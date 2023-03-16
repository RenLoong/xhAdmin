<?php

namespace app\admin\validate;

use app\admin\model\SystemAdmin as ModelSystemAdmin;
use think\Validate;

class SystemAdmin extends Validate
{
    protected $rule =   [
        'role_id'           => 'require',
        'username'          => 'require|verifyAdd',
        'password'          => 'require',
        'nickname'          => 'require',
        'headimg'           => 'require',
    ];

    protected $message  =   [
        'role_id.require'   => '请选择所属部门',
        'username.require'  => '请输入登录账号',
        'password.require'  => '请输入登录密码',
        'nickname.require'  => '请输入用户昵称',
        'headimg.require'   => '请上传头像',
    ];

    /**
     * 登录场景
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @return void
     */
    public function sceneLogin()
    {
        return $this
            ->only([
                'username',
                'password',
            ])
            ->remove('username', ['verifyAdd']);
    }

    /**
     * 添加场景验证
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @return void
     */
    public function sceneAdd()
    {
        return $this
            ->only([
                'role_id',
                'username',
                'password',
                'nickname',
                'headimg',
            ]);
    }

    /**
     * 修改场景
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @return void
     */
    public function sceneEdit()
    {
        return $this
            ->only([
                'role_id',
                'username',
                'nickname',
                'headimg',
            ])
            ->remove('username', ['verifyAdd']);
    }

    /**
     * 验证是否存在
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  type $value
     * @return void
     */
    protected function verifyAdd($value)
    {
        $where = [
            'username'  => $value
        ];
        if (ModelSystemAdmin::where($where)->count()) {
            return '该用户已存在';
        }
        return true;
    }
}
