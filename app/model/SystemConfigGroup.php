<?php

namespace app\model;

use app\enum\IsSystem;
use app\Model;

class SystemConfigGroup extends Model
{
    // 模型输出
    protected $append = [
        'is_system_text',
    ];

    /**
     * 获取分组类型
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  type $value
     * @param  type $data
     * @return void
     */
    protected function getIsSystemTextAttr($value, $data)
    {
        return IsSystem::getText($data['is_system'])['text'];
    }


    /**
     * 删除前置事件
     * TODO:该功能待完善
     * 删除时检测是否存在配置项，否则禁止删除
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-05
     * @return void
     */
    public static function onBeforeDelete($model)
    {
    }
}
