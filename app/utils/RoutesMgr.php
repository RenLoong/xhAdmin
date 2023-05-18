<?php

namespace app\utils;

use app\admin\logic\PluginLogic;
use Exception;
use app\admin\middleware\AccessMiddleware;
use app\admin\model\SystemAuthRule;
use Webman\Route;
use support\Request;

/**
 * 路由管理器
 * 仅用于后端路由管理策略
 * 
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-11
 */
class RoutesMgr
{
    /**
     * 初始化路由
     *
     * @return void
     */
    public static function init()
    {
        // 注册已安装路由
        if (file_exists(base_path() . '/.env')) {
            // 注册基础路由
            self::installed();
            // 映射模块名
            $modules = config('admin',[]);
            self::installAdminView($modules);
            // 注册插件后台视图
            self::installPluginAdminView();
        }
    }

    /**
     * 注册已安装插件后台视图
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    private static function installPluginAdminView()
    {
        $plugins    = PluginLogic::getLocalPlugins();
        if (!$plugins) {
            return;
        }
        foreach ($plugins as $plugin_name => $version) {
            $configPath = base_path("/plugin/{$plugin_name}/config/admin.php");
            if (file_exists($configPath)) {
                $data = require $configPath;
                foreach ($data as $key => $value) {
                    $adminName  = $value ? "/{$value}" : '';
                    $data[$key] = "app/{$plugin_name}{$adminName}";
                }
                // 注册插件后台视图
                self::installAdminView($data);
            }
        }
    }

    /**
     * 注册已安装后台视图
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    private static function installAdminView(array $modules)
    {
        if (!$modules) {
            return;
        }
        // gzip开启对应文件返回类型
        $mimeContentType = [
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'css'  => 'text/css',
        ];
        // 批量注册静态模块路由
        foreach ($modules as $moduleAlias) {
            Route::group("/{$moduleAlias}", function () use ($mimeContentType) {
                // 注册视图路由
                $viewPath = str_replace('\\', '/', base_path('/view'));
                // 注册访问地址
                Route::any('/', function (Request $request, $path = '') use ($viewPath) {
                    if (strpos($path, '..') !== false) {
                        return response('<h1>400 Bad Request</h1>', 400);
                    }
                    $file = "{$viewPath}/index.html";
                    if (!is_file($file)) {
                        return response('<h1>404 Not Found</h1>', 404);
                    }
                    return response()->withFile($file);
                });
                // 注册静态资源
                Route::any(
                    '/assets/[{path:.+}]',
                    function (Request $request, $path = '') use ($viewPath, $mimeContentType) {
                        if (strpos($path, '..') !== false) {
                            return response('<h1>400 Bad Request</h1>', 400);
                        }
                        $file = "{$viewPath}/assets/{$path}";
                        if (!is_file($file)) {
                            return response('<h1>404 Not Found</h1>', 404);
                        }
                        $response = response();
                        // 开启GZIP压缩
                        $gzipFile = $file . '.gz';
                        if (file_exists($gzipFile)) {
                            $extension         = pathinfo($file, PATHINFO_EXTENSION);
                            $mime_content_type = 'text/plain';
                            if (isset($mimeContentType[$extension])) {
                                $mime_content_type = $mimeContentType[$extension];
                            }
                            $file = $gzipFile;
                            $response->withHeaders([
                                'Content-Type'     => $mime_content_type,
                                'Content-Encoding' => 'gzip'
                            ]);
                        }
                        return $response->withFile($file);
                    }
                );
            });
        }
    }

    /**
     * 注册已安装路由
     * @throws Exception
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-12
     */
    private static function installed()
    {
        $order = [
            'sort'      => 'asc',
            'id'        => 'asc'
        ];
        $routes = SystemAuthRule::where('is_api', '1')
            ->order($order)
            ->select()
            ->toArray();

        // 是否启用控制器后缀
        $_suffix = config('app.controller_suffix');
        // 动态注册所有路由
        Route::group('/', function () use ($routes, $_suffix) {
            foreach ($routes as $value) {
                if (strpos($value['path'], '/') === false) {
                    throw new Exception("路由注册失败,path不符合规则,路由{$value['path']}");
                }
                list($controller, $action) = explode('/', $value['path']);
                $controller                = ucfirst($controller);
                $moduleName                = getModule($value['module']);
                $path                      = "{$moduleName}/{$controller}/{$action}";
                $namespace                 = "{$value['namespace']}{$controller}{$_suffix}@{$action}";
                Route::add($value['method'], $path, $namespace);
            }
        });
    }
}
