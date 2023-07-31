<?php

namespace app\common\utils;

use support\Response;

/**
 * @title JSON返回
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
trait Json
{

    /**
     * 返回JSON数据
     *
     * @param mixed $msg
     * @param mixed $code
     * @param array $data
     * @return Response
     */
    public static function json(mixed $msg, mixed $code, array $data = []): Response
    {
        $json['msg'] = $msg;
        $json['code'] = $code;
        $json['data'] = $data;
        return json($json);
    }

    /**
     * 返回成功消息
     *
     * @param string $msg
     * @return Response
     */
    public static function success(mixed $msg): Response
    {
        return self::json($msg, 200);
    }

    /**
     * 返回成功消息带数据
     *
     * @param mixed $msg
     * @param array $data
     * @return Response
     */
    public static function successFul(mixed $msg, array $data): Response
    {
        return self::json($msg, 200, $data);
    }

    /**
     * 返回成功结果
     *
     * @param array $data
     * @return Response
     */
    public static function successRes(array $data): Response
    {
        return self::json('success', 200, $data);
    }

    /**
     * 返回失败消息
     *
     * @param mixed $msg
     * @return Response
     */
    public static function fail(mixed $msg): Response
    {
        return self::json($msg, 404);
    }

    /**
     * 返回失败待状态码消息
     *
     * @param mixed $msg
     * @param mixed $code
     * @return Response
     */
    public static function failFul(mixed $msg, mixed $code): Response
    {
        return self::json($msg, $code);
    }

    /**
     * 返回失败消息并重定向
     * @param mixed $msg
     * @param mixed $url
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public static function failRedirect(mixed $msg,mixed $url)
    {
        return self::json($msg, 302, ['url' => $url]);
    }

    /**
     * 无失败消息直接重定向
     * @param mixed $url
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function redirect($url)
    {
        return self::json('error', 301, ['url' => $url]);
    }
}
