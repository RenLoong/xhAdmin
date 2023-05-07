<?php

namespace app\admin\service\kfcloud;

use think\facade\Cache;
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
    // host
    public static $host = 'http://server8.kaifa.cc/api/';

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
        $token = Cache::has(CloudService::$loginToken) ? Cache::get(CloudService::$loginToken) : request()->sessionId();
        return Http::withHost(self::$host)->withHeaders(
            [
                'Authorization'    => $token,
                'X-Requested-With' => 'XMLHttpRequest',
            ]
        );
    }
}