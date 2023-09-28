<?php
use app\ExceptionHandle;
use app\Request;

// 容器Provider定义文件
return [
    // 'think\Request'          => Request::class,
    'think\Request'             => \support\Request::class,
    'think\exception\Handle'    => ExceptionHandle::class,
];
