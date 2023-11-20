<?php
namespace think;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\SystemUpdateRequest;

define('ROOT_PATH', __DIR__);
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
    file_put_contents(ROOT_PATH . '/config/version.json', $versionJson);
    echo '最新版本自动部署成功';
    echo PHP_EOL;
} catch (\Throwable $e) {
    exit($e->getMessage());
}