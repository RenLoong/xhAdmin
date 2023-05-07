<?php

namespace app\admin\middleware;

use app\admin\utils\Auth;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * 权限中间件
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
class AccessMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        // 从headers中拿去请求用户token
        $authorization = $request->header('Authorization');
        $request->sessionId($authorization);
        // 获得请求路径
        $controller = $request->controller;
        $action = $request->action;
        $msg = '';
        $code = 0;
        // 鉴权检测
        if (!Auth::canAccess($controller, $action, $msg, $code)) {
            $response = json(['code' => $code, 'msg' => $msg]);
        } else {
            $response = $request->method() == 'OPTIONS' ? response('', 204) : $handler($request);
        }
        return $response;
    }
}
