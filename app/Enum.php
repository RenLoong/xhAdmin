<?php

namespace app;

use app\utils\EnumBaseic;
use Exception;

/**
 * 枚举基类
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
abstract class Enum extends EnumBaseic
{
    /**
     * 获取枚举值
     * @param string $key
     * @throws Exception
     * @return mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
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
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
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
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
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
     * 别名解析
     * @param mixed $field
     * @return array<array>
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function parseAlias(string $field)
    {
        $data = self::toArray();
        $list = [];
        foreach ($data as $value) {
            $list[$value['value']] = [
                $field      => $value['text'],
                'value'     => $value['value']
            ];
        }
        return $list;
    }

    /**
     * 获取选择组件字典
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function dictOptions()
    {
        $data = self::toArray();
        $list = [];
        foreach ($data as $value) {
            $list[$value['value']] = $value['text'];
        }
        return $list;
    }

    /**
     * 获取元素所需数据
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
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
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public static function getData(): array
    {
        return self::toArray();
    }
}
