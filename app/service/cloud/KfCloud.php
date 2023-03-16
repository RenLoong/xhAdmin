<?php

namespace app\service\cloud;

use Webman\Route;

/**
 * 云服务第三方服务
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-14
 */
class KfCloud
{
    public $server_url = "";

    /**
     * 注册云服务路由
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-14
     * @return void
     */
    public static function registerRoutes()
    {
        Route::group('/kfadmin/cloud', function () {
            Route::post('/register', '\\app\\service\\cloud\\KfCloudController@register');
            Route::post('/login', '\\app\\service\\cloud\\KfCloudController@login');
            Route::delete('/loginOut', '\\app\\service\\cloud\\KfCloudController@loginOut');
            Route::get('/user', '\\app\\service\\cloud\\KfCloudController@user');
        });
    }

    /**
     * 获取远程服务端插件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-15
     * @return void
     */
    public static function getRemotePlugins()
    {
    }

    /**
     * 获取本地插件列表
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-15
     * @return array
     */
    public static function getLocalPlugins(): array
    {
        clearstatcache();
        $data = [];
        $plugin_names = array_diff(scandir(base_path() . '/plugin/'), array('.', '..')) ?: [];
        foreach ($plugin_names as $plugin_name) {
            if (is_dir(base_path() . "/plugin/$plugin_name") && $config = self::getPluginAppConfig($plugin_name)) {
                $data[] = array_merge([
                    'name'      => $plugin_name,
                ], $config);
            }
        }
        return $data;
    }

    /**
     * 获取本地插件版本
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-15
     * @param  type       $name
     * @return array|null
     */
    public static function getPluginAppConfig($name): array|null
    {
        if (!is_file($file = base_path() . "/plugin/{$name}/config/app.php")) {
            return null;
        }
        $config = include $file;
        return $config ?? null;
    }
}
