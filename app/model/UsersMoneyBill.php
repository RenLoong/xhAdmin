<?php

namespace app\model;

use app\Model;
use Exception;

/**
 * 用户余额账单
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-20
 */
class UsersMoneyBill extends Model
{
    // 开启自动时间戳
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = null;

    /**
     * 添加账单
     * @param Model\Users $userModel
     * @param int $type
     * @param float $value
     * @param string $remarks
     * @throws Exception
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function addBill(Users $userModel, int $type, float $value, string $remarks): bool
    {
        $billModel            = new UsersMoneyBill;
        $billModel->uid       = $userModel->id;
        $billModel->bill_type = $type;
        $billModel->value     = $value;
        $billModel->old_value = $userModel->money;
        $billModel->remarks   = $remarks;
        if ($type == 1) {
            $billModel->new_value = $userModel->money + $value;
        }
        else if ($type === 0) {
            if ($value > $userModel->money) {
                throw new Exception('账单:用户余额不足');
            }
            $billModel->new_value = $userModel->money - $value;
        }
        if (!$billModel->save()) {
            throw new Exception('添加余额账单失败');
        }
        return true;
    }
}
