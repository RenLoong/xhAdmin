<?php

namespace app\model;

use app\Model;
use app\service\Upload;
use app\utils\Password;
use Exception;

/**
 * 商户
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class Users extends Model
{
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
     * 设置头像储存
     * @param mixed $value
     * @return array|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    protected function setHeadimgAttr($value)
    {
        return $value ? Upload::path($value) : '';
    }

    /**
     * 获取头像
     * @param mixed $value
     * @return string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    protected function getHeadimgAttr($value)
    {
        return $value ? Upload::url((string)$value) : '';
    }

    /**
     * 操作用户积分
     * @param int $appid
     * @param int $uid
     * @param int $type
     * @param int $value
     * @param string $remarks
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function actionIntegral(int $appid,int $uid, int $type, int $value, string $remarks): bool
    {
        $where = [
            'id' => $uid,
            'appid'=> $appid
        ];
        $model = self::where($where)->find();
        if (!$model) {
            throw new Exception('该用户未注册');
        }
        UsersIntegralBill::addBill($model, $type, $value, $remarks);
        if ($type == 1) {
            $model->integral = $model->integral + $value;
        }
        else if ($type === 0) {
            if ($value > $model->integral) {
                throw new Exception('用户积分不足');
            }
            $model->integral = $model->integral - $value;
        }
        if (!$model->save()) {
            throw new Exception('操作用户积分失败');
        }
        return true;
    }

    /**
     * 操作用户余额
     * @param int $appid
     * @param int $uid
     * @param int $type
     * @param float $value
     * @param string $remarks
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function actionMoney(int $appid,int $uid, int $type, float $value, string $remarks)
    {
        $where = [
            'id' => $uid,
            'appid'=> $appid
        ];
        $model = self::where($where)->find();
        if (!$model) {
            throw new Exception('该用户未注册');
        }
        UsersMoneyBill::addBill($model, $type, $value, $remarks);
        if ($type == 1) {
            $model->money = $model->money + $value;
        }
        else if ($type === 0) {
            if ($value > $model->money) {
                throw new Exception('用户余额不足');
            }
            $model->money = $model->money - $value;
        }
        if (!$model->save()) {
            throw new Exception('操作余额失败');
        }
        return true;
    }
}
