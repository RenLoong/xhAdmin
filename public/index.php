<?php

use Webman\Config;

define('ROOT_PATH', dirname(__DIR__));
define('KF_PUBLIC_PATH', __DIR__);
define('KF_INSTALL_PATH', KF_PUBLIC_PATH.'/install');
header('content-type:application/json');
require ROOT_PATH . "/vendor/autoload.php";
require KF_INSTALL_PATH . "/utils/Dir.php";
require KF_INSTALL_PATH . "/utils/Db.php";
require KF_INSTALL_PATH . "/utils/RedisMgr.php";
require KF_INSTALL_PATH . "/utils/Request.php";
require KF_INSTALL_PATH . "/utils/Json.php";
require KF_INSTALL_PATH . "/utils/BtPanel.php";
require KF_INSTALL_PATH . "/utils/Validated.php";
require KF_INSTALL_PATH . "/utils/Helpers.php";
try {
    // 未安装，跳转至安装页面
    if (!file_exists(ROOT_PATH . '/.env') && strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
        header('Location:/install/');
    }
    // 已安装，跳转至已安装
    if (file_exists(ROOT_PATH . '/.env')) {
        $type = isset($_POST['type']) ? ucfirst($_POST['type']) : ucfirst('custom');
        if ($type !== 'Custom') {
            $desc = <<<str
            <div>恭喜您，安装成功</div>
            <div>请选择以下方式进行访问</div>
            str;
        } else {
            $desc = <<<str
            <div>恭喜您，安装成功</div>
            <div>【警告】请输入php webman start启动框架</div>
            <div>守护进程模式使用-d参数启动</div>
            <div>然后再选择以下方式进行访问</div>
            str;
        }
        exit(Json::json('success', 200, [
            'install'   => 'ok',
            'desc'      => $desc,
        ]));
    }
    $ctrlName = isset($_GET['c']) ? ucfirst($_GET['c']) : ucfirst('default');
    $action = isset($_GET['a']) ? $_GET['a'] : 'index';
    $controller = "{$ctrlName}Controller";
    $ctrlPath = KF_INSTALL_PATH . '/controller/' . $controller . '.php';
    if (!file_exists($ctrlPath)) {
        exit(Json::fail('找不到【' . $controller . '】控制器'));
    }
    require_once $ctrlPath;
    $class = new $controller;
    if (!method_exists($class, $action)) {
        exit(Json::fail('找不到【' . $controller . '/' . $action . '】方法'));
    }
    // 加载配置项
    Config::load(ROOT_PATH . '/config');
    // 执行请求
    exit($class->$action());
} catch (\Throwable $e) {
    exit(Json::json($e->getMessage(), $e->getCode()));
}
