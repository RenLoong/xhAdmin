<?php

namespace app\enum;

use app\Enum;

/**
 * 权限菜单-是否接口
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-11
 */
class AuthRuleIsApi extends Enum
{
    const NO = [
        'text'      => '否',
        'value'     => '0',
    ];
    const YES = [
        'text'      => '是',
        'value'     => '1',
    ];
}
