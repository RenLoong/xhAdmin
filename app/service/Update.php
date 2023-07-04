<?php

namespace app\service;
use app\utils\DbMgr;
use support\Log;

/**
 * SAAS框架更新服务（支持跨版本升级）
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class Update
{
    /**
     * 更新前置服务
     * @param mixed $version 版本号
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function beforeUpdate($version)
    {
        $sqlDir = base_path('/update/');
        if (!is_dir($sqlDir)) {
            return [];
        }
        $sqlFiles = scandir($sqlDir);
        $extension = '.sql';
        $data      = [];
        foreach ($sqlFiles as $file) {
            if (strpos($file, $extension) !== false) {
                $data[] = $file;
            }
        }
        if (empty($data)) {
            return [];
        }
        $dataList = [];
        foreach ($data as $value) {
            $item = file_get_contents($sqlDir . $value);
            if (!empty($item)) {
                $keyName = str_replace('.sql','',$value);
                $item = DbMgr::removeComments($item);
                $dataList[$keyName] = $item;
            }
        }
        return $dataList;
    }

    /**
     * 开始更新
     * @param mixed $version 版本号
     * @param mixed $data
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function update($version, $data)
    {
        try {
            $prefix = config('database.connections.mysql.prefix');
            $str    = ['`php_', '`yc_'];
            foreach ($data as $file => $sql) {
                $sql = str_replace($str, "`{$prefix}", $sql);
                if (!DbMgr::instance()->statement($sql)) {
                    continue;
                }
                $filePath = base_path('/update/' . $file . '.sql');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $updateDir = base_path('/update');
            if (is_dir($updateDir)) {
                rmdir($updateDir);
            }
            # 更新composer
            self::updateComposer();
        } catch (\Throwable $e) {
            Log::error("执行更新SQL错误：{$e->getMessage()}");
        }
    }

    /**
     * 更新composer
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function updateComposer()
    {
        # 站点目录
        $basePath = base_path();
        # 获取composer
        $composerPath = "{$basePath}/composer.json";
        if (!file_exists($composerPath)) {
            return;
        }
        # 切换工作目录
        chdir($basePath);
        $composerJson = file_get_contents($composerPath);
        $composer     = json_decode($composerJson, true);
        # 检测是否有云服务中心包
        if (empty($composer['require']['yc-open/cloud-service'])) {
            console_log('安装云服务中心...');
            shell_exec("composer require yc-open/cloud-service --no-scripts --no-interaction 2>&1");
            console_log('云服务中心安装完成...');
            return;
        }
        # 检测是否存在阿里云源
        if (!empty($composer['repositories'])) {
            console_log('取消镜像源...');
            unset($composer['repositories']);
            $composer = json_encode($composer, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            file_put_contents($composerPath, $composer);
            shell_exec("composer config --unset repos.packagist --no-scripts --no-interaction 2>&1");
            console_log('取消镜像源完成...');
            return;
        }
    }
}