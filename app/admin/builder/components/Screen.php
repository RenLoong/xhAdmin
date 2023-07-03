<?php

namespace app\admin\builder\components;

use app\admin\builder\ListBuilder;

/**
 * 表格筛选配置
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-02-27
 */
trait Screen
{
    # 筛选表单
    protected $formConfig = [];
    # 筛选表单配置
    protected $screenConfig = [
        'api'           => '',
        'method'        => 'GET',
        'type'          => 'submit',
        'status'        => 'primary',
        'content'       => '查询',
    ];

    /**
     * 添加筛选单元格
     * @param string $field
     * @param string $type
     * @param string $title
     * @param array $extra
     * @return \app\admin\builder\ListBuilder
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function addScreen(string $field, string $type, string $title, array $extra = []): ListBuilder
    {
        if (!isset($this->formConfig['items'])) {
            $this->formConfig['data']  = [];
            $this->formConfig['items'] = [];
        }
        $item = [
            'field' => $field,
            'title' => $title,
            'itemRender' => [
                'name' => "\${$type}",
                'props' => [
                    'placeholder' => "请输入{$title}"
                ],
            ],
        ];
        if ($extra && is_array($extra)) {
            $item['itemRender']['props'] = $extra;
        }
        array_push($this->formConfig['items'], $item);
        $this->formConfig['data'][$field] = '';
        return $this;
    }

    /**
     * 筛选表单配置
     * @param array $config
     * @return \app\admin\builder\ListBuilder
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function screenConfig(array $config):ListBuilder
    {
        $this->screenConfig = array_merge($this->screenConfig,$config);
        return $this;
    }
}