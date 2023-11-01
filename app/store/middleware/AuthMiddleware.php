<?php

namespace app\store\middleware;
use Closure;
use loong\oauth\facade\Auth;
use support\Request;
use think\facade\Session;

/**
 * 权限中间件
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
class AuthMiddleware
{
    /**
     * 业务处理
     * @param \support\Request $request
     * @param callable $handler
     * @return mixed
     * @author John
     */
    public function handle(Request $request, Closure $next)
    {
        $request->token = null;
        $request->user = null;
        try {
            self::canAccess($request);
        } catch (\Throwable $e) {
            return json(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }
        $response = $next($request);
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
    private function canAccess(Request $request): bool
    {
        $control  = '';
        $action  = '';
        $pathinfo = explode('/',$request->pathinfo());
        if (isset($pathinfo[0])) {
            $control = $pathinfo[0];
        }
        if (isset($pathinfo[1])) {
            $action = $pathinfo[1];
        }
        // 无控制器地址
        if (!$control) {
            return true;
        }
        // 获取控制器鉴权信息
        $class = app()->getNamespace() . '\\controller\\' . $control . 'Controller';
        $class = new \ReflectionClass($class);
        $properties = $class->getDefaultProperties();
        $noNeedLogin = $properties['noNeedLogin'] ?? [];

        // 不需要登录
        if (in_array($action, $noNeedLogin)) {
            return true;
        }
        // 获取登录信息
        $authorization = $request->header('Authorization', '');
        if (empty($authorization)) {
            throw new \Exception('请先登录渠道账号', 12000);
        }
        // 获取用户信息
        $token      = str_replace('Bearer ', '', $authorization);
        $user       = Auth::decrypt($token);
        if (!$user) {
            throw new \Exception('登录已过期，请重新登录', 12000);
        }
        $request->token = $authorization;
        $request->user = $user;
        # 验证渠道状态
        if ($user['status'] === '10') {
            throw new \Exception('该渠道已被禁用，请联系管理员', 12000);
        }
        return true;
    }
}
