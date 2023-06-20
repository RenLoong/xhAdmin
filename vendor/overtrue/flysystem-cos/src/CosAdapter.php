<?php

namespace Overtrue\Flysystem\Cos;

use GuzzleHttp\Psr7\Uri;
use League\Flysystem\Config;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\PathPrefixer;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\Visibility;
use Overtrue\CosClient\ObjectClient;
use Overtrue\CosClient\BucketClient;

class CosAdapter implements FilesystemAdapter
{
    /**
     * @var \Overtrue\CosClient\ObjectClient|null
     */
    protected ?ObjectClient $objectClient;

    /**
     * @var \Overtrue\CosClient\BucketClient|null
     */
    protected ?BucketClient $bucketClient;

    /**
     * @var PathPrefixer
     */
    protected PathPrefixer $prefixer;

    /**
     * @var array
     */
    protected $config;

    /**
     * CosAdapter constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = \array_merge(
            [
                'bucket' => null,
                'app_id' => null,
                'region' => 'ap-guangzhou',
                'signed_url' => false,
            ],
            $config
        );

        $this->prefixer = new PathPrefixer($config['prefix'] ?? '', DIRECTORY_SEPARATOR);
    }

    public function fileExists(string $path): bool
    {
        return $this->getMetadata($path) !== null;
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $prefixedPath = $this->prefixer->prefixPath($path);
        $response = $this->getObjectClient()->putObject($prefixedPath, $contents, $config->get('headers', []));

        if (!$response->isSuccessful()) {
            throw UnableToWriteFile::atLocation($path, (string)$response->getBody());
        }

        if ($visibility = $config->get('visibility')) {
            $this->setVisibility($path, $visibility);
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->write($path, \stream_get_contents($contents), $config);
    }

    public function readStream(string $path)
    {
        $prefixedPath = $this->prefixer->prefixPath($path);

        $response = $this->getObjectClient()->get(\urlencode($prefixedPath), ['stream' => true]);

        if ($response->isNotFound()) {
            return false;
        }

        return $response->getBody()->detach();
    }

    public function read(string $path): string
    {
        $prefixedPath = $this->prefixer->prefixPath($path);

        $response = $this->getObjectClient()->getObject($prefixedPath);
        if ($response->isNotFound()) {
            throw UnableToReadFile::fromLocation($path, (string)$response->getBody());
        }

        return (string)$response->getBody();
    }

    public function move(string $source, string $destination, Config $config): void
    {
        $this->copy($source, $destination, $config);

        $this->delete($this->prefixer->prefixPath($source));
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        $prefixedSource = $this->prefixer->prefixPath($source);

        $location = $this->getSourcePath($prefixedSource);

        $prefixedDestination = $this->prefixer->prefixPath($destination);

        $response = $this->getObjectClient()->copyObject(
            $prefixedDestination,
            [
                'x-cos-copy-source' => $location,
            ]
        );
        if (!$response->isSuccessful()) {
            throw UnableToCopyFile::fromLocationTo($source, $destination);
        }
    }

    public function delete(string $path): void
    {
        $prefixedPath = $this->prefixer->prefixPath($path);

        $response = $this->getObjectClient()->deleteObject($prefixedPath);

        if (!$response->isSuccessful()) {
            throw UnableToDeleteFile::atLocation($path, (string)$response->getBody());
        }
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $prefixedPath = $this->prefixer->prefixPath($path);

        $response = $this->listObjects($prefixedPath, $deep);

        // 处理目录
        foreach ($response['CommonPrefixes'] ?? [] as $prefix) {
            yield new DirectoryAttributes($prefix);
        }

        foreach ($response['Contents'] ?? [] as $content) {
            yield new FileAttributes(
                $content['Key'],
                \intval($content['Size']),
                null,
                \strtotime($content['LastModified'])
            );
        }
    }

    public function getMetadata($path): ?FileAttributes
    {
        $prefixedPath = $this->prefixer->prefixPath($path);

        $meta = $this->getObjectClient()->headObject($prefixedPath)->getHeaders();
        if (empty($meta)) {
            return null;
        }

        return new FileAttributes(
            $path,
            isset($meta['Content-Length'][0]) ? \intval($meta['Content-Length'][0]) : null,
            null,
            isset($meta['Last-Modified'][0]) ? \strtotime($meta['Last-Modified'][0]) : null,
            $meta['Content-Type'][0] ?? null,
        );
    }

    public function fileSize(string $path): FileAttributes
    {
        $meta = $this->getMetadata($path);
        if ($meta->fileSize() === null) {
            throw UnableToRetrieveMetadata::fileSize($path);
        }

        return $meta;
    }

    public function mimeType(string $path): FileAttributes
    {
        $meta = $this->getMetadata($path);
        if ($meta->mimeType() === null) {
            throw UnableToRetrieveMetadata::mimeType($path);
        }
        return $meta;
    }

    public function lastModified(string $path): FileAttributes
    {
        $meta = $this->getMetadata($path);
        if ($meta->lastModified() === null) {
            throw UnableToRetrieveMetadata::lastModified($path);
        }

        return $meta;
    }

    public function visibility(string $path): FileAttributes
    {
        $prefixedPath = $this->prefixer->prefixPath($path);

        $meta = $this->getObjectClient()->getObjectACL($prefixedPath);

        foreach ($meta['AccessControlPolicy']['AccessControlList']['Grant'] ?? [] as $grant) {
            if ('READ' === $grant['Permission'] && false !== strpos($grant['Grantee']['URI'] ?? '', 'global/AllUsers')) {
                return new FileAttributes($path, null, Visibility::PUBLIC);
            }
        }

        return new FileAttributes($path, null, Visibility::PRIVATE);
    }

    public function setVisibility(string $path, string $visibility): void
    {
        $this->getObjectClient()->putObjectACL(
            $this->prefixer->prefixPath($path),
            [],
            [
                'x-cos-acl' => $this->normalizeVisibility($visibility),
            ]
        );
    }

    public function createDirectory(string $path, Config $config): void
    {
        $dirname = $this->prefixer->prefixPath($path);

        $this->getObjectClient()->putObject($dirname . '/', '');
    }

    public function deleteDirectory(string $path): void
    {
        $dirname = $this->prefixer->prefixPath($path);

        $response = $this->listObjects($dirname);

        if (empty($response['Contents'])) {
            return;
        }

        $keys = array_map(
            function ($item) {
                return ['Key' => $item['Key']];
            },
            $response['Contents']
        );

        $response = $this->getObjectClient()->deleteObjects(
            [
                'Delete' => [
                    'Quiet' => 'false',
                    'Object' => $keys,
                ],
            ]
        );

        if (!$response->isSuccessful()) {
            throw UnableToDeleteDirectory::atLocation($path, (string) $response->getBody());
        }
    }

    public function getUrl(string $path)
    {
        $prefixedPath = $this->prefixer->prefixPath($path);

        if (!empty($this->config['cdn'])) {
            return \strval(new Uri(\sprintf('%s/%s', \rtrim($this->config['cdn'], '/'), $prefixedPath)));
        }

        return $this->config['signed_url'] ? $this->getSignedUrl($path) : $this->getObjectClient()->getObjectUrl($prefixedPath);
    }

    public function getSignedUrl(string $path, string $expires = '+60 minutes'): string
    {
        $prefixedPath = $this->prefixer->prefixPath($path);

        return $this->getObjectClient()->getObjectSignedUrl($prefixedPath, $expires);
    }

    public function getObjectClient()
    {
        return $this->objectClient ?? $this->objectClient = new ObjectClient($this->config);
    }

    public function getBucketClient()
    {
        return $this->bucketClient ?? $this->bucketClient = new BucketClient($this->config);
    }

    /**
     * @param \Overtrue\CosClient\ObjectClient $objectClient
     *
     * @return $this
     */
    public function setObjectClient(ObjectClient $objectClient)
    {
        $this->objectClient = $objectClient;

        return $this;
    }

    /**
     * @param \Overtrue\CosClient\BucketClient $bucketClient
     *
     * @return $this
     */
    public function setBucketClient(BucketClient $bucketClient)
    {
        $this->bucketClient = $bucketClient;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getSourcePath(string $path)
    {
        return sprintf(
            '%s-%s.cos.%s.myqcloud.com/%s',
            $this->config['bucket'],
            $this->config['app_id'],
            $this->config['region'],
            $path
        );
    }

    /**
     * @param string $directory
     * @param bool $recursive
     *
     * @return mixed
     */
    protected function listObjects($directory = '', $recursive = false)
    {
        $result = $this->getBucketClient()->getObjects(
            [
                'prefix' => ('' === (string)$directory) ? '' : ($directory . '/'),
                'delimiter' => $recursive ? '' : '/',
            ]
        )['ListBucketResult'];

        foreach (['CommonPrefixes', 'Contents'] as $key) {
            $result[$key] = $result[$key] ?? [];

            // 确保是二维数组
            if (($index = \key($result[$key])) !== 0) {
                $result[$key] = \is_null($index) ? [] : [$result[$key]];
            }
        }

        return $result;
    }

    protected function normalizeVisibility(string $visibility): string
    {
        return $visibility === Visibility::PUBLIC ? 'public-read' : 'default';
    }

    protected function toResource(string $body)
    {
        $resource = fopen('php://temp', 'r+');
        if ($body !== '') {
            fwrite($resource, $body);
            fseek($resource, 0);
        }

        return $resource;
    }
}
