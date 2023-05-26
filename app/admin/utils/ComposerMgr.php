<?php

namespace app\admin\utils;

/**
 * Composer操作类
 */
class ComposerMgr
{
    // 检测并安装composer扩展
    public static function check_plugin_dependencies(string $plugin_name,$isUpdate = false)
    {
        $pluginPath = base_path("/plugin/{$plugin_name}/composer.json");
        if (!file_exists($pluginPath)) {
            return;
        }
        $json = file_get_contents($pluginPath);
        if (!$json) {
            return;
        }
        $packages = json_decode($json,true);
        if (!is_array($packages)) {
            return;
        }
        if ($isUpdate) {
            // 执行更新包
            self::updatePluginPackages($packages);
        }else{
            // 执行安装包
            self::installPluginPackages($packages);
        }
    }


    // 安装composer
    private static function installPluginPackages(array $packages)
    {
        $basePath = base_path();
        foreach ($packages as $package) {
            if (! isset($package['package_name'])) {
                continue;
            }
            if (! isset($package['version'])) {
                continue;
            }
            $command = "cd {$basePath} && composer require {$package['package_name']} {$package['version']}";
            shell_exec($command);
        }
    }

    // 更新composer包
    private static function updatePluginPackages(array $packages)
    {
        $basePath = base_path();
        foreach ($packages as $package) {
            if (! isset($package['package_name'])) {
                continue;
            }
            if (! isset($package['version'])) {
                continue;
            }
            $command = "cd {$basePath} && composer update {$package['package_name']}";
            shell_exec($command);
        }
    }
}