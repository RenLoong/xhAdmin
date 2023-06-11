<?php

namespace app;

/**
 * @title SAAS框架
 * @desc 类描述
 * @author 楚羽幽 <cy958416459@qq.com>
 */
class Install
{
    /**
     * 安装前置操作
     *
     * @param  type $version
     * @return void
     */
    public static function beforeUpdate($version)
    {
    }

    /**
     * 安装后置操作
     *
     * @param  type $version
     * @param  type $context
     * @return void
     */
    public static function update($version, $context)
    {
        self::updateComposer();
    }

    /**
     * 更新composer数据
     *
     * @return void
     */
    private static function updateComposer()
    {
        $newComposer = self::composerJSON(app_path('/composer.txt'));
        if (!$newComposer) {
            console_log('无需更新composer');
            return;
        }
        $oldComposerPath = base_path('/composer.json');
        $oldComposer = self::composerJSON($oldComposerPath);
        if ($oldComposer) {
            $newComposer['require'] = $oldComposer['require'];
        }
        $data = json_encode($newComposer, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $data = str_replace("\\/", '/', $data);
        file_put_contents($oldComposerPath, $data);
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        # 执行更新命令
        $composerCommand = 'export COMPOSER_HOME=/www/server/php/80/bin;COMPOSER_ALLOW_SUPERUSER=1;';
        $command = "{$composerCommand}composer update --no-interaction 2>&1";
        $output = shell_exec($command);
        p($output,'框架更新结果');
        var_dump($output);
        if ($output === null) {
            console_log("composer安装失败");
        } else {
            // 停止进程
            posix_kill(posix_getppid(), SIGINT);
        }
    }

    /**
     * 获取composer
     *
     * @param  string     $path
     * @return array|bool
     */
    private static function composerJSON(string $path): array|bool
    {
        if (!file_exists($path)) {
            return false;
        }
        $data = file_get_contents($path);
        $data = json_decode($data, true);
        if (!$data) {
            return false;
        }
        return $data;
    }
}
