<?php

namespace app\admin\model;

use app\model\SystemAdmin as ModelSystemAdmin;
use app\common\service\UploadService;

/**
 * 管理员
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
class SystemAdmin extends ModelSystemAdmin
{
    /**
     * 关联等级
     * @return \think\model\relation\HasOne
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function role()
    {
        return $this->hasOne(SystemAdminRole::class, 'id', 'role_id');
    }


    /**
     * 获取头像URL
     * @param mixed $value
     * @return string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function getHeadimgAttr($value)
    {
        return $value ? UploadService::url((string)$value) : '';
    }
}
