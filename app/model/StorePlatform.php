<?php

namespace app\model;

use app\Model;

/**
 * 商户平台
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StorePlatform extends Model
{
    // 标记字段为附件类型
    public $uploadify = [
        'logo'   => 'image',
    ];
}
