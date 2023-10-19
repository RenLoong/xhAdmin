<?php
namespace app\common\trait;

use app\admin\utils\UploadUtil;
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
trait UploadConfigTrait
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
     * 附件库配置
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function uploadify(Request $request)
    {
        $prefixs = UploadUtil::getPrefixs();
        if ($request->isPut()) {
            $drive = $request->post('system_uploadify');
            $post  = $request->post();
            unset($post['system_uploadify']);
            $children = [];
            foreach ($post as $field => $value) {
                list($prefixName)              = explode('_', $field);
                $field                         = str_replace($prefixs, '', $field);
                $children[$prefixName][$field] = $value;
            }
            $data = [
                'upload_drive' => $drive,
                'children' => $children
            ];
            $this->save('upload', $data);
            return Json::success('附件库保存成功');
        }
        $uploadify  = getHpConfig('', null, 'upload');
        $drive      = isset($uploadify['upload_drive']) ? $uploadify['upload_drive'] : 'local';
        
        # 获取模板数据
        $setingsTpl = config('settings.upload');
        foreach ($setingsTpl as $key => $value) {
            foreach ($value['children'] as $childKey => $children) {
                $setingsTpl[$key]['children'][$childKey]['name']      = $children['field'];
                $setingsTpl[$key]['children'][$childKey]['component'] = $children['type'];
                $setingsTpl[$key]['children'][$childKey]['extra']     = json_decode(json_encode($children['props']), true);
            }
        }        
        # 实例表单构建器
        $builder = new FormBuilder;
        $builder = $builder->setMethod('PUT');
        $builder = $builder->initTabs('system_uploadify', $drive, [
            'props' => [
                // 选项卡样式
                'type' => 'line',
                'tabPosition' => 'top',
            ],
        ]);
        foreach ($setingsTpl as $value) {
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
            $configs = $this->uploadifyValue($value['children'], $value);
            $builder = $builder->addTab(
                $value['name'],
                $value['title'],
                $configs
            );
        }
        $data = $builder->endTabs()->create();
        return $this->successRes($data);
    }

    /**
     * 获取附件库配置数据
     * @param array $data
     * @param array $group
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function uploadifyValue(array $data,array $group)
    {
        # 当前分组
        $drive = request()->get('group','');
        # 查询数据
        $settings = getHpConfig('', $this->saas_appid, $drive);
        $dataValue = isset($settings['children']) ? $settings['children'] : $settings;
        $config    = [];
        $builder   = new FormBuilder;
        foreach ($data as $value) {
            # 数据验证
            hpValidate(\app\admin\validate\SystemConfig::class, $value);
            # 取字段名
            $fieldKey = str_replace($group['name'] . '_', '', $value['name']);
            # 设置数据
            if (!empty($dataValue[$group['name']][$fieldKey])) {
                $value['value'] = $dataValue[$group['name']][$fieldKey];
            }
            $configValue = $value['value'];
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
}