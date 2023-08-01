<?php

namespace app\common\model;

use app\Model;
use app\common\service\UploadService;
use app\common\utils\Password;

/**
 * 商户
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class Store extends Model
{
    // 设置JSON字段转换
    protected $json = [
        'plugins_name'
    ];
    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    // 隐藏字段
    protected $hidden = [
        'password'
    ];

    /**
     * 密码加密写入
     * @param mixed $value
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    protected function setPasswordAttr($value)
    {
        if (!$value) {
            return false;
        }
        return Password::passwordHash((string)$value);;
    }

    /**
     * 获取LOGO
     * @param mixed $value
     * @return string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    protected function getLogoAttr($value)
    {
        return $value ? UploadService::url((string)$value) : '';
    }
}
