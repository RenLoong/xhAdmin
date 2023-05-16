<?php
namespace app\utils;

use Workerman\Timer;
use Workerman\Worker;

/**
 * 工具函数
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-08
 */
class Utils
{
    /**
     * 重启Webman
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-08
     */
    public static function reloadWebman()
    {
        if (function_exists('posix_kill')) {
            try {
                posix_kill(posix_getppid(), SIGUSR1);
                return true;
            } catch (\Throwable $e) {
                p($e->getMessage());
            }
        }
        else {
            Timer::add(1, function () {
                Worker::stopAll();
            });
        }
        return false;
    }
}