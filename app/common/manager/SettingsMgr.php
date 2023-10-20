<?php
namespace app\common\manager;

use app\common\model\SystemConfig;

/**
 * 系统设置管理
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
class SettingsMgr
{
    /**
     * 获取配置项数据
     * @param int|null $appid
     * @param string $name
     * @param string $default
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function get(int|null $appid,string $name,string $default=null)
    {
        $where = [
            'name'      => $name,
            'appid'     => $appid
        ];
        $value = SystemConfig::where($where)->value('value');
        if(empty($value)){
            return $default;
        }
        return $value;
    }

    /**
     * 获取配置分组数据
     * @param int|null $appid
     * @param string $group
     * @param string $default
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function group(int|null $appid,string $group,string $default=null)
    {
        $where = [
            'group'     => $group,
            'appid'     => $appid
        ];
        $value = SystemConfig::where($where)->column('value');
        if(empty($value)){
            return $default;
        }
        return $value;
    }
}