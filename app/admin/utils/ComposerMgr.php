<?php

namespace app\admin\utils;

use Exception;

/**
 * Composer操作类
 */
class ComposerMgr
{
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
        $basePath = base_path();
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "cd {$basePath} && composer remove --no-interaction {$package_name}";
            shell_exec($command);
        }
        shell_exec("cd {$basePath} && composer --no-interaction dump-autoload");
        echo "composer --- 卸载完成" . PHP_EOL;
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
        $basePath = base_path();
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "cd {$basePath} && composer require --no-interaction {$package_name}:dev-main;";
            shell_exec($command);
        }
        shell_exec("cd {$basePath} && composer --no-interaction dump-autoload");
        echo "composer --- 安装完成" . PHP_EOL;
    }

    // 更新composer包
    private static function updatePluginPackages(array $packages)
    {
        $basePath = base_path();
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "cd {$basePath} && composer update --no-interaction {$package_name}:dev-main;";
            shell_exec($command);
        }
        shell_exec("cd {$basePath} && composer --no-interaction dump-autoload");
        echo "composer --- 更新完成" . PHP_EOL;
    }
}
