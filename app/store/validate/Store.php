<?php

namespace app\store\validate;

use app\store\model\Store as ModelStore;
use think\Validate;

class Store extends Validate
{
    protected $rule =   [
        'title'             => 'require|verifyTitle',
        'contact'           => 'require',
        'mobile'            => 'require|mobile',
        'logo'              => 'require',
    ];

    protected $message  =   [
        'title.require'     => '请输入租户名称',
        'contact.require'   => '请输入租户密码',
        'mobile.require'    => '请输入联系手机',
        'mobile.mobile'     => '请输入正确的联系手机',
        'logo.require'      => '请上传租户图标',
    ];

    /**
     * 验证租户名称
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @param  string $value
     * @return bool|string
     */
    protected function verifyTitle($value)
    {
        $store_id = hp_admin_id('hp_store');
        $where = [
            ['title','=',$value],
            ['id','<>',$store_id]
        ];
        if (ModelStore::where($where)->count()) {
            return '该租户名称已存在';
        }
        return true;
    }
}
