<?php

return [
    // 静态文件后缀
    'static_suffix' => ['html', 'xml', 'json', 'css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'ico', 'woff', 'woff2', 'ttf', 'eot', 'svg', 'map', 'vue','svg'],
    // 中间件
    'middleware' => [
        \app\common\middleware\XhMiddleware::class,
    ]
];