<?php
namespace app\manager;
use app\model\StorePlatformConfig;
use Exception;

class StorePlatformsConfig
{
    /**
     * 获取某平台配置
     * @param int $platform_id
     * @param array|string $fields
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-18
     */
    public static function config(int $platform_id,array|string $fields = '')
    {
        if (is_string($fields) && $fields) {
            $fields = explode(',', $fields);
        }
        $where = [
            ['platform_id','=',$platform_id],
        ];
        if ($fields) {
            $where[] = ['config_field','in',$fields];
        }
        $data = StorePlatformConfig::where($where)->column('config_value','config_field');
        return $data;
    }
}