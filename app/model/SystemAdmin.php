<?php

namespace app\model;

use app\Model;
use app\service\Upload;
use app\utils\Password;

/**
 * 管理员
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-08
 */
class SystemAdmin extends Model
{
    // 隐藏字段
    protected $hidden = [
        'password'
    ];

    // 标记字段为附件类型
    public $uploadify = [
        'headimg'   => 'image',
    ];

    /**
     * 密码加密写入
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-08
     * @param  type $value
     * @return void
     */
    protected function setPasswordAttr($value)
    {
        if (!$value) {
            return false;
        }
        return Password::passwordHash((string)$value);;
    }
}
