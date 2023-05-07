<?php

namespace app\admin\builder;

use app\Model;
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

    // 额外扩展配置
    protected $extraConfig = [];

    // 提交后重定向地址
    protected $redirect = '';

    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function __construct()
    {
        $this->request = request();
        $url           = $this->request->path();
        $rule          = [];
        $config        = [];
        $this->builder = Form::elm($url, $rule, $config);

        // 额外扩展配置
        $extraConfig       = [
            'submitBtn' => [
                'type'    => 'success',
                'content' => '提交保存',
                'show'    => true,
                'style'   => [
                    'padding' => '0 30px'
                ]
            ],
            'resetBtn'  => [
                'type'    => 'warning',
                'content' => '数据重置',
                'show'    => true,
                'style'   => [
                    'padding' => '0 30px'
                ]
            ]
        ];
        $this->extraConfig = $extraConfig;
    }

    /**
     * 表单配置
     * @param string $name
     * @param array $value
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function setConf(string $name, array $value): FormBuilder
    {
        $this->extraConfig[$name] = $value;
        return $this;
    }

    /**
     * 添加表单行元素
     * @param string $field
     * @param string $type
     * @param string $title
     * @param mixed $value
     * @param array $extra
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    public function addRow(string $field, string $type, string $title,$value = '', array $extra = []): FormBuilder
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
     * @param string $field
     * @param string $type
     * @param string $title
     * @param mixed $value
     * @param array $extra
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
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
     * @param string $title
     * @param array $extra
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function addDivider(string $title, array $extra = []): FormBuilder
    {
        // 创建自定义组件
        $component = new CustomComponent('n-divider');
        // 设置属性
        $component
            ->appendChild($title)
            ->appendRule('wrap', ['show' => false])
            ->appendRule('native', false)
            ->appendRule('_fc_drag_tag', 'n-divider')
            ->appendRule('_fc_drag_tag', 'n-divider')
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
     * @param string $active
     * @param array $extra
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function initTabs(string $active, array $extra = []): FormBuilder
    {
        // 选项卡组件
        $component = new CustomComponent('n-tabs');
        // 设置默认选中
        $component->props([
            'defaultValue' => $active,
        ]);
        // 设置默认样式
        $component->style([
            'width'  => '100%',
            'margin' => '0 15px',
        ]);
        foreach ($extra as $componentType => $componentValue) {
            $component->$componentType($componentValue);
        }
        $this->tabBuilder = $component;
        // 返回资源对象
        return $this;
    }

    /**
     * 添加子面板数据
     * @param string $field
     * @param string $title
     * @param array $children
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function addTab(string $field, string $title, array $children): FormBuilder
    {
        $component[] = [
            'type'     => 'n-tab-pane',
            'props'    => [
                'name' => $field,
                'tab'  => $title,
            ],
            'children' => $children
        ];
        $this->tabBuilder->appendChildren($component);
        // 返回资源对象
        return $this;
    }

    /**
     * 结束选项卡表单
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function endTabs(): FormBuilder
    {
        $this->builder->append($this->tabBuilder);
        return $this;
    }

    /**
     * 设置表单渲染数据
     * @param array $data
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function setFormData(array $data): FormBuilder
    {
        $this->builder->formData($data);
        return $this;
    }

    /**
     * 设置请求方式
     * @param mixed $method
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function setMethod($method = 'GET'): FormBuilder
    {
        $this->builder->setMethod(strtoupper($method));
        return $this;
    }

    /**
     * 设置请求地址
     * @param mixed $action
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function setAction($action = ''): FormBuilder
    {
        $this->builder->setAction($action);
        return $this;
    }

    /**
     * 设置提交后重定向地址
     * @param string $url
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function setRedirect(string $url)
    {
        $this->redirect = $url;
        return $this;
    }

    /**
     * 设置数据
     * @param Model $model
     * @return FormBuilder
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function setData(Model $model): FormBuilder
    {
        if (!is_array($model)) {
            $data = $model->toArray();
            $this->builder->formData($data);
        }
        return $this;
    }

    /**
     * 快速生成表单
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function create(): array
    {
        $apiUrl                       = $this->builder->getAction();
        $method                       = $this->builder->getMethod();
        $this->data['http']['api']    = $apiUrl;
        $this->data['http']['method'] = $method;
        $this->data['config']         = $this->builder->formConfig();
        $this->data['extraConfig']    = $this->extraConfig;
        $this->data['formRule']       = $this->builder->formRule();
        $this->data['redirect']       = $this->redirect;
        return $this->data;
    }

    /**
     * 获取builder生成类对象
     * @return Form
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function getBuilder(): Form
    {
        return $this->builder;
    }
}