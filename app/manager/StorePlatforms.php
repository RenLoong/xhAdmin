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
    public static function getSelectOptions(int $store_id,bool $isAlias = false,string $aliasText = '可创建')
    {
        // 剩余可创建
        $surplusNum = self::surplusNum($store_id);
        # 平台类型枚举
        $platformTypes = PlatformTypes::getData();

        $data = [];
        foreach ($platformTypes as $key => $value) {
            $surplus    = isset($surplusNum[$value['value']]) ? $surplusNum[$value['value']] : '';
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
    public static function platformSurplusNum(int $store_id,string $platform)
    {
        # 总数量
        $sumNum = self::storeGradeNum($store_id);
        # 已创建数量
        $createdNum = self::createdNum($store_id);
        # 总数量 - 已创建数量 = 剩余可创建
        $surplusNum =  $sumNum[$platform] - $createdNum[$platform];

        #返回----总数量--已创建--剩余
        return [
            'sumNum'        => $sumNum[$platform],
            'createdNum'    => $createdNum[$platform],
            'surplusNum'    => $surplusNum,
        ];
    }

    /**
     * 获取租户等级总创建数量
     * @param int $store_id
     * @throws Exception
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    public static function storeGradeNum(int $store_id)
    {
        $where = [
            'id'    => $store_id
        ];
        $model= Store::with(['grade'])->where($where)->find();
        if (!$model) {
            throw new Exception('该租户不存在');
        }
        $data  = $model->toArray();
        $grade = $data['grade'];
        $platformTypes = PlatformTypes::getData();

        $data = [];
        foreach ($platformTypes as $value) {
            if (!isset($grade["platform_{$value['value']}"])) {
                throw new Exception('获取租户等级总创建数量枚举错误');
            }
            $data[$value['value']] = $grade["platform_{$value['value']}"];
        }
        return $data;
    }

    /**
     * 获取租户已创建数量
     * @param int $store_id
     * @return array<int>
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    public static function createdNum(int $store_id)
    {
        $platformTypes = PlatformTypes::getData();

        $data = [];
        foreach ($platformTypes as $value) {
            $where = [
                'store_id'      => $store_id
            ];
            $count = StorePlatform::where($where)->count();
            $data[$value['value']] = $count;
        }
        return $data;
    }
}