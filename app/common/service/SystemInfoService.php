<?php

namespace app\common\service;

use Exception;

/**
 * 系统信息配置
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-12
 */
class SystemInfoService
{
    // 系统名称
    public static $system_name = 'XHAdmin';
    // 框架文档
    public static $system_doc = 'http://doc.kfadmin.net/kfadmin-doc/';
    // 企业名称
    public static $about_name = '贵州猿创科技有限公司';
    // 生态
    public static $ecology = [
        [
            'name' => '使用文档',
            'url'  => 'https://www.kancloud.cn/me_coder/kfadmin/3169580',
        ],
        [
            'name' => '在线社区',
            'url'  => 'http://bbs.kfadmin.net',
        ],
        [
            'name' => '微信群',
            'url'  => 'http://www.kfadmin.net/wx',
        ],
    ];
    # 咨询微信
    public static $wechat = '18786709420（微信同号）';
    # 微信链接
    public static $wechat_url = '';
    # 微信二维码地址
    public static $wechat_qrcode_url = '';

    /**
     * 获取系统信息
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-12
     */
    public static function info()
    {
        $data                = [
            'about_name'            => '',
            'system_name'           => '',
            'system_version_name'   => '',
            'system_version'        => '',
            'ecology'               => self::$ecology,
            'service_wx'            => self::$wechat,
            'wechat_url'            => self::$wechat_url,
            'wechat_qrcode_url'     => self::$wechat_qrcode_url,
            'site_encrypt'          => '',
            'privatekey'            => '',
        ];
        $versionData                    = self::version();
        $data['system_version_name']    = $versionData['version_name'];
        $data['system_version']         = $versionData['version'];
        # 获取新版授权文件
        $authFilePath = config_path() . 'authorization.json';
        if (!file_exists($authFilePath)) {
            # 获取旧版本授权文件
            $tokenFilePath = root_path().'token.pem';
            $authFilePath = root_path().'private_key.pem';
            if (file_exists($tokenFilePath) && file_exists($authFilePath)) {
                $data['site_encrypt'] = file_get_contents($tokenFilePath);
                $data['privatekey'] = file_get_contents($authFilePath);
            }
            $data['about_name']             = self::$about_name;
            $data['system_name']            = self::$system_name;
        } else {
            $authData = json_decode(file_get_contents($authFilePath),true);
            $data     = array_merge($data, $authData);
            $data['system_name'] = $data['name'];
            $data['about_name'] = $data['copyright'];
        }
        return $data;
    }
    
    /**
     * 获取版本信息
     * @param string $name
     * @throws \Exception
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function version(string $name = '')
    {
        $packPath            = root_path().'config/version.json';
        if (!file_exists($packPath)) {
            $packPath       = root_path().'version.json';
        }
        if (!file_exists($packPath)) {
            throw new Exception('框架版本出错');
        }
        $content             = file_get_contents($packPath);
        $packInfo            = json_decode($content, true);
        if ($name && !isset($packInfo[$name])) {
            throw new Exception('框架版本获取错误');
        }
        if (!$name) {
            return $packInfo;
        }
        return $packInfo[$name];
    }
}