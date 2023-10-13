<?php

return [
    // 默认磁盘
    'default' => 'public',
    // 磁盘列表
    'disks' => [
        'local' => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
        ],
        'public' => [
            // 磁盘类型
            'type' => 'public',
            // 磁盘路径
            'root' => app()->getRootPath() . 'public/storage',
            // 磁盘路径对应的外部URL路径
            'url' => '/storage',
            // 可见性
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
        'aliyun' => [
            'type' => 'aliyun',
            'access_id' => '',
            'access_secret' => '',
            'bucket' => '',
            'endpoint' => '',
            'isCName' => true,
        ],
        'qiniu' => [
            'type' => 'qiniu',
            'access_key' => '******',
            'secret_key' => '******',
            'bucket' => '',
            'domain' => '',
        ],
        'qcloud' => [
            'type' => 'qcloud',
            'region' => '***',
            //bucket 所属区域 英文
            'app_id' => '***',
            // 域名中数字部分
            'secret_id' => '***',
            'secret_key' => '***',
            'bucket' => '***',
            'timeout' => 60,
            'connect_timeout' => 60,
        ],
        'obs' => [
            'type' => 'obs',
            'root' => '',
            'key' => env('OBS_KEY'),
            'secret' => env('OBS_SECRET'),
            'bucket' => env('OBS_BUCKET'),
            'endpoint' => env('OBS_ENDPOINT'),
            'is_cname' => env('OBS_IS_CNAME', false),
            //true or false...
            'security_token' => env('OBS_SECURITY_TOKEN'), //true or false...
        ],
        's3' => [
            'type' => 's3',
            'credentials' => [
                'key' => 'S3_KEY',
                'secret' => 'S3_SECRET',
            ],
            'region' => 'S3_REGION',
            'version' => 'latest',
            'bucket_endpoint' => false,
            'use_path_style_endpoint' => false,
            'endpoint' => 'S3_ENDPOINT',
            'bucket' => 'S3_BUCKET',
        ],
        'google' => [
            'type' => 'google',
            'projectId' => 'GOOGLE_PROJECT_ID', //your-project-id
            'bucket' => 'GOOGLE_BUCKET',
            //your-bucket-name
            'prefix' => '',
            //optional-prefix 
        ],
        'ftp' => [
            'type' => 'ftp',
            'host' => 'example.com',
            'username' => 'username',
            'password' => 'password',
            // 可选的 FTP 设置
            // 'port' => 21,
            // 'root' => '',
            // 'passive' => true,
            // 'ssl' => true,
            // 'timeout' => 30,
            // 'url'=>''
        ],
        'sftp' => [
            'type' => 'sftp',
            'host' => 'example.com',
            // 基于基础的身份验证设置...
            'username' => 'username',
            'password' => 'password',
            // 使用加密密码进行基于 SSH 密钥的身份验证的设置...
            'privateKey' => null,
            'passphrase' => null,
            // 可选的 SFTP 设置
            'port' => 22,
            'root' => '/path/to/root',
            'url' => '/path/to/root',
            'timeout' => 10
        ]
    ],
];