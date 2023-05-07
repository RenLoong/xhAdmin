<?php

namespace app\admin\model;

use app\model\Store as ModelStore;

/**
 * 商户
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class Store extends ModelStore
{
    /**
     * 一对一关联租户等级
     * @return \think\model\relation\HasOne
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-02
     */
    public function grade()
    {
        return $this->hasOne(StoreGrade::class, 'id', 'grade_id');
    }

    /**
     * 获取select选项值
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function getSelectOptions()
    {
        $orderBy = [
            'id'        => 'desc',
        ];
        $field = [
            'id'        => 'value',
            'title'     => 'label'
        ];
        $data    = Store::order($orderBy)->field($field)->select()->each(function ($e) {
            $e->label = "ID:{$e->value}---{$e->label}";
        })->toArray();
        return $data;
    }
}
