<?php
namespace think;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\SystemUpdateRequest;

define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));
define('XH_PUBLIC_PATH', ROOT_PATH.'/public');
require ROOT_PATH . '/vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();
$http->end($response);

header('Content-Type:application/json; charset=utf-8');

try {
    # 写入版本信息
    $req = new SystemUpdateRequest;
    $req->newVersion();
    $cloud             = new Cloud($req);
    $data              = $cloud->send();
    $versionData       = [
        'version'       => $data->version,
        'version_name'  => $data->version_name
    ];
    $versionJson       = json_encode($versionData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    print_r($versionJson);
    // file_put_contents(ROOT_PATH . '/config/version.json', $versionJson);
} catch (\Throwable $e) {
    exit($e->getMessage());
}