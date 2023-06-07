<?php

namespace app\admin\service\kfcloud;

use app\admin\logic\PluginLogic;
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
    public static function login(string $username, string $password, string $scode = ''): Response
    {
        $body = [
            'username' => $username,
            'password' => $password,
        ];
        if ($scode) {
            $body['scode'] = $scode;
        }
        return HttpService::send()->post('User/login', $body);
    }


    /**
     * 站点安装
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  string   $title
     * @param  string   $domain
     * @param  string   $ip
     * @return Response
     */
    public static function installSite(string $title,string $domain)
    {
        $data = [
            'title'     => $title,
            'domain'    => $domain
        ];
        return HttpService::send()->post('Site/install', $data);
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
     * 获取用户账单记录
     *
     * @param  array                       $data
     * @return \yzh52521\EasyHttp\Response
     */
    public static function bill(array $data = []): Response
    {
        return HttpService::send()->get('User/getUserBill',$data);
    }

    /**
     * 获取应用插件列表
     * @param array $query
     * @return Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function list(array $query): Response
    {
        $systemInfo= SystemInfo::info();
        $query = array_merge([
            'page'              => 1,
            'limit'             => 20,
            'sass_version'      => $systemInfo['system_version']
        ], $query);
        return HttpService::send()->get('Plugin/list', $query);
    }

    /**
     * 获取应用插件详情
     * @param string|null $name
     * @param string|null $version
     * @return Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function detail(string|null $name, string|null $version): Response
    {
        $systemInfo = SystemInfo::info();
        $installed_version = PluginLogic::getPluginVersion($name);
        $query = [
            'name'              => $name,
            'version'           => $version,
            'local_version'     => $installed_version,
            'sass_version'      => $systemInfo['system_version']
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
    public static function buyApp(string|null $name, string|null $version): Response
    {
        $systemInfo = SystemInfo::info();
        $query = [
            'name'              => $name,
            'version'           => $version,
            'sass_version'      => $systemInfo['system_version']
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
        $query = [
            'order_no'      => $order_no
        ];
        return HttpService::send()->get('Order/unifiedOrder', $query);
    }

    /**
     * 获取下载KEY
     * @param string|null $name
     * @param string|null $version
     * @return Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function getDownKey(string|null $name, string|null $version): Response
    {
        $systemInfo = SystemInfo::info();
        $query = [
            'name'              => $name,
            'version'           => $version,
            'sass_version'      => $systemInfo['system_version']
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
    public static function getZip(string $key): Response
    {
        $query = [
            'key' => $key
        ];
        return HttpService::send()->get('Plugin/getZip', $query);
    }
}