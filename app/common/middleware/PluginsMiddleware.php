<?php

namespace app\common\middleware;

use app\common\exception\ErrorException;
use app\common\manager\StoreAppMgr;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * 应用插件中间件
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class PluginsMiddleware implements MiddlewareInterface
{
    /**
     * 中间件处理
     * @param \Webman\Http\Request $request
     * @param callable $next
     * @return \Webman\Http\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function process(Request $request, callable $next): Response
    {
        # 判断是否为资源文件
        if (strpos($request->path(), '/assets/')) {
            return $next($request);
        }
        # 应用名称
        $pluginName = $request->plugin;
        # 后台地址
        $appAdminPath = "/app/{$pluginName}/admin";
        $path = $request->path();
        if (in_array($path, ["/app/{$pluginName}", "/app/{$pluginName}/", $appAdminPath, $appAdminPath . '/'])) {
            return $next($request);
        }
        // 获得请求路径
        $control = $request->controller;
        $action = $request->action;
        // 无控制器地址
        if (!$control) {
            throw new ErrorException('控制器错误');
        }
        // 获取控制器鉴权信息
        $class = new \ReflectionClass($control);
        $properties = $class->getDefaultProperties();
        $noIdentifyApp = $properties['noIdentifyApp'] ?? [];
        // 不需要鉴权项目
        if (in_array($action, $noIdentifyApp)) {
            return $next($request);
        }
        # 应用ID
        $appid = $request->header('appid', '');
        if (empty($appid)) {
            $appid = $request->input('appid', '');
        }
        /**
         * 实例响应结果
         * @var Response $response
         */
        if (empty($appid)) {
            throw new ErrorException('缺少应用ID');
        }
        try {
            # 获取应用信息
            $where = [
                'id'        => $appid,
            ];
            $appModel = StoreAppMgr::detail($where);
        } catch (\Throwable $e) {
            throw new ErrorException($e->getMessage());
        }
        # 验证是否被冻结
        if ($appModel['status'] != '20') {
            throw new ErrorException('该应用已被冻结');
        }
        # 验证应用授权是否已过期
        # 验证代理是否已过期
        # 验证项目是否已过期

        # 返回结果集
        return $next($request);
    }
}
