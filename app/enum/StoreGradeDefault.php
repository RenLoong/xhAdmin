<?php

namespace app\enum;

use app\Enum;

/**
 * 租户等级状态
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-13
 */
class StoreGradeDefault extends Enum
{
    const OFF = [
        'text'      => '否',
        'value'     => '0'
    ];
    const ON = [
        'text'      => '是',
        'value'     => '1'
    ];
}
