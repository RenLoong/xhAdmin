<?php

namespace app\admin\utils;
use Exception;
use support\Log;

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
     * 配合composerMergePlugin插件更新
     *
     * @param  string $name
     * @return void
     */
    public static function composerMergePlugin(string $name)
    {
        $composerPath = base_path("/plugin/{$name}/packages/*/composer.json");
        $data = self::scanPluginComposerJson($composerPath);
        # 通用命令代码
        // $composerCommand = self::$composerCommand;
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        foreach ($data as $package_name) {
            $command = "composer update {$package_name} --no-scripts --no-interaction 2>&1";
            $output = shell_exec($command);
            p($output,"composer---执行结果");
        }
        console_log("应用composer更新完成...");
    }

    /**
     * 扫描指定目录下的composer文件
     *
     * @param  string $dir
     * @return array
     */
    private static function scanPluginComposerJson(string $composerPath) {
        // 获取所有的plugin/*/packages/composer.json文件路径
        $files = glob($composerPath);
        $names = [];
        // 读取每个json文件并解析出name字段
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $json = json_decode($content, true);
            if (isset($json['name'])) {
                $names[] = $json['name'];
            }
        }
        // 返回name字段列表
        return $names;
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
        // $composerCommand = self::$composerCommand;
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "composer require {$package_name}:dev-master  --no-scripts --no-interaction 2>&1";
            $output = shell_exec($command);
            if ($output === null) {
                echo "{$package_name}，依赖包安装失败";
                echo PHP_EOL;
            }else{
                console_log($output);
                if (strpos($output,"Installation failed, reverting")) {
                    self::installDevMain($package_name);
                }
            }
        }
        shell_exec("composer dump-autoload --no-scripts --no-interaction 2>&1");
    }

    /**
     * 安装dev-main版本
     * @param string $package_name
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private static function installDevMain(string $package_name)
    {
        $command = "composer require {$package_name}:dev-main --no-scripts --no-interaction 2>&1";
        $output = shell_exec($command);
        if ($output === null) {
            console_log("{$package_name}，依赖包安装失败");
        }else{
            console_log((string)$output);
        }
    }

    // 更新composer包
    private static function updatePluginPackages(array $packages)
    {
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "composer update {$package_name} --no-scripts --no-interaction 2>&1";
            $output = shell_exec($command);
            if ($output === null) {
                console_log("{$package_name}，依赖包更新失败");
            }else{
                console_log((string)$output);
            }
        }
        shell_exec("composer dump-autoload --no-scripts --no-interaction 2>&1");
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
        // $composerCommand = self::$composerCommand;
        # 切换工作目录
        $basePath = base_path();
        chdir($basePath);
        foreach ($packages as $package_name) {
            if (!$package_name) {
                continue;
            }
            $command = "composer remove {$package_name} --no-scripts --no-interaction 2>&1";
            $output = shell_exec($command);
            if ($output === null) {
                console_log("{$package_name}，依赖包卸载失败");
            }else{
                p($output);
            }
        }
        shell_exec("composer dump-autoload --no-scripts --no-interaction 2>&1");
    }
}
