<?php

use Webman\Route;

// 关闭默认路由
Route::disableDefaultRoute();

// 初始化并注册强制路由
\app\utils\RoutesMgr::init();
