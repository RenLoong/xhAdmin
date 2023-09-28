<?php
namespace app\common\trait;

use app\common\enum\ConfigGroupCol;
use app\common\manager\StoreAppMgr;
use app\common\model\SystemConfig;
use app\common\enum\CustomComponent;
use app\common\builder\FormBuilder;
use app\common\service\UploadService;
use Exception;
use app\common\utils\Json;
use support\Request;

/**
 * 系统设置管理
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
trait SettingsTrait
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
     * @var string|null
     */
    protected $saas_appid = null;

    /**
     * 自定义组件
     * @var array
     */
    protected $customComponent = [
        'uploadify',
        'wangEditor',
        'remote',
        'info',
    ];

    /**
     * 扩展组件类型
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $extraOptions = ['checkbox', 'radio', 'select'];

    /**
     * 获取配置项
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function config(Request $request)
    {
        $plugin = $request->plugin;
        if ($plugin && !$this->saas_appid) {
            throw new Exception('请在控制器内设置saas_appid');
        }
        if ($plugin && $this->saas_appid) {
            $config = config('plugin.' . $plugin . '.settings');
        } else {
            $config = config('settings');
        }
        $storeApp = StoreAppMgr::detail(['id'=> $this->saas_appid]);
        if ($request->isPut()) {
            $post = $request->post();
            foreach ($config as $value) {
                # 查询数据
                $where = [
                    'group'             => $value['name'],
                    'store_id'          => $storeApp['store_id'],
                    'saas_appid'        => $storeApp['id'],
                ];
                $model = SystemConfig::where($where)->find();
                if (!$model) {
                    $model                  = new SystemConfig;
                    $model->group           = $value['name'];
                    $model->store_id        = $storeApp['store_id'];
                    $model->saas_appid      = $storeApp['id'];
                }
                $saveValue = [];
                # 取出子项
                foreach ($value['children'] as $item) {
                    if (isset($post[$item['name']])) {
                        $dataValue = empty($post[$item['name']]) ? '' : $post[$item['name']];
                        # 验证是否附件库
                        if ($dataValue && $item['component'] === 'uploadify') {
                            $dataValue = UploadService::path($dataValue);
                        }
                        $saveValue[$item['name']] = $dataValue;
                    }
                }
                $model->value = $saveValue;
                # 保存数据
                if (!$model->save()) {
                    throw new Exception("保存分组[{$value['name']}]失败");
                }
            }
            # 保存成功
            return $this->success('保存成功');
        }
        # 获取渲染视图
        $data = $this->getConfigView($config);
        return $this->successRes($data);
    }

    /**
     * 获取Tabs视图
     * @param array $data
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function getConfigView(array $data)
    {
        # 设置默认选中
        $first  = current($data);
        $actvie = empty($first['name']) ? '' : $first['name'];
        # 实例表单构建器
        $builder = new FormBuilder;
        $builder = $builder->setMethod('PUT');
        $builder = $builder->initTabs($actvie, [
            'props' => [
                // 选项卡样式
                'type'          => 'line',
                'tabPosition'   => 'top',
            ],
        ]);
        foreach ($data as $value) {
            if (empty($value['name'])) {
                throw new Exception('分组标识不能为空');
            }
            if (empty($value['title'])) {
                throw new Exception('分组名称不能为空');
            }
            if (empty($value['children'])) {
                throw new Exception('配置项数据错误');
            }
            if (empty($value['layout_col'])) {
                $value['layout_col'] = 24;
            }
            $configs = $this->getConfigData($value['children'], $value, $this->saas_appid);
            $builder = $builder->addTab(
                $value['name'],
                $value['title'],
                $configs
            );
        }
        $data = $builder->endTabs()->create();
        # 获取配置数据
        return $data;
    }

    /**
     * 获取系统配置
     * @param array $list
     * @param array $group
     * @param mixed $saas_appid
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function getConfigData(array $list, array $group, $saas_appid): array
    {
        # 查询数据
        $where       = [
            ['group', '=', $group['name']],
            ['saas_appid', '=', $saas_appid],
        ];
        $dataValue   = SystemConfig::where($where)->value('value') ?? [];
        $config      = [];
        $builder     = new FormBuilder;
        foreach ($list as $value) {
            # 数据验证
            hpValidate(\app\admin\validate\SystemConfig::class, $value);
            # 设置数据
            $configValue = empty($dataValue[$value['name']]) ? '' : $dataValue[$value['name']];
            # 验证扩展组件
            if (in_array($value['component'], $this->extraOptions)) {
                if (empty($value['extra'])) {
                    throw new Exception("[{$value['name']}] - 扩展数据不能为空");
                }
                if (empty($value['extra']['options'])) {
                    throw new Exception("[{$value['name']}] - 扩展的选项数据不能为空");
                }
            }
            # 附件库参数设置
            if ($value['component'] === 'uploadify') {
                # 重设的模型数据
                if ($configValue) {
                    $tempConfig  = array_filter(explode(',', $configValue));
                    $configValue = UploadService::urls($tempConfig);
                } else {
                    $configValue = [];
                }
            }
            # 设置组件参数
            if (in_array($value['component'], $this->customComponent)) {
                # 设置底部描述
                if (!empty($value['placeholder'])) {
                    $value['extra']['props']['prompt']['text'] = $value['placeholder'];
                }
                # 设置布局模式
                $value['extra']['props']['col'] = $group['layout_col'];
                # 自定义组件
                $builder->addComponent(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $value['extra']
                );
            } else {
                # 多选框组件
                if ($value['component'] == 'checkbox') {
                    $value['extra']['props']['options'] = $value['extra']['options'];
                    $configValue                        = [];
                }
                # 设置底部描述
                if (!empty($value['placeholder'])) {
                    $value['extra']['prompt']['text'] = $value['placeholder'];
                }
                # 设置布局模式
                $value['extra']['props']['col'] = $group['layout_col'];
                # 普通组件
                $builder->addRow(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $value['extra']
                );
            }
        }
        $config = $builder->getBuilder()->formRule();
        # 返回视图数据
        return $config;
    }

    /**
     * 获取配置项视图
     * @param \support\Request $request
     * @return mixed
     * @author John
     */
    public function settings(Request $request)
    {
        $isSystem = (int) $request->get('type', '0');
        $group    = $request->get('group', '');
        if (empty($group)) {
            throw new Exception('参数错误');
        }
        if ($isSystem) {
            $settings = config("settings.{$group}");
        } else {
            $plugin   = $request->plugin;
            $settings = config("plugin.{$plugin}.settings.{$group}");
        }
        if (empty($settings)) {
            throw new Exception('找不到标准的配置文件');
        }
        if ($request->isPut()) {
            $group = $request->get('group', '');
            $data  = $request->post();
            $this->save($group, $data);
            return Json::success('保存成功');
        }
        $data = $this->parseViews($group,$settings);
        return Json::successRes($data);
    }

    /**
     * 解析视图数据
     * @param string $group
     * @param array $data
     * @return array
     * @author John
     */
    protected function parseViews(string $group,array $data)
    {
        $custComponent = CustomComponent::getColumn('value');
        $builder       = new FormBuilder;
        $builder->setMethod('PUT');
        foreach ($data as $value) {
            $extra = $value['extra'] ?? [];
            if (in_array($value['component'], $custComponent)) {
                $builder->addComponent(
                    $value['field'],
                    $value['component'],
                    $value['title'],
                    $value['value'],
                    $extra
                );
            } else {
                $builder->addRow(
                    $value['field'],
                    $value['component'],
                    $value['title'],
                    $value['value'],
                    $extra
                );
            }
        }
        $where    = [
            'group'      => $group,
        ];
        $formData = SystemConfig::where($where)->value('value');
        $formData = $formData ?? [];
        $views = $builder->setFormData($formData)->create();
        return $views;
    }

    /**
     * 保存数据 TODO：待完善
     * @param array $data
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function save(string $group, array $data)
    {
        $configValue = [];
        foreach ($data as $field => $value) {
            if (strrpos($field, '.') !== false) {
                # 解析层级键值
                $configValue  = $this->configValue($field, $value);
            } else {
                $configValue[$field] = $value;
            }
        }
        $where = [
            'group'         => $group,
            'saas_appid'    => $this->saas_appid,
        ];
        $model = SystemConfig::where($where)->find();
        if (!$model) {
            $model        = new SystemConfig;
            $model->group = $group;
        }
        $model->value = $configValue;
        if (!$model->save()) {
            throw new Exception('保存失败');
        }
    }

    /**
     * 解析配置数据
     * @param string $field
     * @param mixed $value
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function configValue(string $field, $value)
    {
        $data = [];
        return $data;
    }
}