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
        # 应用名称
        $pluginName = $request->plugin;
        # 后台地址
        $appAdminPath = "/app/{$pluginName}/admin";
        # 响应结果集
        $response = $next($request);
        if ($request->path() === $appAdminPath) {
            return $response;
        }
        # 静态文件
        $staticFile = str_replace($appAdminPath, '', $request->path());
        # 视图静态资源
        $viewFilePath = base_path("/view/{$staticFile}");
        # 判断是否存在
        if (file_exists($viewFilePath)) {
            return $response;
        }
        # 应用ID
        $appid = $request->header('appid','');
        if (empty($appid)) {
            $appid = $request->input('appid','');
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
        return $response;
    }
}
