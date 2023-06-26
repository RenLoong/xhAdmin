<?php
return [
    // 默认数据库
    'default' => env('TYPE'),
    // 各种数据库配置
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('HOSTNAME'),
            'port' => env('HOSTPORT'),
            'database' => env('DATABASE'),
            'username' => env('USERNAME'),
            'password' => env('PASSWORD'),
            'unix_socket' => '',
            'charset' => env('CHARSET'),
            'collation' => env('CHARSET_CI'),
            'prefix' => env('PREFIX'),
            'strict' => true,
            'engine' => null,
            'options' => [
                \PDO::ATTR_TIMEOUT => 3
            ]
        ],
    ],
];