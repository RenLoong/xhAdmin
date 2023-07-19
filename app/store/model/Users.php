<?php

namespace app\store\model;

use app\model\Store;
use app\model\StoreApp;
use app\model\StorePlatform;

/**
 * 用户管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class Users extends \app\model\Users
{
    // 定义全局查询范围
    protected $globalScope = ['store'];

    // 隐藏字段
    protected $hidden = [
        'password'
    ];


    /**
     * 基类查询
     * @param mixed $query
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function scopeStore($query)
    {
        $store_id = hp_admin_id('hp_store');
        $query->where('store_id', $store_id);
    }


    /**
     * 关联租户
     * @return \think\model\relation\HasOne
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    /**
     * 关联平台
     * @return \think\model\relation\HasOne
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public function platform()
    {
        return $this->hasOne(StorePlatform::class, 'id', 'platform_id');
    }

    /**
     * 关联应用
     * @return \think\model\relation\HasOne
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public function platformApp()
    {
        return $this->hasOne(StoreApp::class, 'id', 'appid');
    }
}