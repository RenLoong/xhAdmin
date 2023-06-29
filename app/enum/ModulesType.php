<?php

namespace app\enum;

use app\Enum;

/**
 * 表结构类型
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class ModulesType extends Enum
{
    const DATETIME = [
        'text'      => 'dateTime',
        'value'     => 'dateTime',
    ];
    const INTEGER = [
        'text'      => 'int',
        'value'     => 'integer',
    ];
    const STRING = [
        'text'      => 'varchar',
        'value'     => 'varchar',
    ];
    const TEXT = [
        'text'      => 'text',
        'value'     => 'text',
    ];
}
