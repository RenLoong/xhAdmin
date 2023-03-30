<?php
return [
    'enable' => true,
    'default' => 'public',
    'max_size' => 1024 * 1024 * 10, //单个文件大小10M
    'ext_yes' => [], //允许上传文件类型 为空则为允许所有
    'ext_no' => [], // 不允许上传文件类型 为空则不限制
    'storage' => [
        // 上传至public目录
        'public' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\LocalAdapterFactory::class,
            'root' => public_path(),
            'url' => '//127.0.0.1:39200' // 静态文件访问域名
        ],
        // 阿里云驱动
        'oss' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\AliyunOssAdapterFactory::class,
            'accessId' => 'OSS_ACCESS_ID',
            'accessSecret' => 'OSS_ACCESS_SECRET',
            'bucket' => 'OSS_BUCKET',
            'endpoint' => 'OSS_ENDPOINT',
            'url' => '' // 静态文件访问域名
            // 'timeout' => 3600,
            // 'connectTimeout' => 10,
            // 'isCName' => false,
            // 'token' => null,
            // 'proxy' => null,
        ],
        // 七牛云驱动
        'qiniu' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\QiniuAdapterFactory::class,
            'accessKey' => 'QINIU_ACCESS_KEY',
            'secretKey' => 'QINIU_SECRET_KEY',
            'bucket' => 'QINIU_BUCKET',
            'domain' => 'QINBIU_DOMAIN',
            'url' => '' // 静态文件访问域名
        ],
        // 腾讯云驱动
        'cos' => [
            'driver' => \Shopwwi\WebmanFilesystem\Adapter\CosAdapterFactory::class,
            'region' => 'COS_REGION',
            'app_id' => 'COS_APPID',
            'secret_id' => 'COS_SECRET_ID',
            'secret_key' => 'COS_SECRET_KEY',
            // 可选，如果 bucket 为私有访问请打开此项
            // 'signed_url' => false,
            'bucket' => 'COS_BUCKET',
            'read_from_cdn' => false,
            'url' => '' // 静态文件访问域名
            // 'timeout' => 60,
            // 'connect_timeout' => 60,
            // 'cdn' => '',
            // 'scheme' => 'https',
        ],
    ],
];
