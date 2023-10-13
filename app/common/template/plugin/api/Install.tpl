<?php

namespace plugin\{PLUGIN_NAME}\api;

use app\common\manager\DbMgr;
use think\facade\Db;
use think\facade\Log;

class Install
{
    /**
     * 安装应用
     * @param mixed $version
     * @return bool
     * @author John
     */
    public static function install($version): bool
    {
    }

    /**
     * 卸载应用
     * @param mixed $version
     * @return bool
     * @author John
     */
    public static function uninstall($version): bool
    {
    }

    /**
     * 更新应用
     * @param mixed $from_version
     * @param mixed $to_version
     * @param mixed $data
     * @return void
     * @author John
     */
    public static function update($from_version, $to_version, $data = null)
    {
    }

    /**
     * 更新前
     * @param mixed $from_version
     * @param mixed $to_version
     * @return array
     * @author John
     */
    public static function beforeUpdate($from_version, $to_version)
    {
        $data = [];
        return $data;
    }
}
