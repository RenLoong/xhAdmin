<?php

namespace app\admin\controller;

use app\admin\service\kfcloud\CloudService;
use app\BaseController;
use support\Request;
use think\facade\Cache;

/**
 * 云服务中心
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-05
 */
class PluginCloudController extends BaseController
{
    /**
     * 云服务中心
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public function index(Request $request)
    {
        // 检测云服务是否登录
        if (!Cache::has(CloudService::$loginToken)) {
            return $this->failFul('请先登录', 11000);
        }
        $data = CloudService::user()->array();
        return json($data);
    }

    /**
     * 云服务登录
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public function login(Request $request)
    {
        $post = $request->post();
        if (!isset($post['username']) || !$post['username']) {
            return $this->fail('请输入云服务账号');
        }
        if (!isset($post['password']) || !$post['password']) {
            return $this->fail('请输入登录密码');
        }
        if (!isset($post['scode']) || !$post['scode']) {
            return $this->fail('请输入图像验证码');
        }
        $response = CloudService::login(
            (string) $post['username'],
            (string) $post['password'],
            (string) $post['scode']
        )->array();
        if ($response['code'] == 200) {
            Cache::set('kf_user_token', $response['data']['token'], 0);
        }
        return json($response);
    }


    /**
     * 获取账单流水
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  Request $request
     * @return void
     */
    public function bill(Request $request)
    {
    }

    /**
     * 获取图像验证码
     * @param Request $request
     * @return string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public function captcha(Request $request)
    {
        return CloudService::captcha()->body();
    }
}