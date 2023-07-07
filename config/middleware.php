<?php
return [
    # 全局中间件
    ''    => [],
    # 后台中间件
    'admin'     => [
        \app\admin\middleware\AccessMiddleware::class
    ],
    # 租户中间件
    'store'     => [
        \app\store\middleware\AccessMiddleware::class
    ],
];
