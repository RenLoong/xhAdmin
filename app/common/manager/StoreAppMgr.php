<?php
namespace app\common\manager;

use app\common\model\StoreApp as modelStoreApp;
use app\common\model\SystemConfig;
use app\common\model\SystemConfigGroup;
use app\common\model\SystemUpload;
use app\common\model\SystemUploadCate;
use Exception;
use think\facade\Db;

class StoreAppMgr
{
    /**
     * 获取应用数据模型
     * @param array $where
     * @return \app\common\model\StoreApp
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function model(array $where): modelStoreApp
    {
        $model = modelStoreApp::where($where)->find();
        if (!$model) {
            throw new Exception('找不到该应用数据');
        }
        return $model;
    }

    /**
     * 获取应用数据
     * @param array $where
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function detail(array $where):array
    {
        return self::model($where)->toArray();
    }
    
    /**
     * 删除项目
     * @param array $where
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function del(array $where)
    {
        if (empty($where['id'])) {
            throw new Exception('参数错误--[项目ID]');
        }
        if (empty($where['store_id'])) {
            throw new Exception('参数错误--[租户ID]');
        }
        Db::startTrans();
        try {
            # 获取项目
            $model = self::model($where);
            # 删除项目配置
            SystemConfig::where($where)->delete();
            SystemConfigGroup::where($where)->delete();
            # 删除项目附件
            SystemUpload::where($where)->delete();
            SystemUploadCate::where($where)->delete();
            # 删除项目
            if (!$model->delete()) {
                throw new Exception('删除失败');
            }
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            throw $e;
        }
    }
}