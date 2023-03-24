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
    public $dontReport = [
        AppException::class,
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render(Request $request, Throwable $exception): Response
    {
        if (($exception instanceof AppException) && ($response = $exception->render($request))) {
            return $response;
        }
        return parent::render($request, $exception);
    }
}
