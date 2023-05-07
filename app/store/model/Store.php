<?php

namespace app\store\model;

/**
 * 商户
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class Store extends \app\model\Store
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
}
