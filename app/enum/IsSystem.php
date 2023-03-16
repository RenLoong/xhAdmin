<?php

namespace app\enum;

use app\Enum;

class IsSystem extends Enum
{
    // 非系统
    const APP_SYSTEM_NO = [
        'text'      => '非系统',
        'value'     => '0'
    ];
    // 系统参数
    const APP_SYSTEM_YES = [
        'text'      => '系统',
        'value'     => '1'
    ];
}
