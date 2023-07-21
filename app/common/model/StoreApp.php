<?php

namespace app\common\model;

use app\Model;
use app\common\service\UploadService;

/**
 * 应用模型
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-03
 */
class StoreApp extends Model
{
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
     * 设置头像储存
     * @param mixed $value
     * @return array|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    protected function setLogoAttr($value)
    {
        return $value ? UploadService::path($value) : '';
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
        return $value ? UploadService::url((string) $value) : '';
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
    public static function selectOptions(array $where = [], string|array $field = '*')
    {
        $field = [
            'title as label',
            'id as value'
        ];
        $data  = StoreApp::where($where)
            ->field($field)
            ->select()
            ->each(function ($value) {
                $value->label = "应用：{$value['label']}";
                return $value;
            })
            ->toArray();
        return $data;
    }
}