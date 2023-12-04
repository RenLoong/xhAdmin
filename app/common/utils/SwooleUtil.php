<?php

namespace app\common\utils;

/**
 * swoole配置工具类
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class SwooleUtil
{
    /**
     * 获取应用的websocket
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function getWebSocket()
    {
        $data = [
            \app\common\service\WebSocketService::class
        ];
        # 扫描配置文件
        $websocket = glob(root_path().'plugin/*/config/websocket.php');
        if (empty($websocket)) {
            return $data;
        }
        foreach ($websocket as $path) {
            $config = require $path;
            if (empty($config) || !is_array($config)) {
                continue;
            }
            foreach ($config as $class) {
                if (!class_exists($class)) {
                    continue;
                }
                $data[] = $class;
            }
        }
        # 去除空值
        $data = array_filter($data);
        # 去除重复
        $data = array_unique($data);
        # 返回数据
        return $data;
    }

    /**
     * 获取应用的队列
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function getQueue()
    {
        $data = [];
        # 扫描配置文件
        $queue = glob(root_path().'plugin/*/config/queue.php');
        if (empty($queue)) {
            return $data;
        }
        foreach ($queue as $path) {
            $config = require $path;
            if (empty($config) || !is_array($config)) {
                continue;
            }
            $data = array_merge($data, $config);
        }
        # 返回数据
        return $data;
    }
}
