<?php

namespace app\model;

use app\Model;

class SystemAuthRule extends Model
{
    // 设置JSON字段转换
    protected $json = ['method'];
    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    // 模型输出
    protected $append = [
        'path_text',
    ];

    /**
     * 修改器-检测父级菜单是否数组
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-10
     * @param  type $value
     * @return void
     */
    public function setPidAttr($value)
    {
        return is_array($value) ? end($value) : $value;
    }

    /**
     * 修改器-Path路径首字母转大写
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  type $value
     * @return void
     */
    public function setPathAttr($value)
    {
        return ucfirst((string)$value);
    }

    /**
     * 获取完整的请求地址
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  type $value
     * @param  type $data
     * @return void
     */
    protected function getPathTextAttr($value, $data)
    {
        return "{$data['module']}/{$data['path']}";
    }
}
