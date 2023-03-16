<?php

namespace app\enum;

use app\Enum;

/**
 * 商户状态
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StoreStatus extends Enum
{
    const OFF = [
        'text'      => '冻结',
        'value'     => '0'
    ];
    const ON = [
        'text'      => '正常',
        'value'     => '1'
    ];
}
