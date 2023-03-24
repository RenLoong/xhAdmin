<?php

namespace app\admin\validate;

use think\Validate;

class SystemAuthRule extends Validate
{
    protected $rule =   [
        'module'            => 'require',
        'title'             => 'require',
        'namespace'         => 'require',
        'path'              => 'require',
        'method'            => 'require',
        'sort'              => 'require',
    ];

    protected $message  =   [
        'module.require'        => '请输入模块名称',
        'title.require'         => '请输入菜单名称',
        'namespace.require'     => '请输入命名空间',
        'path.require'          => '请输入权限路由',
        'method.require'        => '请选择请求类型',
        'sort.require'          => '请输入菜单排序',
    ];

    protected $scene = [
        'add'               =>  [
            'module',
            'title',
            'namespace',
            'path',
            'method',
            'sort',
        ],
        'edit'              =>  [
            'module',
            'title',
            'namespace',
            'path',
            'method',
            'sort',
        ],
    ];
}
