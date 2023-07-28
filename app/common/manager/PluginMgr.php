<?php
namespace app\common\manager;

use app\common\service\SystemInfoService;
use Exception;
use YcOpen\CloudService\Cloud;
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
        $req = new PluginRequest;
        $req->detail();
        $req->name = $name;
        $req->version = $installedVersion;
        $req->saas_version = $systemInfo['system_version'];
        $req->local_version = $installedVersion;
        $cloud = new Cloud($req);
        $data = $cloud->send()->toArray();
        $pluginDetail       = empty($data) ? [] : $data;
        if (empty($pluginDetail)) {
            throw new Exception('云端应用数据错误');
        }
        $pluginSaasVersion = $pluginDetail['local_find']['saas_version'];
        if ($systemInfo['system_version'] > $pluginSaasVersion) {
            return false;
        }
        return true;
    }

    /**
     * 获取本地插件版本
     * @param mixed $name
     * @return mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function getPluginVersion($name)
    {
        if (!is_dir(base_path("/plugin/{$name}"))) {
            return 1;
        }
        $json = base_path("/plugin/{$name}/version.json");
        if (!is_file($json)) {
            return 1;
        }
        $config = json_decode(file_get_contents($json), true);
        return isset($config['version']) ? $config['version'] : 1;
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
        if (!is_dir(base_path("/plugin/{$name}"))) {
            return $version;
        }
        $json = base_path("/plugin/{$name}/version.json");
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
        if (!is_dir(base_path('/plugin/'))) {
            return [];
        }
        clearstatcache();
        $installed = [];
        $plugin_names = array_diff(scandir(base_path('/plugin/')), array('.', '..')) ?: [];
        foreach ($plugin_names as $plugin_name) {
            if (is_dir(base_path("/plugin/{$plugin_name}")) && $version = self::getPluginVersion($plugin_name)) {
                $installed[$plugin_name] = $version;
            }
        }
        return $installed;
    }
}