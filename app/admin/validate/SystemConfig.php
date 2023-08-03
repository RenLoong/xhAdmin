<?php

namespace app\admin\validate;

use yzh52521\validate\Validate;

class SystemConfig extends Validate
{
    protected $rule =   [
        'title'             => 'require',
        'name'              => 'require',
        'component'         => 'require',
        'placeholder'       => 'require',
    ];

    protected $message  =   [
        'title.require'             => '配置项标识不能为空',
        'name.require'              => '配置项名称不能为空',
        'component.require'         => '表单组件不能为空',
        'placeholder.require'       => '表单底部描述不能为空',
    ];

    protected $scene = [
        'add'               =>  ['title', 'name', 'type', 'placeholder'],
        'edit'              =>  ['title', 'type', 'placeholder'],
    ];
}
