<?php

namespace app\admin\middleware;

use app\admin\service\VueRoutesService;
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
     * 中间件处理
     * @param \Webman\Http\Request $request
     * @param callable $handler
     * @return \Webman\Http\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
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
        $noNeedAuth = $properties['noNeedAuth'] ?? [];

        // 不需要登录
        if (in_array($action, $noNeedLogin)) {
            return true;
        }
        // 获取登录信息
        $admin = hp_admin('hp_admin');
        if (!$admin) {
            // 12000 未登录固定的返回码
            $code = 12000;
            $msg = '请先登录';
            return false;
        }

        // 不需要鉴权
        if (in_array($action, $noNeedAuth)) {
            return true;
        }
        // 系统级部门，不需要鉴权
        if ($admin['role']['is_system'] == '1') {
            return true;
        }
        // 获取角色规则
        $rule = VueRoutesService::getAdminRoleColumn($admin);
        // 检测是否有操作权限
        $ctrlName = str_replace('Controller', '', basename(str_replace('\\', '/', $control)));
        $path = "{$ctrlName}/{$action}";
        if (!in_array($path, $rule)) {
            $code = 404;
            $msg = '没有该操作权限';
            return false;
        }
        return true;
    }
}
