<?php

namespace app\store\validate;

use think\Validate;

class Develop extends Validate
{
    protected $rule             =   [
        'title'                 => 'require',
        'platform'              => 'require',
        'team'                  => 'require',
        'name'                  => 'require',
        'settings'              => 'require',
        'is_auth'               => 'require',
        'username'              => 'require',
        'password'              => 'require',
    ];

    protected $message          =   [
        'title.require'         => '请输入项目名称',
        'platform.require'      => '请选择项目类型',
        'team.require'          => '请输入团队标识',
        'name.require'          => '请输入应用标识',
        'settings.require'      => '是否需要系统配置项',
        'is_auth.require'       => '是否需要权限管理',
        'username.require'      => '请输入超管账号',
        'password.require'      => '请输入登录密码',
    ];
}
