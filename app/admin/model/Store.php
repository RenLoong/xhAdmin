<?php

namespace app\admin\model;

use app\common\enum\PlatformTypes;
use app\common\model\Store as ModelStore;
use app\common\model\StoreApp;

/**
 * 商户
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class Store extends ModelStore
{

    // 追加输出
    protected $append = [
        'surplusNum',
    ];

    # 获取资产数量
    protected function getSurplusNumAttr($value, $data)
    {
        $platforms = PlatformTypes::toArray();

        $list = [];
        foreach ($platforms as $item) {
            $where = [
                'store_id'          => $data['id'],
                'platform'     => $item['value']
            ];
            $created = StoreApp::where($where)->count();

            # 总数量
            $sum = $data[$item['value']] + $created;

            # 已创建数量 / 总数量
            $list[$item['value']] = "{$created} / {$sum}";
        }
        return $list;
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
