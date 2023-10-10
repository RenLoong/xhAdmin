<?php

namespace app\store\validate;

use app\store\model\StoreApp as ModelStoreApp;
use think\Validate;

class StoreApp extends Validate
{
    protected $rule =   [
        'store_id'          => 'require',
        'platform'          => 'require',
        'title'             => 'require|verifyTitle',
        'username'          => 'require',
        'password'          => 'require',
        'name'              => 'require',
        'logo'              => 'require',
    ];

    protected $message  =   [
        'store_id.require'          => '缺少租户参数',
        'platform.require'          => '缺少项目平台类型',
        'title.require'             => '请输入项目名称',
        'username.require'          => '请输入管理员账号',
        'password.require'          => '请输入管理员密码',
        'name.require'              => '请选择应用插件',
        'logo.require'              => '请上传应用图标',
    ];

    public function sceneAdd()
    {
        return $this
            ->only([
                'store_id',
                'platform',
                'title',
                'url',
                'username',
                'password',
                'name',
                'logo',
            ]);
    }
    public function sceneEdit()
    {
        return $this
            ->only([
                'store_id',
                'platform',
                'title',
                'url',
                'name',
                'username',
                'logo',
            ])
            ->remove('title', ['verifyTitle']);
    }

    /**
     * 添加验证
     * @param mixed $value
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    protected function verifyTitle($value)
    {
        $store_id = request()->user['id'];
        $where = [
            'store_id'      => $store_id,
            'title'         => $value
        ];
        if (ModelStoreApp::where($where)->count()) {
            return '该应用已添加';
        }
        return true;
    }
}
