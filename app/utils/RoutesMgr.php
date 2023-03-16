<?php

namespace app\utils;

use Exception;
use app\middleware\AccessMiddleware;
use app\admin\model\SystemAuthRule;
use app\service\cloud\KfCloud;
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
        // 安装完成
        Route::add(['GET', 'POST'], '/install/step5', '\app\install\Install@step5')
            ->middleware([
                AccessMiddleware::class,
            ]);
        // 检测是未安装（注册路由）
        if (file_exists(base_path() . '/.env')) {
            RoutesMgr::installed();
            KfCloud::registerRoutes();
        } else {
            RoutesMgr::install();
        }
    }

    /**
     * 注册已安装路由
     *
     * @return void
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
                $controller = ucfirst($controller);
                $path = "{$value['module']}/{$controller}/{$action}";
                $namespace = "{$value['namespace']}{$controller}{$_suffix}@{$action}";
                Route::add($value['method'], $path, $namespace);
            }
        })->middleware([
            AccessMiddleware::class,
        ]);
        // 所有模块名
        $modules = SystemAuthRule::distinct(true)->column('module');
        // 批量注册模块路由
        foreach ($modules as $value) {
            Route::group("/{$value}", function () {
                // 注册视图路由
                $viewPath = str_replace('\\', '/', dirname(__DIR__)) . '/admin/view';
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
                    function (Request $request, $path = '') use ($viewPath) {
                        if (strpos($path, '..') !== false) {
                            return response('<h1>400 Bad Request</h1>', 400);
                        }
                        $file = "{$viewPath}/assets/{$path}";
                        if (!is_file($file)) {
                            return response('<h1>404 Not Found</h1>', 404);
                        }
                        return response()->withFile($file);
                    }
                );
            });
        }
    }

    /**
     * 注册未安装路由
     *
     * @return void
     */
    private static function install()
    {
        // 安装目录
        $installPath = str_replace('\\', '/', dirname(__DIR__)) . '/install';
        // 跳转安装
        Route::add(['GET'], '/', '\Hangpu8\Admin\install\InstallController@index');
        // 安装业务流程
        Route::group('/install', function () use ($installPath) {
            // 安装首页
            Route::add(['GET'], '/step1', '\Hangpu8\Admin\install\InstallController@step1');
            // 检测环境
            Route::add(['GET'], '/step2', '\Hangpu8\Admin\install\InstallController@step2');
            // 数据库设置
            Route::add(['GET', 'POST'], '/step3', '\Hangpu8\Admin\install\InstallController@step3');
            // 开始安装
            Route::add(['POST'], '/step4', '\Hangpu8\Admin\install\InstallController@step4');
            // 加载视图
            Route::any('/view/', function (Request $request, $path = '') use ($installPath) {
                // 安全检查，避免url里 /../../../password 这样的非法访问
                if (strpos($path, '..') !== false) {
                    return response('<h1>400 Bad Request</h1>', 400);
                }
                $file = "{$installPath}/view/index.html";
                if (!is_file($file)) {
                    return response('<h1>404 Not Found</h1>', 404);
                }
                return response()->file($file);
            });
        })->middleware([
            AccessMiddleware::class
        ]);
        // 静态资源
        Route::any('/hpadmin/[{path:.+}]', function (Request $request, $path = '') use ($installPath) {
            // 安全检查，避免url里 /../../../password 这样的非法访问
            if (strpos($path, '..') !== false) {
                return response('<h1>400 Bad Request</h1>', 400);
            }
            // 文件
            $file = "{$installPath}/view/hpadmin/{$path}";
            if (!is_file($file)) {
                return response('<h1>404 Not Found</h1>', 404);
            }
            return response()->withFile($file);
        });
    }
}
