<?php

namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * 全局中间件
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class GlobalsMiddleware implements MiddlewareInterface
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
        // print_r('全局中间件生效');
        /** @var Response $response */
        $response = $next($request);
        return $response;
    }
}
