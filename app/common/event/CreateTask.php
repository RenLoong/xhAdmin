<?php
declare (strict_types = 1);
namespace app\common\event;
use think\facade\Log;
use Swoole\Timer;
use app\common\utils\SwooleUtil;
class CreateTask
{
    public function handle($event)
    {
        try {
            $task=SwooleUtil::getTask();
            if (empty($task)) {
                return;
            }
            foreach ($task as $key => $value) {
                $time = 1;
                if (!empty($value['time'])) {
                    $time = abs((int)$value['time']);
                }
                Timer::tick($time*1000, function ()use($value) {
                    try {
                        if (!empty($value['package'])) {
                            require_once $value['package'];
                        }
                        $class = new $value['class'];
                        $class->{$value['handler']}();
                    } catch (\Throwable $th) {
                        Log::error("{$value['plugin']} task error：{$th->getMessage()}，file：{$th->getFile()}:{$th->getLine()}");
                    }
                });
            }
            Log::info('create task start：'. date('Y-m-d H:i:s'));
        } catch (\Throwable $th) {
            Log::error("create task error：{$th->getMessage()}，file：{$th->getFile()}:{$th->getLine()}");
        }
    }
}