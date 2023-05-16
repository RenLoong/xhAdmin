<?php

namespace app\model;

use app\Model;
use app\service\Upload;
use app\utils\Password;

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
    protected $json = ['plugins_name'];
    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    // 隐藏字段
    protected $hidden = [
        'password'
    ];
    
    /**
     * 一对一关联租户等级
     * @return \think\model\relation\HasOne
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-02
     */
    public function grade()
    {
        return $this->hasOne(StoreGrade::class, 'id', 'grade_id');
    }

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
        return $value ? Upload::url((string)$value) : '';
    }
}
