<?php

namespace app\common\manager;

use app\common\service\SystemInfoService;
use Exception;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request;
use YcOpen\CloudService\Request\PluginRequest;

class PluginMgr
{
    /**
     * 检测应用对SAAS版本支持
     * @param string $name
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */

    public static function checkPluginSaasVersion(string $name)
    {
        # 获取应用信息
        $systemInfo = SystemInfoService::info();
        # 获取本地应用版本
        $installedVersion = self::getPluginVersion($name);
        # 获取云端应用详情
        $data = Request::Plugin(Request::API_VERSION_V2)->detail([
            'name' => $name,
            'version' => $installedVersion,
            'saas_version' => $systemInfo['system_version'],
            'local_version' => $installedVersion,
        ])->response()->toArray();
        if (empty($data['local_version'])) {
            throw new Exception('云端应用数据错误');
        }
        return $systemInfo['system_version'] >= $data['local_version']['saas_version'];
    }

    /**
     * 获取本地插件版本
     * @param mixed $name
     * @return mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function getPluginVersion($name,$versionName = 'version')
    {
        if (!is_dir(root_path()."/plugin/{$name}")) {
            return 1;
        }
        $json = root_path()."/plugin/{$name}/version.json";
        if (!is_file($json)) {
            return 1;
        }
        $config = json_decode(file_get_contents($json), true);
        return isset($config[$versionName]) ? $config[$versionName] : 1;
    }

    /**
     * 获取应用版本数据
     * @param mixed $name
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getPluginVersionData($name)
    {
        $version = [
            'version'           => 1,
            'version_name'      => '1.0.0'
        ];
        if (!is_dir(root_path()."/plugin/{$name}")) {
            return $version;
        }
        $json = root_path()."/plugin/{$name}/version.json";
        if (!is_file($json)) {
            return $version;
        }
        $config = json_decode(file_get_contents($json), true);
        # 返回数据
        return $config ? $config : $version;
    }

    /**
     * 获取已安装的插件列表
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function getLocalPlugins(): array
    {
        if (!is_dir(root_path().'/plugin/')) {
            return [];
        }
        clearstatcache();
        $installed = [];
        $plugin_names = array_diff(scandir(root_path().'/plugin/'), array('.', '..')) ?: [];
        foreach ($plugin_names as $plugin_name) {
            if (is_dir(root_path()."/plugin/{$plugin_name}") && $version = self::getPluginVersion($plugin_name)) {
                $installed[$plugin_name] = $version;
            }
        }
        return $installed;
    }
}
