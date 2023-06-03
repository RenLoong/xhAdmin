<?php

/**
 * @title 数据库管理服务
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class RedisMgr
{
    /**
     * 连接Redis数据库
     *
     * @return \Redis
     */
    public static function connect(): \Redis
    {
        $config = require ROOT_PATH . "/config/redis.php";
        $config = isset($config['default']) ? $config['default'] : $config;
        $redis  = new \Redis;
        $redis->connect($config['host'], $config['port']);
        return $redis;
    }
}
