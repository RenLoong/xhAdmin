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
        $pluginPath = base_path("/plugin/{$plugin_name}/composer.json");
        if (!file_exists($pluginPath)) {
            return;
        }
        $json = file_get_contents($pluginPath);
        if (!$json) {
            return;
        }
        $response = json_decode($json, true);
        if (!is_array($response)) {
            return;
        }
        if (!isset($response['require'])) {
            throw new Exception('composer包数据格式错误');
        }
        $packages = $response['require'];
        if ($isUpdate) {
            // 执行更新包
            self::updatePluginPackages($packages);
        } else {
            // 执行安装包
            self::installPluginPackages($packages);
        }
    }

    // 安装composer
    private static function installPluginPackages(array $packages)
    {
        $basePath = base_path();
        foreach ($packages as $package_name => $version) {
            if (!$package_name) {
                continue;
            }
            if (!$version) {
                continue;
            }
            $command = "cd {$basePath} && composer require --no-interaction {$package_name}:{$version};";
            shell_exec($command);
        }
        shell_exec("cd {$basePath} && composer --no-interaction dump-autoload");
        echo "composer --- 安装完成".PHP_EOL;
    }

    // 更新composer包
    private static function updatePluginPackages(array $packages)
    {
        $basePath = base_path();
        foreach ($packages as $package_name => $version) {
            if (!$package_name) {
                continue;
            }
            if (!$version) {
                continue;
            }
            $command = "cd {$basePath} && composer update --no-interaction {$package_name}";
            shell_exec($command);
        }
        shell_exec("cd {$basePath} && composer --no-interaction dump-autoload");
        echo "composer --- 更新完成" . PHP_EOL;
    }
}
