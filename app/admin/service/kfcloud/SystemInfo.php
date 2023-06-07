<?php

namespace app\admin\service\kfcloud;

use Exception;

/**
 * 系统信息配置
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-12
 */
class SystemInfo
{
    // 系统名称
    public static $system_name = 'KFAdmin';
    // 框架文档
    public static $system_doc = 'https://www.kfadmin.net/dev';
    // 企业名称
    public static $about_name = '贵州猿创科技有限公司';
    // 生态
    public static $ecology = [
        [
            'name' => '使用文档',
            'url'  => 'https://www.kfadmin.net/doc',
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
    // 咨询微信
    public static $service_wx = '18786709420（微信同号）';

    /**
     * 获取系统信息
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-12
     */
    public static function info()
    {
        $packPath            = base_path('/version.json');
        if (!file_exists($packPath)) {
            throw new Exception('框架版本出错');
        }
        $content             = file_get_contents($packPath);
        $packInfo            = json_decode($content, true);
        if (!isset($packInfo['version_name'])) {
            throw new Exception('获取版本名称错误');
        }
        if (!isset($packInfo['version'])) {
            throw new Exception('获取版本号错误');
        }
        $system_name         = self::$system_name;
        $system_version_name = isset($packInfo['version_name']) ? $packInfo['version_name'] : '';
        $system_version = isset($packInfo['version']) ? $packInfo['version'] : '';
        $freamVersion        = [
            'name' => "{$system_name} {$system_version_name}",
            'url'  => self::$system_doc,
        ];
        $data                = [
            'about_name'          => self::$about_name,
            'fream_version'       => [$freamVersion],
            'system_name'         => $system_name,
            'system_version_name' => $system_version_name,
            'system_version'      => (int)$system_version,
            'ecology'             => self::$ecology,
            'service_wx'          => self::$service_wx,
        ];
        return $data;
    }
}