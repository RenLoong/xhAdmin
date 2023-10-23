<?php
namespace app\common\trait\config;

use app\common\manager\SettingsMgr;
use app\common\model\SystemConfig;
use app\common\service\UploadService;
use Exception;
use app\common\utils\Json;
use support\Request;

/**
 * 普通配置
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
trait Config
{
    # 使用JSON工具类
    use Json;
    # 使用表单工具类
    use FormUtil;

    /**
     * 请求对象
     * @var Request
     */
    protected $request = null;

    /**
     * 应用ID（null则获取系统配置）
     * @var string|null
     */
    protected $saas_appid = null;

    /**
     * 获取配置项
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function settings(Request $request)
    {
        # 不存在则为系统配置
        $isSystem = (int) $this->saas_appid;
        # 获取配置项分组
        $group = $request->get('group', '');
        # 获取配置模板数据
        if (!$isSystem && empty($request->plugin)) {
            # 系统配置项
            $settings = config("settings.{$group}");
        } else {
            # 获取应用插件配置项
            $plugin   = $request->plugin;
            $settings = config("plugin.{$plugin}.settings.{$group}");
        }
        if (empty($settings)) {
            throw new Exception('找不到标准的配置文件');
        }
        if ($request->isPut()) {
            $post  = $request->post();
            $where = [
                'group' => $group,
                'saas_appid' => $this->saas_appid,
            ];
            $model = SystemConfig::where($where)->find();
            if (!$model) {
                $model             = new SystemConfig;
                $model->group      = $group;
                $model->saas_appid = $this->saas_appid;
            }
            foreach ($settings as $value) {
                # 处理附件库数据
                if ($value['component'] === 'uploadify') {
                    $post[$value['name']] = UploadService::path($post[$value['name']]);
                }
            }
            $model->value = $post;
            if (!$model->save()) {
                throw new Exception('保存失败');
            }
            return $this->success('保存成功');
        } else {
            # 获取表单规则
            $builder = $this->getFormView($settings);
            # 查询配置数据
            $where    = [
                'group' => $group,
                'saas_appid' => $this->saas_appid
            ];
            $formData = SettingsMgr::getConfig($where, []);
            foreach ($settings as $value) {
                # 处理附件库数据
                if (isset($formData[$value['name']]) && $value['component'] === 'uploadify') {
                    $formData[$value['name']] = UploadService::url($formData[$value['name']]);
                }
            }
            $views = $builder->setMethod('PUT')->setFormData($formData)->create();
            return Json::successRes($views);
        }
    }

    /**
     * 获取无选中Tabs选项卡
     * @param \support\Request $request
     * @throws \Exception
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function config(Request $request)
    {
        $plugin = $request->plugin;
        if (empty($plugin) && empty($this->saas_appid)) {
            $config = config('settings');
        } else {
            $config = config('plugin.' . $plugin . '.settings');
        }
        if (empty($config)) {
            throw new Exception('找不到Tabs配置文件');
        }
        # 保存数据
        if ($request->isPut()) {
            $post   = $request->post();
            if (empty($post['active'])) {
                throw new Exception('请选择选项卡');
            }
            unset($post['active']);
            # 查询数据
            foreach ($config as $item) {
                $where = [
                    'group' => $item['name'],
                    'saas_appid' => $this->saas_appid,
                ];
                $model = SystemConfig::where($where)->find();
                if (!$model) {
                    $model             = new SystemConfig;
                    $model->group      = $item['name'];
                    $model->saas_appid = $this->saas_appid;
                }
                # 处理数据
                $configValue = [];
                foreach ($item['children'] as $children) {
                    $configValue[$children['name']] = $post[$children['name']];
                    # 处理附件库数据
                    if ($children['component'] === 'uploadify') {
                        $configValue[$children['name']] = UploadService::path($post[$children['name']]);
                    }
                }
                # 设置保存数据
                $model->value = $configValue;
                # 保存数据
                if (!$model->save()) {
                    throw new Exception("保存分组[{$item['title']}]失败");
                }
            }
            # 保存成功
            return $this->success('保存成功');
        }
        # 设置默认选中
        $first  = current($config);
        $active = empty($first['name']) ? '' : $first['name'];
        # 获取数据
        $where      = [
            'saas_appid' => $this->saas_appid,
        ];
        $configData = getHpConfig('', $this->saas_appid);
        # 处理数据
        $formData = empty($configData) ? [] : $configData;
        foreach ($config as $item) {
            foreach ($item['children'] as $value) {
                # 处理附件库数据
                if (isset($formData[$value['name']]) && $value['component'] === 'uploadify') {
                    $formData[$value['name']] = UploadService::url($formData[$value['name']]);
                }
            }
        }
        # 获取渲染视图
        $view = $this->getTabsView($active, $config)
            ->setMethod('PUT')
            ->setFormData($formData)
            ->create();
        return $this->successRes($view);
    }
}