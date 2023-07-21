<?php

namespace app\common\service;

use Workerman\Timer;
use Workerman\Worker;

/**
 * 框架服务类
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class FrameworkService
{
    /**
     * 停止子进程
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function reloadSubprocess()
    {
        if (function_exists('posix_kill')) {
            try {
                console_log("停止子进程---执行成功");
                # 重启子进程
                posix_kill(posix_getppid(), SIGUSR1);
                return true;
            } catch (\Throwable $e) {
                p("停止框架失败---{$e->getMessage()}");
            }
        } else {
            Timer::add(1, function () {
                console_log("停止子进程---执行成功");
                Worker::stopAll();
            });
        }
        return false;
    }
    
    /**
     * 重启主进程
     * @return bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function reloadWebman()
    {
        if (function_exists('posix_kill')) {
            try {
                console_log("停止主进程---执行成功");
                # 重启主进程
                posix_kill(posix_getppid(), SIGINT);
                return true;
            } catch (\Throwable $e) {
                p("停止主进程失败---{$e->getMessage()}");
            }
        } else {
            Timer::add(1, function () {
                console_log("停止主进程---执行成功");
                Worker::stopAll();
            });
        }
        return false;
    }
}
