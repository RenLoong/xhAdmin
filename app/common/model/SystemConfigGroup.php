<?php

namespace app\common\model;

use app\common\enum\IsSystem;
use app\Model;

class SystemConfigGroup extends Model
{
    // 模型输出
    protected $append = [
        'is_system_text',
    ];

    /**
     * 获取分组类型
     * @param mixed $value
     * @param mixed $data
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function getIsSystemTextAttr($value, $data)
    {
        return IsSystem::getText($data['is_system']);
    }
}
