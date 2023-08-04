<?php
namespace app\common\manager;

use app\common\model\StoreApp;
use app\common\model\SystemConfig;
use Exception;
use support\Request;

/**
 * 应用配置管理
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class StoreAppConfigMgr
{
    /**
     * 请求对象
     * @var Request
     */
    protected $request = null;

    /**
     * 模型对象
     * @var StoreApp
     */
    protected $model = null;

    /**
     * 额外扩展参数--组件类型
     * @var array
     */
    protected $componentType = [
        'checkbox',
        'radio',
        'select'
    ];

    /**
     * 构造函数
     * @param \support\Request $request
     * @param \app\common\model\SystemConfig $model
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function __construct(Request $request, StoreApp $model)
    {
        $this->request = $request;
        $this->model   = $model;
    }

    /**
     * 获取配置项数据
     * @param array $where
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function getConfig(array $where)
    {
        return SystemConfig::where($where)->column('value','name');
    }

    /**
     * 获取系统配置列表
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function list()
    {
        $settingPath = base_path("/plugin/{$this->model['name']}/config/settings.php");
        if (!file_exists($settingPath)) {
            throw new Exception('应用配置文件不存在');
        }
        $settings = config("plugin.{$this->model['name']}.settings");
        $store_id = $this->model['store_id'];
        $saas_appid = $this->model['id'];
        # 配置管理类
        $configMgr = new SystemConfigMgr($this->request);
        # 获取配置项
        $data = $configMgr->getConfigView($settings,$store_id,$saas_appid);
        # 返回视图
        return JsonMgr::successRes($data);
    }

    /**
     * 保存项目系统配置
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function saveData()
    {
        # 提交配置项数据
        $post = request()->post();
        # 配置表单规则
        $settings = config("plugin.{$this->model['name']}.settings");
        $configMgr = new SystemConfigMgr($this->request);
        # 保存数据
        $configMgr->saveData($settings,$post,$this->model['store_id'],$this->model['id']);
        # 返回数据
        return JsonMgr::success('保存成功');
    }
}