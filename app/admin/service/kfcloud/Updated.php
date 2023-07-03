<?php

namespace app\admin\service\kfcloud;

use yzh52521\EasyHttp\Response;

/**
 * 系统更新服务
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-15
 */
class Updated
{
    /**
     * 检测更新
     * @param int $version
     * @param string $version_name
     * @return Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-15
     */
    public static function verify(int $version, string $version_name)
    {
        $query = [
            'version' => $version,
            'version_name' => $version_name,
        ];
        return HttpService::send()->get('SystemUpdate/verify', $query);
    }

    /**
     * 获取系统更新信息
     * @param int $version
     * @param string $version_name
     * @return Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-15
     */
    public static function systemUpdateInfo(int $version, string $version_name)
    {
        $query = [
            'version' => $version,
            'version_name' => $version_name,
        ];
        return HttpService::send()->get('SystemUpdate/detail', $query);
    }


    /**
     * 获取系统下载KEY
     * @param int $version
     * @return Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-15
     */
    public static function getSystemDownKey(int $version)
    {
        $query = [
            'target_version' => $version,
        ];
        return HttpService::send()->get('SystemUpdate/getKey', $query);
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
        return HttpService::send()->get('SystemUpdate/getZip', $query);
    }
}