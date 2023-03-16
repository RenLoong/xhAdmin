<?php

namespace app\admin\logic;

use app\admin\model\StoreGrade as ModelStoreGrade;

/**
 * 租户等级逻辑
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StoreGrade
{
    /**
     * 获取租户等级
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @return array
     */
    public static function getOptions(): array
    {
        $where = [
            'status'        => '1'
        ];
        $order = [
            'sort' => 'asc',
            'id' => 'asc'
        ];
        $field = [
            'id as value',
            'title as label'
        ];
        $list = ModelStoreGrade::where($where)
            ->order($order)
            ->field($field)
            ->select()
            ->toArray();
        return $list;
    }
}
