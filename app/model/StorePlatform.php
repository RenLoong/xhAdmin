<?php

namespace app\model;

use app\enum\PlatformTypes;
use app\Model;
use app\service\Upload;
use think\model\concern\SoftDelete;

/**
 * 商户平台
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StorePlatform extends Model
{
    # 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $append = [
        'configs'
    ];

    /**
     * 一对一关联租户
     * @return \think\model\relation\HasOne
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-02
     */
    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    /**
     * 获得配置
     * @param mixed $value
     * @param mixed $data
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    protected function getConfigsAttr($value, $data)
    {
        $where   = [
            'platform_id' => $data['id'],
        ];
        $list    = StorePlatformConfig::where($where)
            ->field('config_field,config_value')
            ->select()
            ->toArray();
        $configs = [];
        foreach ($list as $value) {
            $configs[$value['config_field']] = $value['config_value'];
        }
        return $configs;
    }

    /**
     * 设置头像储存
     * @param mixed $value
     * @return array|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    protected function setLogoAttr($value)
    {
        return is_array($value) ? Upload::path($value) : '';
    }

    /**
     * 解析URL
     * @param mixed $value
     * @return string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    protected function getLogoAttr($value)
    {
        return $value ? Upload::url((string) $value) : '';
    }

    /**
     * 获取平台类型名称
     * @param mixed $value
     * @return mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    protected function getPlatformTypeTextAttr($value,$data)
    {
        return PlatformTypes::getText($data['platform_type'])['text'];
    }

    /**
     * 获取select组件值
     * @param array $where
     * @param string|array $field
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function selectOptions(array $where, string|array $field = '*')
    {
        $data = StorePlatform::where($where)
            ->field($field)
            ->select()
            ->toArray();
        return $data;
    }

    /**
     * 获取Cascader组件值
     * @param array $where
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function getCascaderOptions(array $where = [])
    {
        $field = [
            'title as label',
            'id as value',
        ];
        $data  = StorePlatform::where($where)
            ->field($field)
            ->select()
            ->each(function ($value) {
                $where           = [
                    'platform_id' => $value['id']
                ];
                $field           = [
                    'id as value',
                    'title as label',
                    'store_id',
                ];
                $value->label    = "平台：{$value['label']}";
                $value->children = StoreApp::selectOptions($where, $field);
                return $value;
            })
            ->toArray();
        return $data;
    }
}
