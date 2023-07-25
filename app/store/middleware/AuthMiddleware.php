<?php

namespace app\store\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * 权限中间件
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * 逻辑处理
     * @param \Webman\Http\Request $request
     * @param callable $handler
     * @return \Webman\Http\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
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
        if (!self::canAccess($controller, $action, $msg, $code)) {
            $response = json(['code' => $code, 'msg' => $msg]);
        } else {
            $response = $request->method() == 'OPTIONS' ? response('', 204) : $handler($request);
        }
        return $response;
    }

    /**
     * 检测是否有权限
     *
     * @param string $control
     * @param string $action
     * @param string $msg
     * @param integer $code
     * @return boolean
     */
    private static function canAccess(string $control, string $action, string &$msg, int &$code): bool
    {
        // 无控制器地址
        if (!$control) {
            return true;
        }
        // 获取控制器鉴权信息
        $class = new \ReflectionClass($control);
        $properties = $class->getDefaultProperties();
        $noNeedLogin = $properties['noNeedLogin'] ?? [];

        // 不需要登录
        if (in_array($action, $noNeedLogin)) {
            return true;
        }
        // 获取登录信息
        $admin = hp_admin_id('hp_store');
        if (!$admin) {
            // 10000 未登录固定的返回码
            $code = 12000;
            $msg = '请先登录租户';
            return false;
        }
        return true;
    }
}