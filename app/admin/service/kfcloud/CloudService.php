<?php

namespace app\admin\service\kfcloud;

use yzh52521\EasyHttp\Response;

/**
 * KFAdmin云服务
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-22
 */
class CloudService
{
    // 登录令牌名称
    public static $loginToken = 'kf_user_token';

    /**
     * 登录云服务接口
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  string   $username
     * @param  string   $password
     * @param  string   $scode
     * @return Response
     */
    public static function login(string $username, string $password, string $scode): Response
    {
        $body = [
            'username'      => $username,
            'password'      => $password,
            'scode'         => $scode
        ];
        return HttpService::send()->post('User/login', $body);
    }

    /**
     * 获取图像验证码
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-22
     * @return Response
     */
    public static function captcha(): Response
    {
        return HttpService::send()->get('Captcha/captcha');
    }

    /**
     * 获取用户信息
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @return Response
     */
    public static function user(): Response
    {
        return HttpService::send()->get('User/info');
    }

    /**
     * 获取应用插件列表
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  integer  $page
     * @param  integer  $limit
     * @return Response
     */
    public static function list(int $page, int $limit = 20): Response
    {
        $query = [
            'page'      => $page,
            'limit'     => $limit
        ];
        return HttpService::send()->get('Plugin/list', $query);
    }

    /**
     * 获取应用插件详情
     * @param string $name
     * @return Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public static function detail(string|null $name): Response
    {
        $query = [
            'name'         => $name
        ];
        return HttpService::send()->get('Plugin/detail', $query);
    }

    /**
     * 购买应用
     * @param string $name
     * @return Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public static function buyApp(string|null $name): Response
    {
        $query = [
            'name'         => $name
        ];
        return HttpService::send()->get('Plugin/buy', $query);
    }

    /**
     * 充值-统一下订单
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  string   $order_no
     * @return Response
     */
    public static function unifiedOrder(string $order_no): Response
    {
        $query = [];
        return HttpService::send()->get('Order/unifiedOrder', $query);
    }

    /**
     * 执行安装
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  string  $name
     * @param  string   $step
     * @return Response
     */
    public static function getDownKey(string|null $name, string $step): Response
    {
        $query = [
            'name'          => $name,
            'step'          => $step
        ];
        return HttpService::send()->get('Plugin/getKey', $query);
    }

    /**
     * 获取下载流
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-25
     * @param  string   $key
     * @return Response
     */
    public static function install(string $key): Response
    {
        $query = [
            'key'           => $key
        ];
        return HttpService::send()
            ->withStream(true)
            ->get('Plugin/install', $query);
    }
}
