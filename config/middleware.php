<?php
return [
    'admin'     => [
        \app\admin\middleware\AccessMiddleware::class
    ],
    'store'     => [
        \app\store\middleware\AccessMiddleware::class
    ],
];
