<?php

namespace app\store\model;

use app\model\SystemUpload as ModelSystemUpload;

class SystemUpload extends ModelSystemUpload
{
    // 定义全局查询范围
    protected $globalScope = ['store'];
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
}
