<?php

namespace app\admin\service\kfcloud;

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
        $packPath            = base_path('/composer.json');
        $content             = file_get_contents($packPath);
        $packInfo            = json_decode($content, true);
        $system_name         = self::$system_name;
        $system_version_name = isset($packInfo['version']) ? $packInfo['version'] : '';
        $system_version      = 0;
        if ($system_version_name) {
            $system_version = str_replace(['v', '.', ' '], '', trim($system_version_name));
        }
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