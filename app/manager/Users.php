<?php
namespace app\manager;

use app\utils\Password;
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
     * 用户登录
     * @param int $appid
     * @param string $username
     * @param string $password
     * @throws \Exception
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function login(int $appid,string $username, string $password)
    {
        # 获取应用数据
        $app = StoreApp::detail($appid);

        # 查询数据
        $where      = [
            'username' => $username
        ];
        $userModel = modelUsers::where($where)->find();
        if (!$userModel) {
            throw new Exception('登录账号错误');
        }
        # 验证登录密码
        if (!Password::passwordVerify((string) $password, (string) $userModel->password)) {
            throw new Exception('登录密码错误');
        }
        if ($userModel->status == 0) {
            throw new Exception('该用户已被冻结');
        }
        $user    = $userModel->toArray();
        $request = request();
        $session = $request->session();
        $session->set($app['name'], $user);

        # 更新登录信息
        $ip                             = $request->getRealIp($safe_mode = true);
        $userModel->last_login_ip       = $ip;
        $userModel->last_login_time     = date('Y-m-d H:i:s');
        if (!$userModel->save()) {
            throw new Exception('用户登录失败');
        }
        $user['token'] = $request->sessionId();
        return $user;
    }

    /**
     * 退出登录
     * @param string $appid
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function loginout(string $appid)
    {
        # 获取应用数据
        $app = StoreApp::detail($appid);
        # 删除登录信息
        $session = request()->session();
        $session->delete($app['name']);
        return true;
    }

    /**
     * 添加用户
     * @param array $data
     * @return int|bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public static function add(array $data): int|bool
    {
        if (empty($data['appid'])) {
            throw new Exception('缺少应用ID');
        }
        # 查询APPID
        $app = StoreApp::detail((int) $data['appid']);
        if (!$app) {
            throw new Exception('该应用不存在');
        }
        $data['store_id']    = $app['store_id'];
        $data['platform_id'] = $app['platform_id'];
        $model               = new modelUsers;
        if ($model->save($data)) {
            return $model->id;
        } else {
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
    public static function edit(array $where, array $data): int|bool
    {
        if (empty($where['appid'])) {
            throw new Exception('缺少平台ID');
        }
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
        if (empty($where['appid'])) {
            throw new Exception('缺少平台ID');
        }
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
    public static function setUsername(array $where, string $username)
    {
        if (empty($where['appid'])) {
            throw new Exception('缺少平台ID');
        }
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
    public static function setPassword(array $where, string $password)
    {
        if (empty($where['appid'])) {
            throw new Exception('缺少平台ID');
        }
        $model           = self::detail($where);
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
        if (empty($where['appid'])) {
            throw new Exception('缺少平台ID');
        }
        $model         = self::detail($where);
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
    public static function detail(array $where): modelUsers
    {
        if (empty($where['appid'])) {
            throw new Exception('缺少平台ID');
        }
        $model = modelUsers::where($where)->find();
        if (!$model) {
            throw new Exception('该用户未注册');
        }
        return $model;
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
    public static function setMoney(int $appid, int $uid, int $type, float $value, string $remarks)
    {
        return modelUsers::actionMoney($appid, $uid, $type, $value, $remarks);
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
    public static function setIntegral(int $appid, int $uid, int $type, int $value, string $remarks)
    {
        return modelUsers::actionIntegral($appid, $uid, $type, $value, $remarks);
    }
}