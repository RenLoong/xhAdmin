<?php
namespace app\common\manager;

use app\common\builder\FormBuilder;
use app\common\enum\ConfigGroupCol;
use app\common\enum\FormType;
use app\common\exception\RedirectException;
use app\common\model\StoreApp;
use app\common\model\SystemConfig;
use app\common\model\SystemConfigGroup;
use app\common\service\UploadService;
use Exception;
use support\Request;

/**
 * 小程序管理类
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class SystemConfigMgr
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
     * 创建项目配置项
     * @param array $data
     * @param bool $isKeyword
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function ActionSettings(array $data,bool $isKeyword = true)
    {
        $group = empty($data['group']) ? [] : $data['group'];
        $configs = empty($data['configs']) ? [] : $data['configs'];
        if (empty($group)) {
            throw new Exception('分组数据格式错误');
        }
        if (empty($configs)) {
            throw new Exception('配置项数据格式错误');
        }
        # 验证配置项分组
        $cateNames = array_column($group,'name');
        foreach ($group as $value){
            if (empty($value['title'])) {
                throw new Exception('分组标题不能为空');
            }
            if (empty($value['name'])) {
                throw new Exception('分组标识不能为空');
            }
            if (!isset($value['sort'])) {
                throw new Exception('分组排序不能为空');
            }
            if (!is_numeric($value['sort'])) {
                throw new Exception('分组排序必须为数字');
            }
            if (empty($value['layout_col'])) {
                throw new Exception('分组布局不能为空');
            }
            if (!in_array($value['layout_col'], array_keys(ConfigGroupCol::dictOptions()))) {
                throw new Exception('分组布局不正确');
            }
            # 验证是否存在分组
            $where = [
                'name'          => $value['name'],
                'store_id'      => $this->model['store_id'],
                'saas_appid'    => $this->model['id'],
            ];
            $model = SystemConfigGroup::where($where)->find();
            if ($model) {
                $model->save($value);
                continue;
            }
            # 保存分组
            $value['store_id'] = $this->model['store_id'];
            $value['saas_appid'] = $this->model['id'];
            $model = new SystemConfigGroup;
            $model->save($value);
        }
        # 验证配置项
        $formDict = array_keys(FormType::dictOptions());
        $configNames = [
            'web_name',
            'web_url',
        ];
        foreach ($configs as $value) {
            if (empty($value['group_name'])) {
                throw new Exception('配置项 - [配置项分组标识不能为空]');
            }
            if (empty($value['title'])) {
                throw new Exception('配置项 - [标题不能为空]');
            }
            if (empty($value['name'])) {
                throw new Exception('配置项 - [配置项标识不能为空]');
            }
            if (empty($value['component'])) {
                throw new Exception('配置项 - [配置项组件不能为空]');
            }
            if (!in_array($value['component'], $formDict)) {
                throw new Exception('配置项 - [配置项组件不正确]');
            }
            if (!in_array($value['group_name'], $cateNames)) {
                throw new Exception('配置项 - [配置项关联分组不正确]');
            }
            if (in_array($value['name'], $configNames) && $isKeyword) {
                throw new Exception("配置项名称不能使用 - [{$value['name']}]");
            }
            if (empty($value['sort'])) {
                throw new Exception('配置项 - [排序不能为空]');
            }
            if (!is_numeric($value['sort'])) {
                throw new Exception('配置项 - [排序必须为数字]');
            }
            if (empty($value['show'])) {
                throw new Exception('配置项 - [隐藏/显示 - 不正确]');
            }
            if (empty($value['placeholder'])) {
                throw new Exception('配置项 - [placeholder不能为空]');
            }
            if (!isset($value['value'])) {
                throw new Exception('配置项 - [缺少默认数据字段]');
            }
            # 验证是否存在配置项
            $where = [
                'name' => $value['name'],
                'store_id' => $this->model['store_id'],
                'saas_appid' => $this->model['id'],
            ];
            $model = SystemConfig::where($where)->find();
            if ($model) {
                $model->save($value);
                continue;
            }
            # 查询关联分组
            $where = [
                'name' => $value['group_name'],
                'store_id' => $this->model['store_id'],
                'saas_appid' => $this->model['id'],
            ];
            $cateModel = SystemConfigGroup::where($where)->find();
            if (!$cateModel) {
                throw new Exception('配置项 - [关联分组不存在]');
            }
            # 保存配置项
            unset($value['cate_name']);
            $value['group_name']    = $cateModel['name'];
            $value['store_id']      = $this->model['store_id'];
            $value['saas_appid']    = $this->model['id'];
            $model = new SystemConfig;
            $model->save($value);
        }
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
        # 获取配置项
        $configs = $this->getConfigs();
        # 设置默认选中
        $first  = current($configs);
        $actvie = empty($first['name']) ? '' : $first['name'];
        # 实例表单构建器
        $builder = new FormBuilder;
        $builder = $builder->setMethod('PUT');
        $builder = $builder->initTabs($actvie, [
            'props' => [
                // 选项卡样式
                'type' => 'line'
            ],
        ]);
        foreach ($configs as $value) {
            $builder = $builder->addTab(
                $value['name'],
                $value['title'],
                $value['children']
            );
        }
        $data = $builder->endTabs()->create();
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
        $post = request()->post();
        foreach ($post as $name => $value) {
            $where = [
                'name' => $name
            ];
            $model = SystemConfig::where($where)->find();
            if (!$model) {
                throw new Exception('项目配置数据错误');
            }
            // 更新数据
            $model->value = $value;
            if (is_array($value) && $model['component'] == 'uploadify') {
                $files = [];
                foreach ($value as $k => $v) {
                    $files[$k] = UploadService::path($v);
                }
                $uploadPath   = implode(',', $files);
                $model->value = $uploadPath;
            }
            if ($model->save() === false) {
                throw new Exception('保存项目数据失败');
            }
        }
        return JsonMgr::success('保存成功');
    }

    /**
     * 获取配置项数据
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function getConfigs()
    {

        $appModel = $this->model;
        $settings = config("plugin.{$appModel['name']}.settings", []);
        if (empty($settings)) {
            throw new RedirectException('配置项文件数据错误', '/#/Index/index');
        }
        // $where      = [
        //     'store_id'      => $storeModel['store_id'],
        //     'saas_appid'    => $storeModel['id'],
        //     'show'          => '20'
        // ];
        // $category   = SystemConfigGroup::where($where)
        //     ->order('sort', 'asc')
        //     ->order('id', 'asc')
        //     ->select()
        //     ->toArray();
        // if (empty($category)) {
        //     throw new RedirectException('配置项分组错误', '/#/Index/index');
        // }
        // $list = [];
        // foreach ($category as $key => $value) {
        //     $list[$key]['name']     = $value['name'];
        //     $list[$key]['title']    = $value['title'];
        //     $list[$key]['children'] = $this->getChildren($value);
        // }
        $list = [];
        return $list;
    }

    /**
     * 获取配置项子项
     * @param array $data
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function getChildren(array $data)
    {
        $model   = $this->model;
        $where   = [
            'group_name'    => $data['name'],
            'saas_appid'    => $model['id'],
            'store_id'      => $model['store_id'],
            'show'          => '20'
        ];
        $list    = SystemConfig::where($where)
            ->select()
            ->toArray();
        $config  = [];
        $builder = new FormBuilder;
        foreach ($list as $value) {
            // 设置数据
            $configValue = $value['value'];
            if ($value['value'] == 'checkbox') {
                $configValue = [$value['value']];
            }
            $options = [];
            // 设置扩展数据
            $layoutCol = ConfigGroupCol::getDictValues((string)$data['layout_col']);
            $extra = [
                'info' => $value['placeholder'],
                'col' => empty($layoutCol['col']) ? 24 : $layoutCol['col'],
            ];
            if ($value['extra'] && in_array($value['component'], $this->componentType)) {
                $extras = explode('|', $value['extra']);
                foreach ($extras as $key2 => $value2) {
                    list($optionValue, $label) = explode(',', $value2);
                    $options[$key2]['label']   = $label;
                    $options[$key2]['value']   = $optionValue;
                }
                $extra['options'] = $options;
            }
            if ($value['component'] == 'uploadify') {
                // 重设的模型数据
                if ($configValue) {
                    $tempConfig  = array_filter(explode(',', $configValue));
                    $configValue = UploadService::urls($tempConfig);
                } else {
                    $configValue = [];
                }
                // 附件库
                $builder->addComponent(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $extra
                );
            } else {
                // 普通组件
                $builder->addRow(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $extra
                );
            }
        }
        $config = $builder->getBuilder()->formRule();
        return $config;
    }
}