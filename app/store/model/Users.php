<?php

namespace app\store\model;

use app\common\model\Store;
use app\common\model\StoreApp;

/**
 * 用户管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class Users extends \app\common\model\Users
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
        $store_id = request()->user['id'];
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
     * 关联应用
     * @return \think\model\relation\HasOne
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public function storeApp()
    {
        return $this->hasOne(StoreApp::class, 'id', 'saas_appid');
    }
}