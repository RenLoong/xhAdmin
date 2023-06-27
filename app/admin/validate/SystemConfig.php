<?php

namespace app\admin\validate;

use yzh52521\validate\Validate;

class SystemConfig extends Validate
{
    protected $rule =   [
        'cid'               => 'require',
        'title'             => 'require',
        'name'              => 'require',
        'type'              => 'require',
        'placeholder'       => 'require',
    ];

    protected $message  =   [
        'cid.require'               => '异常错误，缺少分类ID',
        'title.require'             => '请输入配置项名称',
        'name.require'              => '请输入配置项标识',
        'type.require'              => '请选择表单类型',
        'placeholder.require'       => '请输入配置项描述',
    ];

    protected $scene = [
        'add'               =>  ['title', 'name', 'type', 'placeholder'],
        'edit'              =>  ['title', 'type', 'placeholder'],
    ];
}
