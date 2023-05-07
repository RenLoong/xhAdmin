<?php

namespace app\store\utils;

/**
 * @title 权限管理
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Auth
{
    /**
     * 检测是否有权限
     *
     * @param string $control
     * @param string $action
     * @param string $msg
     * @param integer $code
     * @return boolean
     */
    public static function canAccess(string $control, string $action, string &$msg, int &$code): bool
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
