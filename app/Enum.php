<?php

namespace app;

use app\utils\EnumBaseic;
use Exception;

abstract class Enum extends EnumBaseic
{
    /**
     * 获取枚举值
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  string $key
     * @return void
     */
    public static function getText(string $key)
    {
        $data = self::parseData();
        if (!isset($data[$key])) {
            throw new Exception('获取枚举错误');
        }
        return $data[$key];
    }

    /**
     * 获取枚举值列
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @return array
     */
    public static function getEnumValues(): array
    {
        $data = self::toArray();
        $list = [];
        foreach ($data as $value) {
            $list[] = $value['value'];
        }
        return $list;
    }

    /**
     * 获取组装完成数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @return array
     */
    public static function parseData(): array
    {
        $data = self::toArray();
        $list = [];
        foreach ($data as $value) {
            $list[$value['value']] = $value;
        }
        return $list;
    }

    /**
     * 获取元素所需数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @return array
     */
    public static function getOptions(): array
    {
        $data = self::toArray();
        $list = [];
        foreach ($data as $value) {
            $list[] = [
                'label'     => $value['text'],
                'value'     => $value['value'],
            ];
        }
        return $list;
    }

    /**
     * 获取原始数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @return array
     */
    public static function getData(): array
    {
        return self::toArray();
    }
}
