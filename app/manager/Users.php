<?php
namespace app\manager;
use Exception;
use app\model\Users as modelUsers;

/**
 * 用户管理器
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-20
 */
class Users
{
    /**
     * 添加用户
     * @param array $data
     * @return int|bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function add(array $data):int|bool
    {
        $model = new modelUsers;
        if ($model->save($data)) {
            return $model->id;
        }
        else {
            return false;
        }
    }

    /**
     * 修改用户
     * @param array $where
     * @param array $data
     * @throws Exception
     * @return int|bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function edit(array $where,array $data): int|bool
    {
        $model = modelUsers::where($where)->find();
        if (!$model) {
            throw new Exception('该用户未注册');
        }
        if ($model->save($data)) {
            return $model->id;
        }
        return false;
    }

    /**
     * 删除用户
     * @param array $where
     * @throws Exception
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function del(array $where)
    {
        $model = self::detail($where);
        if (!$model->delete()) {
            throw new Exception('删除用户失败');
        }
        return true;
    }

    /**
     * 修改登录账号
     * @param array $where
     * @param string $username
     * @throws Exception
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function setUsername(array $where,string $username)
    {
        $model           = self::detail($where);
        $model->username = $username;
        if (!$model->save()) {
            throw new Exception('修改用户账号失败');
        }
        return true;
    }

    /**
     * 修改登录密码
     * @param array $where
     * @param string $password
     * @throws Exception
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function setPassword(array $where,string $password)
    {
        $model = self::detail($where);
        $model->password = $password;
        if (!$model->save()) {
            throw new Exception('修改用户密码失败');
        }
        return true;
    }

    /**
     * 修改用户状态
     * @param array $where
     * @param string $status
     * @throws Exception
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function setStatus(array $where, string $status)
    {
        $model           = self::detail($where);
        $model->status = $status;
        if (!$model->save()) {
            throw new Exception('修改用户状态失败');
        }
        return true;
    }

    /**
     * 获取用户详情
     * @param array $where
     * @throws Exception
     * @return modelUsers
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function detail(array $where):modelUsers
    {
        $model = modelUsers::where($where)->find();
        if (!$model) {
            throw new Exception('该用户未注册');
        }
        return $model;
    }

    /**
     * 操作用户余额
     * @param int $uid
     * @param int $type
     * @param float $value
     * @param string $remarks
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function setMoney(int $uid, int $type, float $value, string $remarks)
    {
        return modelUsers::actionMoney($uid, $type, $value, $remarks);
    }

    /**
     * 操作用户积分
     * @param int $uid
     * @param int $type
     * @param int $value
     * @param string $remarks
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function setIntegral(int $uid, int $type, int $value, string $remarks)
    {
        return modelUsers::actionIntegral($uid, $type, $value, $remarks);
    }
}