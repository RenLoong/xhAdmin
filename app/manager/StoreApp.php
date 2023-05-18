<?php
namespace app\manager;
use app\model\StoreApp as modelStoreApp;
use Exception;

class StoreApp
{
    /**
     * 获取应用数据模型
     * @param int $appid
     * @throws Exception
     * @return modelStoreApp
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-18
     */
    public static function model(int $appid): modelStoreApp
    {
        $where = [
            'id'        => $appid
        ];
        $model = modelStoreApp::where($where)->find();
        if (!$model) {
            throw new Exception('找不到该应用数据');
        }
        return $model;
    }

    /**
     * 获取应用数据
     * @param int $appid
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-18
     */
    public static function detail(int $appid):array
    {
        return self::model($appid)->toArray();
    }
}