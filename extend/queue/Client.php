<?php

namespace queue;

use think\cache\driver\Redis;

class Client
{
    public static function send($queue, $data)
    {
        $options = config('cache.stores.redis');
        $redis = (new Redis($options))->handler();
        $redis->lpush(strtoupper($queue), json_encode($data));
        $redis->close();
    }
}
