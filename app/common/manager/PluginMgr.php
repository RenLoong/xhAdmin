<?php
namespace app\common\manager;

use Exception;

class PluginMgr
{
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
        clearstatcache();
        $installed = [];
        $plugin_names = array_diff(scandir(base_path('/plugin/')), array('.', '..')) ?: [];
        foreach ($plugin_names as $plugin_name) {
            if (is_dir(base_path("/plugin/{$plugin_name}")) && $version = self::getPluginVersion($plugin_name) > 1) {
                $installed[$plugin_name] = $version;
            }
        }
        return $installed;
    }
}