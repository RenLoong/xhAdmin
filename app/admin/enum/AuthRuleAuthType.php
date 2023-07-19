<?php

namespace app\admin\enum;

use app\Enum;

/**
 * 是否接口
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
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
