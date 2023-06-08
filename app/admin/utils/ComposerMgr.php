<?php

namespace app\admin\utils;

/**
 * Composer操作类
 */
class ComposerMgr
{
    # composer通用命令
    private static $composerCommand = 'export COMPOSER_HOME=/www/server/php/80/bin;COMPOSER_ALLOW_SUPERUSER=1;';

    // 检测并安装composer扩展
    public static function check_plugin_dependencies(string $plugin_name, $isUpdate = false)
    {
        // 获取包数据
        $package_names = self::packageBefore($plugin_name);
        if (!$package_names) {
            return;
        }
        if ($isUpdate) {
            // 执行更新包
            self::updatePluginPackages($package_names);
        } else {
            // 执行安装包
            self::installPluginPackages($package_names);
        }
    }

    /**
     * 卸载包
     *
     * @param  string $plugin_name
     * @return void
     */
    public static function uninstall(string $plugin_name)
    {
        // 获取包数据
        $packages = self::packageBefore($plugin_name);
        if (!$packages) {
            return;
        }
        # 通用命令代码
        $composerCommand = self::$composerCommand;
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "{$composerCommand}composer remove {$package_name} --no-interaction 2>&1";
            $output = shell_exec($command);
            if ($output === null) {
                echo "{$package_name}，依赖包卸载失败";
                echo PHP_EOL;
            }
        }
        shell_exec("composer --no-interaction dump-autoload");
    }

    /**
     * 执行前收集数据
     *
     * @param  string     $plugin_name
     * @return null|array
     */


    private static function packageBefore(string $plugin_name): null|array
    {
        $pluginPath = base_path("/plugin/{$plugin_name}/packages/composer.txt");
        if (!file_exists($pluginPath)) {
            return null;
        }
        $packageContent = file_get_contents($pluginPath);
        if (!$packageContent) {
            return null;
        }
        $package_names = array_filter(explode("\n", $packageContent));
        if (!is_array($package_names) || empty($package_names)) {
            return null;
        }
        return $package_names;
    }

    // 安装composer
    private static function installPluginPackages(array $packages)
    {
        # 通用命令代码
        $composerCommand = self::$composerCommand;
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "{$composerCommand}composer require {$package_name} --no-interaction 2>&1";
            $output = shell_exec($command);
            if ($output === null) {
                echo "{$package_name}，依赖包安装失败";
                echo PHP_EOL;
            }
        }
        shell_exec("composer --no-interaction dump-autoload");
    }

    // 更新composer包
    private static function updatePluginPackages(array $packages)
    {
        # 通用命令代码
        $composerCommand = self::$composerCommand;
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "{$composerCommand}composer update {$package_name} --no-interaction 2>&1";
            $output = shell_exec($command);
            if ($output === null) {
                echo "{$package_name}，依赖包更新失败";
                echo PHP_EOL;
            }
        }
        shell_exec("composer --no-interaction dump-autoload");
    }
}
