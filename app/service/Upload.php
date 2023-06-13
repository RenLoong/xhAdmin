<?php

namespace app\service;

use app\admin\model\SystemUpload;
use app\admin\model\SystemUploadCate;
use Exception;
use Shopwwi\WebmanFilesystem\Storage;
use support\Log;
use Webman\Http\UploadFile;

/**
 * 附件服务类
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-04
 */
class Upload
{
    /**
     * 下载远程文件并上传
     * @param mixed $url
     * @param mixed $cid
     * @param mixed $appid
     * @param mixed $config
     * @throws \Exception
     * @return array|bool|mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function remoteFile(string $url, int $cid = 0, int $appid = 0, array $config = []): array
    {
        $fileInfo = pathinfo($url);
        if (!isset($fileInfo['extension'])) {
            throw new Exception('获取远程文件扩展失败');
        }
        if (!isset($fileInfo['filename'])) {
            throw new Exception('获取远程文件名称失败');
        }
        $fileMd5  = md5($fileInfo['filename']);
        $tempFile = runtime_path("/remoteFile/{$fileMd5}.{$fileInfo['extension']}");
        if (!is_dir(dirname($tempFile))) {
            mkdir(dirname($tempFile), 0775, true);
        }
        $file = file_get_contents($url);
        if (!file_put_contents($tempFile, $file)) {
            throw new Exception('远程资源文件下载失败');
        }
        $fileMimeType = self::getFileMimeType($tempFile);
        $uploadFile   = new UploadFile($tempFile, $fileInfo['basename'], $fileMimeType, 0);
        if (!$data = self::upload($uploadFile, $cid, $appid)) {
            throw new Exception('上传文件失败');
        }
        return $data;
    }

    /**
     * 获取文件fileMime
     * @param mixed $file_path
     * @return bool|string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function getFileMimeType($file_path)
    {
        $finfo     = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_path);
        finfo_close($finfo);
        return $mime_type;
    }

    /**
     * 上传文件
     * @param mixed $file
     * @param int $cid
     * @param array $config TODO:该参数预留后续使用
     * @return array|bool|mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    public static function upload($file, int $cid = 0, int $appid = 0, array $config = [])
    {
        try {
            $category = self::getCategory($cid);
            $path     = "upload/{$category['dir_name']}";
            $result   = (new Storage)->path($path)->upload($file, false);
            $data     = self::addUpload($result, $category, $appid);
            return $data;
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * 删除系统附件
     * @param int $id
     * @param mixed $force
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public static function delete(int $id, $force = false): bool
    {
        return SystemUpload::destroy($id, $force);
    }

    /**
     * 获取外链地址
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  string $path
     * @return string
     */
    public static function url(string $path): string
    {
        return (string) (new Storage)->url($path);
    }

    /**
     * 批量获取URL外链
     * @param array $data
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function urls(array $array)
    {
        $data = [];
        foreach ($array as $value) {
            $data[] = self::url($value);
        }
        return $data;
    }

    /**
     * 查询模型数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-09
     * @param  string $path
     * @return array
     */
    public static function model(string $path): array|string
    {
        $where = [
            ['path', '=', $path],
        ];
        $field = [
            'path',
            'format',
            'size',
        ];
        $model = SystemUpload::where($where)->field($field)->find();
        if (!$model) {
            return '';
        }
        return $model->toArray();
    }

    /**
     * URL转PATH
     * @param string|array $url
     * @return array|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function path(string|array $url)
    {
        $config    = config('plugin.shopwwi.filesystem.app');
        $default   = $config['default'] ?? 'public';
        $configUrl = isset($config['storage'][$default]['url']) ? "{$config['storage'][$default]['url']}/" : '';
        if (is_array($url)) {
            $data = [];
            foreach ($url as $value) {
                $data[] = str_replace($configUrl, '', $value);
            }
            return count($data) === 1 ? current($data) : $data;
        } else {
            return str_replace($configUrl, '', $url);
        }
    }

    /**
     * 附件储存
     * @param mixed $result
     * @param mixed $category
     * @param mixed $appid
     * @throws \Exception
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function addUpload($result, array $category, int $appid = 0)
    {
        $fiel_name = basename($result->file_name);
        $where     = [
            ['filename', '=', $fiel_name],
            ['adapter', '=', $result->adapter],
        ];
        $fileModel = SystemUpload::where($where)->find();
        if ($fileModel) {
            $fileModel->update_at = date('Y-m-d H:i:s');
            $fileModel->save();
            return $fileModel->toArray();
        }
        $data['cid']      = $category['id'];
        $data['title']    = $result->origin_name;
        $data['filename'] = basename($result->file_name);
        $data['path']     = $result->file_name;
        $data['format']   = $result->extension;
        $data['size']     = $result->size;
        $data['adapter']  = $result->adapter;
        $data['appid'] = $appid;
        if (!(new SystemUpload)->save($data)) {
            throw new Exception('附件上传失败');
        }
        $data['url'] = self::url($result->file_name);
        return $data;
    }

    /**
     * 获取储存的分类
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @return array
     */
    private static function getCategory(int $cid): array
    {
        if ($cid) {
            $where[] = ['id', '=', $cid];
        } else {
            $where[] = ['is_system', '=', '1'];
        }
        $category = SystemUploadCate::where($where)->find();
        if (!$category) {
            throw new Exception('获取附件分类错误');
        }
        return $category->toArray();
    }
}