<?php

namespace app\manager;

use app\enum\PlatformTypes;
use app\model\Store;
use app\model\StorePlatform;
use app\model\StorePlatformConfig;
use app\model\SystemUpload;
use Exception;
use think\facade\Db;

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

    /**
     * 删除平台
     * @param int $platform_id
     * @throws \Exception
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function deletePlatform(int $platform_id)
    {
        Db::startTrans();
        try {
            # 查询平台
            $where = [
                'id' => $platform_id
            ];
            $model = new StorePlatform;
            $model = $model->withTrashed()->where($where)->find();
            if (!$model) {
                throw new Exception('该平台不存在');
            }
            # 恢复租户数量（真实删除有效）
            if ($model->delete_time) {
                # 取平台类型为字段
                $field = $model->platform_type;
                $where      = [
                    'id'    => $model->store_id
                ];
                $storeModel = Store::where($where)->find();
                if (!$storeModel) {
                    throw new Exception('该平台租户不存在');
                }
                $storeModel->$field = $storeModel->$field + 1;
                if (!$storeModel->save()) {
                    throw new Exception("恢复租户平台数量失败");
                }
            }
            # 是否真实删除平台
            if ($model->delete_time) {
                $model = $model->force();
            }
            # 删除平台
            if (!$model->delete()) {
                throw new Exception('删除平台失败');
            }
            # 通用条件
            $where = [
                'platform_id' => $platform_id
            ];
            # 删除平台附件
            $uploadify = SystemUpload::where($where)->select();
            foreach ($uploadify as $uploadModel) {
                # 是否真实删除附件
                if ($model->delete_time) {
                    $uploadModel = $uploadModel->force();
                }
                if (!$uploadModel->delete()) {
                    throw new Exception('删除平台附件失败');
                }
            }
            # 删除平台配置
            $platformConfig = StorePlatformConfig::where($where)->select();
            foreach ($platformConfig as $configModel) {
                # 是否真实删除配置
                if ($model->delete_time) {
                    $configModel = $configModel->force();
                }
                if (!$configModel->delete()) {
                    throw new Exception('删除平台配置失败');
                }
            }
            Db::commit();
            return true;
        } catch (\Throwable $e) {
            Db::rollback();
            throw $e;
        }
    }
}
