<?php

use app\admin\service\kfcloud\HttpService;

/**
 * @title HTTP服务
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Request
{
    /**
     * 发送请求
     *
     * @param array $data
     * @return \PDO
     */
    public static function login(string $username, string $password)
    {
        $data    = [
            'username' => $username,
            'password' => $password,
            'scode'    => 'no'
        ];
        $referer = $_SERVER['HTTP_REFERER'];
        $url     = HttpService::$url . 'User/login';
        $headers = [
            'X-Requested-With:XMLHttpRequest',
        ];
        $ch      = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result ? json_decode($result, true) : null;
    }
}
