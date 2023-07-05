<?php
namespace YcOpen\CloudService;
class Redis
{
    protected $redis;
    public function __construct()
    {
        if (class_exists('\Redis')) {
            $config=include base_path('/config/redis.php');
            $redis=new \Redis;
            $redis->connect($config['default']['host'],$config['default']['port']);
            if($config['default']['password']){
                $redis->auth($config['default']['password']);
            }
            if($config['default']['database']){
                $redis->select($config['default']['database']);
            }
            $this->redis=$redis;
            return ;
        }
        throw new \Exception('请安装redis扩展');
    }
    public function __call($name, $arguments)
    {
        return $this->redis->$name(...$arguments);
    }
    public static function __callStatic($name, $arguments)
    {
        $redis=new self;
        return $redis->$name(...$arguments);
    }
}