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
        if (!isset($config['default'])) {
            throw new Exception('获取redis配置失败');
        }
        $config = $config['default'];
        $redis  = new \Redis;
        # 设置服务地址与端口
        $redis->connect($config['host'], $config['port']);
        # 设置redis密码
        if (isset($config['password']) && $config['password']) {
            $redis->auth($config['password']);
        }
        # 设置选择的库
        if (isset($config['database'])) {
            $redis->select($config['database']);
        }
        # 返回链接实例
        return $redis;
    }
}
