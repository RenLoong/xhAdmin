<?php

namespace app\enum;

use app\Enum;

/**
 * 权限显示状态
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-07
 */
class AuthRuleShow extends Enum
{
    const SHOW_NO = [
        'text'      => '隐藏',
        'value'     => '0',
    ];
    const SHOW_YES = [
        'text'      => '显示',
        'value'     => '1',
    ];
}
