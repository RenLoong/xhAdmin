<?php

namespace app\enum;

use app\Enum;

/**
 * 权限是否接口
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-07
 */
class AuthRuleAuthType extends Enum
{
    const API_NO = [
        'text'      => '否',
        'value'     => '0',
    ];
    const SHOW_YES = [
        'text'      => '是',
        'value'     => '1',
    ];
}
