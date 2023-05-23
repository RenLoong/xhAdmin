<?php

return [
    'default'     => env('TYPE'),
    'connections' => [
        'mysql' => [
            // 数据库类型
            'type'            => env('TYPE'),
            // 服务器地址
            'hostname'        => env('HOSTNAME'),
            // 数据库名
            'database'        => env('DATABASE'),
            // 数据库用户名
            'username'        => env('USERNAME'),
            // 数据库密码
            'password'        => env('PASSWORD'),
            // 数据库连接端口
            'hostport'        => env('HOSTPORT'),
            // 数据库连接参数
            'params'          => [
                    // 连接超时3秒
                    \PDO::ATTR_TIMEOUT => 3,
            ],
            // 数据库编码默认采用utf8
            'charset'         => env('CHARSET'),
            // 数据库表前缀
            'prefix'          => env('PREFIX'),
            // 断线重连
            'break_reconnect' => true,
            // 关闭SQL监听日志
            'trigger_sql'     => false,
            // 自定义分页类
            'bootstrap'       => 'app\\utils\\Paginator'
        ],
    ],
];