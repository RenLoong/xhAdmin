<?php

namespace app\controller;

use app\BaseController;
use app\utils\Utils;
use support\Request;

/**
 * 默认控制器
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
class IndexController extends BaseController
{
    /**
     * 首页视图
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function index(Request $request)
    {
        // 检测是否安装
        if (!file_exists(base_path('/.env'))) {
            return redirect('/install/');
        }
        return redirect('/store/');
    }

    public function test()
    {
        self::checkComposer();
        return $this->fail('开发测试');
    }
    private static function checkComposer()
    {
        # 标记是否最终要操作composer数据
        $composerAction = false;
        # composer包路径
        $composerPath = base_path('/composer.json');
        if (!file_exists($composerPath)) {
            return;
        }
        $json = file_get_contents($composerPath);
        if (!$json) {
            return;
        }
        # 解析JSON
        $composer = json_decode($json, true);
        # 移除开发包
        if (isset($composer['minimum-stability'])) {
            unset($composer['minimum-stability']);
            $composerAction = true;
        }
        $composer = str_replace("\\/", '/', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        # 检测最终是否要动composer
        if ($composerAction) {
            # 切换工作目录
            $basePath = base_path();
            chdir($basePath);
            # 删除锁定文件
            unlink(base_path('/composer.lock'));
            # 删除包目录
            is_dir(base_path('/vendor')) ? shell_exec('rm -rf ' . base_path('/vendor')) : '';
            # 重写包数据
            file_put_contents($composerPath, $composer);
            # 开始安装composer
            console_log("开始安装composer");
            # 执行composer安装
            $composerCommand = 'export COMPOSER_HOME=/www/server/php/80/bin;COMPOSER_ALLOW_SUPERUSER=1;';
            $command = "{$composerCommand}composer install --no-interaction 2>&1";
            $output = shell_exec($command);
            var_dump($output);
            if ($output === null) {
                console_log("composer安装失败");
            }else{
                p($output);
                // 停止进程
                posix_kill(posix_getppid(), SIGINT);
            }
        }
    }
}
