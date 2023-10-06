<?php
namespace app\common\trait;

use app\common\manager\AppletMgr;
use app\common\manager\PluginMgr;
use app\common\manager\StoreAppConfigMgr;
use app\common\manager\StoreAppMgr;
use app\common\utils\Json;
use Exception;
use support\Request;

/**
 * 小程序接管类
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
trait AppletTrait
{
    // 使用JSON工具类
    use Json;

    /**
     * 请求对象
     * @var Request
     */
    protected $request = null;

    /**
     * 应用ID（null则获取系统配置）
     * @var int|null
     */
    protected $saas_appid = null;

    /**
     * 显示小程序配置/上传页面
     * @param \support\Request $request
     * @return \think\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function index(Request $request)
    {
        return renderCustomView('remove/app/applet');
    }

    /**
     * 小程序配置
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function config(Request $request)
    {
        $model = StoreAppMgr::model(['id'=> $this->saas_appid]);
        $pluginPath = base_path("plugin/{$model->name}");
        if (!is_dir($pluginPath)) {
            throw new Exception('该项目绑定应用已卸载');
        }
        $settingPath = "{$pluginPath}/config/settings.php";
        if (!file_exists($settingPath)) {
            throw new Exception('该应用插件没有系统配置文件');
        }
        # 检测应用对SAAS版本支持
        if (!PluginMgr::checkPluginSaasVersion($model['name'])) {
            throw new Exception('请先更新应用');
        }
        $systemConfig = new StoreAppConfigMgr($request, $model);
        $methodFun = 'list';
        if ($request->method() === 'PUT') {
            $methodFun = 'saveData';
        }
        return call_user_func([$systemConfig, $methodFun]);
    }

    /**
     * 小程序发布
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function publish(Request $request)
    {
        $model = StoreAppMgr::model(['id'=> $this->saas_appid]);
        # 检测应用对SAAS版本支持
        if (!PluginMgr::checkPluginSaasVersion($model['name'])) {
            throw new Exception('请先更新应用');
        }
        $methodFun = 'detail';
        switch ($request->method()) {
            # 小程序发布
            case 'POST':
                $methodFun = 'publish';
                break;
            # 小程序配置
            case 'PUT':
                $methodFun = 'config';
                break;
        }
        $class = new AppletMgr($request, $model);
        if (!method_exists($class, $methodFun)) {
            throw new Exception("未实现项目方法 -- {$methodFun}");
        }
        return call_user_func([$class, $methodFun]);
    }
}