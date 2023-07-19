<?php
return [
    # 全局中间件
    ''    => [],
    # 后台中间件
    'admin'     => [
        \app\admin\middleware\AuthMiddleware::class
    ],
    # 用户中间件
    'store'     => [
        \app\store\middleware\AuthMiddleware::class
    ],
];
