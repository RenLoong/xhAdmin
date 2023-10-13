<?php
namespace plugin\ycMini\app\middleware;

use loong\oauth\facade\Auth;
use think\facade\Session;
use think\Request;
use think\Response;

/**
 * 权限中间件
 * @author 贵州猿创科技有限公司
 */
class AuthMiddleware
{
    public function handle(Request $request, callable $handler): Response
    {
        // 鉴权检测
        try {
            $this->canAccess($request);
            $response = $request->method() == 'OPTIONS' ? response('', 204) : $handler($request);
        } catch (\Throwable $th) {
            $response = json(['code' => $th->getCode(), 'msg' => $th->getMessage(), 'file' => $th->getFile()]);
        }
        return $response;
    }
    public function canAccess(Request $request): bool
    {
        $saas_appid = $request->header('Appid') ?? $request->get('appid');
        $request->saas_appid = $saas_appid;
        if (!$request->controller()) {
            return true;
        }
        // 获取控制器鉴权信息
        $namespace = app()->getNamespace();
        $class = new \ReflectionClass("{$namespace}\\{$request->control}");
        $properties = $class->getDefaultProperties();
        $noNeedLogin = $properties['noNeedLogin'] ?? [];
        $noValidate = $properties['noValidate'] ?? [];
        Session::set('RecordSaasAppid', $saas_appid);
        if (in_array($request->action(), $noValidate)) {
            $user = Session::get('filingUser');
            if ($user) {
                $request->uid = $user['id'];
                if ($user['saas_appid'] != $saas_appid) {
                    throw new \Exception("应用ID错误,请重新登录", 12000);
                }
            }
            return true;
        }

        // 不需要登录
        if (in_array($request->action(), $noNeedLogin)) {
            return true;
        }
        // 获取登录信息
        $user = Auth::decrypt($request->header('Authorization'));
        if (!$user) {
            throw new \Exception("获取用户信息失败,请重新登录", 12000);
        }
        if ($user['saas_appid'] != $saas_appid) {
            throw new \Exception("应用ID错误,请重新登录", 12000);
        }
        $request->uid = $user['id'];
        return true;
    }
}