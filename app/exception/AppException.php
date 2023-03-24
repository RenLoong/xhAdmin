<?php

namespace app\exception;

use Exception;
use Webman\Http\Request;
use Webman\Http\Response;
use function json_encode;

/**
 * 应用异常渲染类
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-22
 */
class AppException extends Exception
{
    public function render(Request $request): ?Response
    {
        if ($request->expectsJson()) {
            $code = $this->getCode();
            $json = ['code' => $code ?: 500, 'msg' => $this->getMessage()];
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }
        return new Response(200, [], $this->getMessage());
    }
}
