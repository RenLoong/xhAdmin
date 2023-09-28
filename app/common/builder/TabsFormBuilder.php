<?php
namespace app\common\builder;
use FormBuilder\Driver\CustomComponent;

/**
 * 选项卡表单构建器
 * Class TabsFormBuilder
 * @package app\common\builder
 */
trait TabsFormBuilder
{

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
        $component = new CustomComponent('el-tabs');
        // 设置默认选中
        $component->props([
            'modelValue'    => $active,
            'type'          => 'border-card'
        ]);
        foreach ($extra as $componentType => $componentValue) {
            $component->$componentType($componentValue);
        }
        $this->tabBuilder = $component;
        // 返回资源对象
        return $this;
    }

    /**
     * 添加子选项卡数据
     * @param string $field
     * @param string $title
     * @param array $children
     * @return FormBuilder
     * @throws \Exception
     */
    public function addTab(string $field, string $title, array $children): FormBuilder
    {
        return $this->addPanel($field, $title, $children);
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
    public function addPanel(string $field, string $title, array $children): FormBuilder
    {
        $component[] = [
            'type'          => 'el-tab-pane',
            'props'         => [
                'name'      => $field,
                'label'     => $title,
            ],
            'children'      => $children
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
}