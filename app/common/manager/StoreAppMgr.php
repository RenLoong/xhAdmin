<?php
namespace app\common\manager;

use app\common\model\Store;
use app\common\model\StoreApp as modelStoreApp;
use app\common\model\SystemConfig;
use app\common\model\SystemConfigGroup;
use app\common\model\SystemUpload;
use app\common\model\SystemUploadCate;
use app\common\service\SystemInfoService;
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
     * 获取已购买应用并已安装的应用
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getBuyInstallApp()
    {
        $installed  = PluginMgr::getLocalPlugins();
        $systemInfo = SystemInfoService::info();
        $query      = [
            'active'            => '20',
            'limit'             => 1000,
            'is_buy'            => '20',
            'plugins'           => $installed,
            'saas_version'      => $systemInfo['system_version']
        ];
        $res        = \YcOpen\CloudService\Request::Plugin()->list($query)->v2()->response();
        $data       = $res->data;
        return $data;
    }

    /**
     * 获取已购买并已安装的应用选项列表
     * @return array<array>
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getBuyInstOptions()
    {
        # 获取已购买并已安装的应用
        $list = self::getBuyInstallApp();
        $data = [];
        foreach ($list as $key => $value) {
            $data[$key] = [
                'label'         => $value['title'],
                'value'         => $value['name']
            ];
        }
        return $data;
    }

    /**
     * 获取已授权的租户应用
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getAuthApp(int $store_id)
    {
        # 获取已购买并已安装的应用
        $list = self::getBuyInstallApp();
        # 获取租户已授权的应用
        $pluginsNames = Store::where('id', $store_id)->value('plugins_name');
        if (empty($pluginsNames)) {
            $pluginsNames = [];
        }
        $data = [];
        foreach ($list as $item) {
            if (!in_array($item['name'], $pluginsNames)) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

    /**
     * 获取已授权的租户应用选项列表
     * @param int $store_id
     * @param string $platform
     * @return array<array>
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getAuthAppOptions(int $store_id,string $platform)
    {
        # 获取已授权的租户应用
        $list = self::getAuthApp($store_id);
        $data = [];
        foreach ($list as $key => $value) {
            if (!in_array($platform, $value['platform'])) {
                continue;
            }
            $data[$key] = [
                'label'         => $value['title'],
                'value'         => $value['name']
            ];
        }
        return $data;
    }

    /**
     * 获取租户已创建应用的数量
     * @param int $store_id
     * @param string $platform
     * @return int
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getStoreCreatedPlatform(int $store_id,string $platform)
    {
        # 获取已创建应用的数量
        $where = [
            'store_id'      => $store_id,
            'platform'      => $platform
        ];
        $storeAppNum = modelStoreApp::where($where)->count();
        return $storeAppNum;
    }

    /**
     * 获取应用数据
     * @param array $where
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function detail(array $where): array
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
            throw new Exception('参数错误--[代理ID]');
        }
        $whereOther['saas_appid'] = $where['id'];
        $whereOther['store_id']   = $where['store_id'];
        Db::startTrans();
        try {
            # 获取项目
            $model = self::model($where);
            # 删除项目配置
            SystemConfig::where($whereOther)->delete();
            # 删除项目附件
            SystemUpload::where($whereOther)->delete();
            SystemUploadCate::where($whereOther)->delete();
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