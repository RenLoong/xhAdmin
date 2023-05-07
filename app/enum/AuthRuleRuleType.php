<?php

namespace app\enum;

use app\Enum;

/**
 * 权限规则
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-07
 */
class AuthRuleRuleType extends Enum
{
    const DIR_VIEW = [
        'text'      => '不使用组件',
        'value'     => '',
    ];
    const FORM_VIEW = [
        'text'      => '表单组件',
        'value'     => 'form/index',
    ];
    const TABLE_VIEW = [
        'text'      => '表格组件',
        'value'     => 'table/index',
    ];
    const REMOTE_VIEW = [
        'text'      => '远程组件',
        'value'     => 'remote/index',
    ];
}
