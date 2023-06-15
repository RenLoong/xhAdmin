<?php

namespace app\admin\validate;

use yzh52521\validate\Validate;

class SystemConfigGroup extends Validate
{
    protected $rule =   [
        'title'             => 'require',
        'name'              => 'require',
    ];

    protected $message  =   [
        'title.require'     => '请输入分组名称',
        'name.require'      => '请输入分组标识',
    ];

    protected $scene = [
        'add'               =>  ['title', 'name'],
        'edit'              =>  ['title'],
    ];
}
