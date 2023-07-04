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
        } catch (\Throwable $e) {
            Log::error("执行更新SQL错误：{$e->getMessage()}");
        }
    }
}