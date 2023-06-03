<?php

use app\admin\service\kfcloud\CloudService;

class Validated
{
    # 检测默认端口是否被占用
    public static function validatePort(int $port)
    {
        $host = 'localhost';
        $fp = @fsockopen($host, $port, $errno, $errstr, 3);
        if (!$fp) {
            return false;
        }
        fclose($fp);
        return true;
    }

    # 验证宝塔验证
    public static function validateBt(array $data)
    {
        if (!isset($data['panel_url']) || empty($data['panel_url'])) {
            throw new Exception('请输入宝塔面板地址');
        }
        // $preg = "/http[s]?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is";
        // if (!preg_match($preg, $data['panel_url'])) {
        //     throw new Exception('请输入正确的面板地址');
        // }
        if (filter_var($data['panel_url'], FILTER_VALIDATE_URL) === false) {
            throw new Exception('请输入正确的面板地址');
        }
        if (!isset($data['panel_key']) || empty($data['panel_key'])) {
            throw new Exception('请输入宝塔面板密钥');
        }
        $bt = new BtPanel($data['panel_url'], $data['panel_key']);
        // 验证守护进程是否安装
        $softList = [];
        for ($i=0; $i < 5; $i++) {
            $response = $bt->getSoftList(['p' =>$i]);
            if (!isset($response['list']['data'])) {
                throw new Exception('获取守护进程插件失败');
            }
            if (empty($response['list']['data'])) {
                continue;
            }
            $softList = array_merge($softList, $response['list']['data']);
        }
        $names = [];
        foreach ($softList as $value) {
            $names[] = $value['name'];
        }
        if (!in_array('supervisor', $names)) {
            throw new Exception('请安装宝塔插件【进程守护管理器】');
        }
    }

    # 验证云服务
    public static function validateCloud(array $data)
    {
        if (!isset($data['username']) || empty($data['username'])) {
            throw new Exception('请输入云服务账号');
        }
        if (!isset($data['password']) || empty($data['password'])) {
            throw new Exception('请输入云服务账号');
        }
        $response = Request::login($data['username'], $data['password']);
        if (!$response) {
            throw new Exception('云服务请求失败');
        }
        if (!isset($response['code'])) {
            throw new Exception('云服务接口错误');
        }
        if ($response['code'] !== 200) {
            throw new Exception($response['msg'], $response['code']);
        }
        // 缓存Redis
        $redis = RedisMgr::connect();
        $loginStatus = $redis->set(CloudService::$loginToken, $response['data']['token']);
        if (!$loginStatus) {
            throw new Exception('登录云服务失败');
        }
        return $data;
    }

    # 验证站点
    public static function validateSite(array $data)
    {
        if (!isset($data['web_name']) || empty($data['web_name'])) {
            throw new Exception('请输入站点名称');
        }
        if (!isset($data['web_url']) || empty($data['web_url'])) {
            throw new Exception('请输入站点域名');
        }
        if (!filter_var($data['web_url'], FILTER_VALIDATE_URL)) {
            throw new Exception('请输入正确的域名地址');
        }
        if (!isset($data['username']) || empty($data['username'])) {
            throw new Exception('请输入站点管理员账号');
        }
        if (!isset($data['password']) || empty($data['password'])) {
            throw new Exception('请输入站点管理员密码');
        }
        return $data;
    }
}
