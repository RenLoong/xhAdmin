<?php

namespace app\admin\validate;

use yzh52521\validate\Validate;

class SystemConfig extends Validate
{
    protected $rule =   [
        'group_name'        => 'require',
        'title'             => 'require',
        'name'              => 'require',
        'component'         => 'require',
        'placeholder'       => 'require',
    ];

    protected $message  =   [
        'group_name.require'        => '缺少参数 - [分组标识]',
        'title.require'             => '请输入配置项名称',
        'name.require'              => '请输入配置项标识',
        'component.require'         => '请选择表单类型',
        'placeholder.require'       => '请输入配置项描述',
    ];

    protected $scene = [
        'add'               =>  ['title', 'name', 'type', 'placeholder'],
        'edit'              =>  ['title', 'type', 'placeholder'],
    ];
}
