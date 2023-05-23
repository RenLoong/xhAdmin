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
        'expire_time'       => 'require',
        'contact'           => 'require',
        'mobile'            => 'require|mobile',
        'logo'              => 'require',
        'wechat'            => 'require',
        'mini_wechat'       => 'require',
        'douyin'            => 'require',
        'h5'                => 'require',
        'app'               => 'require',
        'other'             => 'require',
    ];

    protected $message  =   [
        'username.require'  => '请输入租户账号',
        'username.mobile'   => '租户账号必须是手机号',
        'password.require'  => '请输入租户密码',
        'title.require'         => '请输入租户名称',
        'expire_time.require'   => '请选择租户过期时间',
        'contact.require'   => '请输入联系人姓名',
        'mobile.require'    => '请输入联系手机',
        'mobile.mobile'     => '请输入正确的联系手机',
        'logo.require'      => '请上传租户图标',
        'wechat.require'      => '请输入公众号数量',
        'mini_wechat.require'      => '请输入微信小程序数量',
        'douyin.require'      => '请输入抖音小程序数量',
        'h5.require'      => '请输入网页应用数量',
        'app.require'      => '请输入APP数量',
        'other.require'      => '请输入其他应用数量',
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
                'expire_time',
                'contact',
                'mobile',
                'logo',
                'wechat',
                'mini_wechat',
                'douyin',
                'h5',
                'app',
                'other',
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
                'expire_time',
                'contact',
                'mobile',
                'logo',
                'wechat',
                'mini_wechat',
                'douyin',
                'h5',
                'app',
                'other',
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
