<?php
return [
    'enable' => true,
    'default' => 'public',
    'max_size' => 1024 * 1024 * 50, //单个文件大小10M
    # 允许上传文件类型 为空则为允许所有
    'ext_yes' => [],
    # 不允许上传文件类型 为空则不限制
    'ext_no' => [],
    'storage' => [
        // 上传至public目录
        'public' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\LocalAdapterFactory::class,
            'root' => public_path(),
            'url' => env('UPLOAD_PUBLIC_URL','') // 静态文件访问域名
        ],
        // 阿里云驱动
        'oss' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\AliyunOssAdapterFactory::class,
            'accessId' => '',
            'accessSecret' => '',
            'bucket' => '',
            'endpoint' => '',
            'url' => '', // 静态文件访问域名
            // 'timeout' => 3600,
            // 'connectTimeout' => 10,
            // 'isCName' => false,
            // 'token' => null,
            // 'proxy' => null,
        ],
        // 七牛云驱动
        'qiniu' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\QiniuAdapterFactory::class,
            'accessKey' => '',
            'secretKey' => '',
            'bucket' => '',
            'domain' => '',
            'url' => '' // 静态文件访问域名
        ],
        // 腾讯云驱动
        'cos' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\CosAdapterFactory::class,
            'region' => '',
            'app_id' => '',
            'secret_id' => '',
            'secret_key' => '',
            // 可选，如果 bucket 为私有访问请打开此项
            // 'signed_url' => false,
            'bucket' => '',
            'read_from_cdn' => false,
            'url' => '', // 静态文件访问域名
            // 'timeout' => 60,
            // 'connect_timeout' => 60,
            // 'cdn' => '',
            // 'scheme' => 'https',
        ],
    ],
];
