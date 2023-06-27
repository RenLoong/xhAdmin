<?php

namespace app\exception;

use Throwable;
use Webman\Exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;

/**
 * 异常处理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-22
 */
class Handler extends ExceptionHandler
{
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render(Request $request, Throwable $exception): Response
    {
        if ($request->expectsJson()) {
            $code = $exception->getCode();
            $json = [
                'code' => $code ?: 500,
                'msg'  => $exception->getMessage(),
            ];
            // 调试环境错误
            if ($this->debug) {
                $json['trace']['line'] = $exception->getLine();
                $json['trace']['file'] = $exception->getFile();
            }
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }
        return new Response(200, [], $exception->getMessage());
    }
}