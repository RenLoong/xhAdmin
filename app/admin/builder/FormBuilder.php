<?php

namespace app\admin\builder;

use FormBuilder\Driver\CustomComponent;
use FormBuilder\Factory\Elm;
use FormBuilder\Form;

/**
 * @title 表单构造器
 * @desc 用于表单UI快速生成
 * @author 楚羽幽 <admin@hangpu.net>
 */
class FormBuilder extends Form
{
    /**
     * 表单对象
     * @var Form
     */
    private $builder;

    // 选项卡对象
    private $tabBuilder;
    // 表单数据规则
    private $data;
    // 请求对象
    private $request;

    /**
     * 构造函数
     *
     * @param string $url
     * @param array $rule
     * @param array $config
     */
    public function __construct()
    {
        $this->request = request();
        $url = $this->request->uri();
        $rule = [];
        $config = [];
        $this->builder = Form::elm($url, $rule, $config);
    }

    /**
     * 表单配置
     *
     * @param string $name
     * @param array $value
     * @return FormBuilder
     */
    public function setConf(string $name, array $value): FormBuilder
    {
        $config = $this->builder->formConfig();
        if (count($value) <= 1) {
            $config[$name] = array_merge($config[$name], $value);
        } else {
            $config[$name] = $value;
        }
        $this->builder->setConfig($config);
        return $this;
    }

    /**
     * 添加表单行元素
     *
     * @param string $field 组件字段
     * @param string $type 组件类型
     * @param string $title 组件标题
     * @param string $value 组件默认值
     * @param array $extra 扩展数据
     * @return FormBuilder
     */
    public function addRow(string $field, string $type, string $title, $value = '', array $extra = []): FormBuilder
    {
        // 创建组件
        $component = Elm::$type($field, $title, $value);
        foreach ($extra as $componentType => $componentValue) {
            $component->$componentType($componentValue);
        }
        $this->builder->append($component);
        return $this;
    }

    /**
     * 添加自定义组件
     *
     * @param string $field 组件字段
     * @param string $type 组件类型
     * @param string $title 组件标题
     * @param string $value 组件默认值
     * @param array $extra 扩展数据
     * @return FormBuilder
     */
    public function addComponent(string $field, string $type, string $title, $value = '', array $extra = []): FormBuilder
    {
        // 创建自定义组件
        $component = new CustomComponent($type);
        // 设置字段，默认数据等
        $component->field($field)->title($title)->value($value);
        // 设置组件属性
        foreach ($extra as $componentType => $componentValue) {
            $component->$componentType($componentValue);
        }
        $this->builder->append($component);
        return $this;
    }

    /**
     * 创建表单分割线
     *
     * @param string $title
     * @param array $extra
     * @return FormBuilder
     */
    public function addDivider(string $title, array $extra = []): FormBuilder
    {
        // 创建自定义组件
        $component = new CustomComponent('el-divider');
        // 设置属性
        $component
            ->appendChild($title)
            ->appendRule('wrap', ['show' => false])
            ->appendRule('native', false)
            ->appendRule('_fc_drag_tag', 'el-divider')
            ->appendRule('_fc_drag_tag', 'el-divider')
            ->appendRule('hidden', false)
            ->appendRule('display', true);
        // 设置组件属性
        foreach ($extra as $componentType => $componentValue) {
            $component->$componentType($componentValue);
        }
        $this->builder->append($component);
        return $this;
    }

    /**
     * 初始化选项卡
     *
     * @param string $active
     * @param array $props
     * @return FormBuilder
     */
    public function initTabs(string $active, array $extra = []): FormBuilder
    {
        // 选项卡组件
        $component = new CustomComponent('el-tabs');
        // 设置默认选中
        $component->props([
            'modelValue'    => $active
        ]);
        // 设置默认样式
        $component->style([
            'width'         => '100%',
            'margin'        => '0 15px',
        ]);
        foreach ($extra as $componentType => $componentValue) {
            $component->$componentType($componentValue);
        }
        $this->tabBuilder               = $component;
        // 返回资源对象
        return $this;
    }

    /**
     * 添加子面板数据
     *
     * @param string $field
     * @param string $title
     * @param array $children
     * @return FormBuilder
     */
    public function addTab(string $field, string $title, array $children): FormBuilder
    {
        $component[]                        = [
            'type'                          => 'el-tab-pane',
            'props'                         => [
                'name'                      => $field,
                'label'                     => $title,
            ],
            'children'                      => $children
        ];
        $this->tabBuilder->appendChildren($component);
        // 返回资源对象
        return $this;
    }

    /**
     * 结束选项卡表单
     *
     * @return $this
     */
    public function endTabs(): FormBuilder
    {
        $this->builder->append($this->tabBuilder);
        return $this;
    }

    /**
     * 设置表单渲染数据
     *
     * @param array $data
     * @return FormBuilder
     */
    public function setFormData(array $data): FormBuilder
    {
        $this->builder->formData($data);
        return $this;
    }

    /**
     * 设置请求方式
     *
     * @param string $method
     * @return FormBuilder
     */
    public function setMethod($method = 'GET'): FormBuilder
    {
        $this->builder->setMethod(strtoupper($method));
        return $this;
    }

    /**
     * 设置请求地址
     *
     * @param string $action
     * @return FormBuilder
     */
    public function setAction($action = ''): FormBuilder
    {
        $this->builder->setAction($action);
        return $this;
    }

    /**
     * 设置数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  type        $model
     * @return FormBuilder
     */
    public function setData($model): FormBuilder
    {
        if (!is_array($model)) {
            $data = $model->toArray();
            $this->builder->formData($data);
        }
        return $this;
    }

    /**
     * 快速生成表单
     *
     * @return array
     */
    public function create(): array
    {
        $apiUrl = $this->builder->getAction();
        $method = $this->builder->getMethod();
        $this->data['http']['api'] = $apiUrl;
        $this->data['http']['method'] = $method;
        $this->data['config'] = $this->builder->formConfig();
        $this->data['formRule'] = $this->builder->formRule();
        return $this->data;
    }

    /**
     * 获取builder生成类对象
     *
     * @return Form
     */
    public function getBuilder(): Form
    {
        return $this->builder;
    }
}
