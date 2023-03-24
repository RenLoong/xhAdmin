<?php

namespace app\admin\model;

use app\model\StoreGrade as ModelStoreGrade;

/**
 * 商户等级
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StoreGrade extends ModelStoreGrade
{
    /**
     * 设置为默认等级，则取消其他默认
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-13
     * @param  type $value
     * @return void
     */
    protected function setIsDefaultAttr($value)
    {
        if ($value) {
            StoreGrade::where(['is_default' => '1'])->save(['is_default' => '0']);
        }
        return $value;
    }
}
