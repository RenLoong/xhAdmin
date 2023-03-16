<?php

namespace app\service\cloud;

use app\BaseController;
use support\Request;
use think\facade\Cache;

/**
 * 云服务接口
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-14
 */
class KfCloudController extends BaseController
{
    /**
     * 用户注册
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-14
     * @param  Request $request
     * @return void
     */
    public function register(Request $request)
    {
        return parent::success('注册成功');
    }

    /**
     * 用户登录
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-14
     * @param  Request $request
     * @return void
     */
    public function login(Request $request)
    {
        Cache::set('kf_user', ['id' => 1], 0);
        return parent::success('登录成功');
    }

    /**
     * 用户退出
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-14
     * @param  Request $request
     * @return void
     */
    public function loginOut(Request $request)
    {
        return parent::success('退出成功');
    }

    /**
     * 用户数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-14
     * @param  Request $request
     * @return void
     */
    public function user(Request $request)
    {
        // 检测是否登录
        $user = Cache::get('kf_user');
        if (!$user) {
            return parent::failFul('请先登录', 12000);
        }
        return parent::successRes($user);
    }
}
