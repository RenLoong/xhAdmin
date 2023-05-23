<?php

namespace app\manager;

use app\enum\PlatformTypes;
use app\model\Store;
use app\model\StorePlatform;
use Exception;

class StorePlatforms
{
    /**
     * 获取选择器数据值
     * @param int $store_id
     * @param bool $isAlias
     * @param string $aliasText
     * @return array<array>
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    public static function getSelectOptions(int $store_id, bool $isAlias = false, string $aliasText = '可创建')
    {
        # 平台类型枚举
        $platformTypes = PlatformTypes::getData();

        $data = [];
        foreach ($platformTypes as $key => $value) {
            // 剩余数量
            $platformSurplusNum = self::platformSurplusNum($store_id, (string)$value['value']);
            // 组装显示
            $surplus    = isset($platformSurplusNum['surplusNum']) ? $platformSurplusNum['surplusNum'] : '';
            $aliasName  = $isAlias ? "{$value['text']} --- {$aliasText}：{$surplus}" : $value['text'];
            $data[$key] = [
                'label'     => $aliasName,
                'type'      => $value['value'],
                'value'     => $value['value'],
                'surplus'   => $surplus,
            ];
        }
        return $data;
    }

    /**
     * 获取租户剩余创建数量
     * @param int $store_id
     * @return array<array>
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    public static function surplusNum(int $store_id)
    {
        # 平台类型枚举
        $platformTypes = PlatformTypes::getData();
        $data          = [];
        foreach ($platformTypes as $value) {
            $platformSurplusNum = self::platformSurplusNum($store_id, $value['value']);
            $data[$value['value']] = $platformSurplusNum['surplusNum'];
        }
        return $data;
    }

    /**
     * 计算租户平台资产
     * @param int $store_id
     * @param string $platform
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-21
     */
    public static function platformSurplusNum(int $store_id, string $platform)
    {
        # 总数量
        $sumNum = self::storeGradeNum($store_id, $platform);
        # 已创建数量
        $createdNum = self::createdNum($store_id, $platform);
        # 总数量 - 已创建数量 = 剩余可创建
        $surplusNum =  $sumNum - $createdNum;

        #返回----总数量--已创建--剩余
        return [
            'sumNum'        => $sumNum,
            'createdNum'    => $createdNum,
            'surplusNum'    => $surplusNum,
        ];
    }

    /**
     * 获取租户等级总创建数量
     */
    public static function storeGradeNum(int $store_id, string $platform)
    {
        $where = [
            'id'    => $store_id
        ];
        $model = Store::where($where)->find();
        if (!$model) {
            throw new Exception('该租户不存在');
        }
        $data  = $model->toArray();
        if (!isset($data[$platform])) {
            throw new Exception('获取租户等级总创建数量枚举错误');
        }
        // 获取已创建数量
        $createdNum = self::createdNum($store_id, $platform);
        return (int)$data[$platform] + $createdNum;
    }

    /**
     * 获取租户已创建数量
     * @param int $store_id
     * @return int
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    public static function createdNum(int $store_id, string $platform)
    {
        $where = [
            'store_id'      => $store_id,
            'platform_type' => $platform
        ];
        return StorePlatform::where($where)->count();
    }
}
