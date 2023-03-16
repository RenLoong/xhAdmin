<?php

namespace app\admin\builder\components;

use app\admin\builder\ListBuilder;

/**
 * 表格按钮配置
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-02-27
 */
trait Button
{
    // 表格顶部按钮
    private $topButtonList = [];

    // 表格列按钮
    private $rightButtonList = [];

    /**
     * 开启操作选项
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-02-28
     * @param  string      $title
     * @param  array       $extra
     * @return ListBuilder
     */
    public function addActionOptions(string $title, array $extra = []): ListBuilder
    {
        $field = 'rightButtonList';
        $extra = array_merge([
            'width'             => 'auto',
            'fixed'             => 'right',
            'slots'             => [
                'default'       => $field
            ],
        ], $extra);
        $this->addColumn($field, $title, $extra);
        return $this;
    }

    /**
     * 添加表格头部按钮
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-01
     * @param  string      $field
     * @param  string      $title
     * @param  array       $pageData
     * @return ListBuilder
     */
    public function addTopButton(string $field, string $title, array $pageData = [], array $message = [], array $button = []): ListBuilder
    {
        $btnData                             = [
            'field'                         => $field,
            'title'                         => $title,
            'pageData'                      => [
                'type'                      => 'page', // 支持：page，modal，confirm，table
                'api'                       => '',
                'method'                    => 'GET',
                'queryParams'               => [],
            ],
            // 按钮样式
            'button'                        => [],
            // type为modal时有效
            'message'                       => [
                'title'                     => '温馨提示',
            ],
        ];
        // 合并页面数据
        $btnData['pageData']                = array_merge($btnData['pageData'], $pageData);
        // 设置模态框
        if (in_array($btnData['pageData']['type'], ['modal', 'table', 'remote'])) {
            $btnData['message']['width']                = '60%';
            $btnData['message']['mask']                 = true;
            $btnData['message']['top']                  = '10vh';
            $btnData['message']['showClose']            = true;
            $btnData['message']['destroyOnClose']       = true;
            $btnData['message']['closeOnClickModal']    = false;
        }
        // 合并消息数据
        $btnData['message']                 = array_merge($btnData['message'], $message);
        // 默认的按钮样式
        $btnData['button']                  = array_merge($this->getBtnDefaultStyle(), $button);
        $this->topButtonList[]              = $btnData;
        return $this;
    }

    /**
     * 添加列按钮
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-02-28
     * @param  string      $field
     * @param  string      $title
     * @param  array       $pageData
     * @param  array       $message
     * @param  array       $button
     * @return ListBuilder
     */
    public function addRightButton(string $field, string $title, array $pageData = [], array $message = [], array $button = []): ListBuilder
    {
        $btnData                             = [
            'field'                         => $field,
            'title'                         => $title,
            'pageData'                      => [
                'type'                      => 'page', // 支持：page，modal，confirm，table
                'api'                       => '',
                'method'                    => 'GET',
                'queryParams'               => [],
                'aliasParams'               => [],
            ],
            // 按钮样式
            'button'                        => [],
            // type为modal时有效
            'message'                       => [
                'title'                     => '温馨提示',
            ],
        ];
        // 合并页面数据
        $btnData['pageData']                = array_merge($btnData['pageData'], $pageData);
        // 增加宽度与高度
        if (in_array($btnData['pageData']['type'], ['modal', 'table', 'remote'])) {
            $btnData['message']['width']                = '60%';
            $btnData['message']['mask']                 = true;
            $btnData['message']['top']                  = '10vh';
            $btnData['message']['showClose']            = true;
            $btnData['message']['destroyOnClose']       = true;
            $btnData['message']['closeOnClickModal']    = false;
        }
        // 合并消息数据
        $btnData['message']                 = array_merge($btnData['message'], $message);
        // 默认的按钮样式
        $btnData['button']                  = array_merge($this->getBtnDefaultStyle(), $button);
        $this->rightButtonList[]            = $btnData;
        return $this;
    }

    /**
     * 获得默认按钮样式
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-02-28
     * @param  string $text
     * @param  string $type
     * @param  string $size
     * @return array
     */
    private function getBtnDefaultStyle(string $type = 'default', string $size = ''): array
    {
        $data                   = [
            //类型 default / primary / success / warning / danger / info / text
            'type'              => $type,
            //尺寸 medium / small / mini
            'size'              => $size,
            //是否朴素按钮
            'plain'             => false,
            //是否圆角按钮
            'round'             => false,
            //是否圆形按钮
            'circle'            => false,
            //是否加载中状态
            'loading'           => false,
            //是否禁用状态
            'disabled'          => false,
            //图标类名
            'icon'              => '',
            //是否默认聚焦
            'autofocus'         => false,
            //原生 type 属性
            'nativeType'        => "button",
            // 是否显示按钮
            'show'              => true,
            // 是否文字链接
            'link'              => false,
        ];
        return $data;
    }
}
