<?php

namespace app\enum;

use app\Enum;

/**
 * 管理员状态
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-08
 */
class AdminStatus extends Enum
{
    const STATUS_NO = [
        'text'      => '冻结',
        'value'     => '0',
    ];
    const STATUS_YES = [
        'text'      => '正常',
        'value'     => '1',
    ];
}
