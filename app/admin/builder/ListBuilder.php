<?php

namespace app\admin\builder;

use app\admin\builder\components\Button;
use app\admin\builder\components\Table;
use support\Response;

/**
 * 表格构造器
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-02-27
 */
class ListBuilder
{
    // 表格设置
    use Table;
    // 表格按钮
    use Button;

    // 在每个对象的静态缓存中存储现有属性。
    protected static $cache = [];

    /**
     * 初始化表格
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-02-28
     */
    public function __construct()
    {
    }

    /**
     * 解析表格规则
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-02-27
     * @return array
     */
    private function parseRule(): array
    {
        return get_object_vars($this);
    }

    /**
     * 获取表格JSON规则
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-02-27
     * @return Response
     */
    public function JSONRule(): Response
    {
        return json($this->parseRule());
    }

    /**
     * 返回表格规则
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-02-27
     * @return array
     */
    public function create(): array
    {
        $resutl = $this->parseRule();
        $data['tableConfig'] = $resutl;

        // 移除按钮属性
        if (isset($data['tableConfig']['topButtonList'])) {
            unset($data['tableConfig']['topButtonList']);
        }
        if (isset($data['tableConfig']['rightButtonList'])) {
            unset($data['tableConfig']['rightButtonList']);
        }
        if (isset($data['tabsConfig']['tabsConfig'])) {
            unset($data['tabsConfig']['tabsConfig']);
        }
        $data['tabsConfig'] = $resutl['tabsConfig'];
        $data['topButtonList'] = $resutl['topButtonList'];
        $data['rightButtonList'] = $resutl['rightButtonList'];

        // 返回数据
        return $data;
    }

    /**
     * 动态设置属性
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  type $name
     * @param  type $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
