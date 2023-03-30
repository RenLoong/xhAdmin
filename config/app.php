<?php

use support\Request;

$APP_DEBUG = getenv('APP_DEBUG');
$APP_DEBUG = eval("return {$APP_DEBUG};");

return [
    'debug' => $APP_DEBUG,
    'error_reporting' => E_ALL,
    'default_timezone' => getenv('DEFAULT_TIMEZONE'),
    'request_class' => Request::class,
    'public_path' => base_path() . DIRECTORY_SEPARATOR . 'public',
    'runtime_path' => base_path(false) . DIRECTORY_SEPARATOR . 'runtime',
    'controller_suffix' => 'Controller',
    'controller_reuse' => false,
];
