<?php
namespace app\admin\service\kfcloud;

use support\Redis;
use yzh52521\EasyHttp\Http;
use yzh52521\EasyHttp\Request;

/**
 * KFAdmin云服务
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-22
 */
class HttpService
{
    // 服务端接口地址
    // public static $url = 'https://www.kfadmin.net/api/';
    // 临时使用
    public static $url = 'http://122.228.85.130:39150/api/';

    /**
     * 实例请求
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-22
     * @return Request
     */
    public static function send(): Request
    {
        $token = Redis::get(CloudService::$loginToken) ?? request()->sessionId();
        if (!preg_match("/cli/i", php_sapi_name())) {
            $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['SERVER_NAME'] : '';
            $host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
            $fullUrl = $scheme && $host ? "{$scheme}://{$host}" : '';
        }else{
            $fullUrl = request()->fullUrl();
        }
        return Http::withHost(self::$url)->withHeaders(
            [
                'Authorization'    => $token,
                'Referer'          => $fullUrl,
                'X-Requested-With' => 'XMLHttpRequest',
            ]
        );
    }
}