<?php

namespace app\admin\model;

use app\model\SystemAdmin as ModelSystemAdmin;

class SystemAdmin extends ModelSystemAdmin
{
    /**
     * 关联等级
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-02
     * @return void
     */
    public function role()
    {
        return $this->hasOne(SystemAdminRole::class, 'id', 'role_id');
    }
}
