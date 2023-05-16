<?php

namespace app\admin\validate;

use app\admin\model\Store as ModelStore;
use think\Validate;

class Store extends Validate
{
    protected $rule =   [
        'username'          => 'require|mobile|verifyUsername',
        'password'          => 'require',
        'title'             => 'require|verifyTitle',
        'contact'           => 'require',
        'mobile'            => 'require',
        'logo'              => 'require',
        'grade_id'          => 'require',
    ];

    protected $message  =   [
        'username.require'  => '请输入租户账号',
        'username.mobile'   => '租户账号必须是手机号',
        'password.require'  => '请输入租户密码',
        'title.require'     => '请输入租户名称',
        'contact.require'   => '请输入联系人姓名',
        'mobile.require'    => '请输入联系电话',
        'logo.require'      => '请上传租户图标',
        'grade_id.require'  => '请选择租户等级',
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
                'username',
                'password',
                'title',
                'contact',
                'mobile',
                'logo',
                'grade_id',
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
                'username',
                'title',
                'contact',
                'mobile',
                'logo',
                'grade_id',
            ])
            ->remove('username', ['verifyUsername'])
            ->remove('title', ['verifyTitle']);
    }

    /**
     * 验证租户账号
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  type $value
     * @return void
     */
    protected function verifyUsername($value)
    {
        $where = [
            'username'  => $value
        ];
        if (ModelStore::where($where)->count()) {
            return '该账号已存在';
        }
        return true;
    }

    /**
     * 验证租户名称
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @param  type $value
     * @return void
     */
    protected function verifyTitle($value)
    {
        $where = [
            'title'  => $value
        ];
        if (ModelStore::where($where)->count()) {
            return '该租户名称已存在';
        }
        return true;
    }
}
