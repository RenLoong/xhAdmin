<?php
namespace app\common\enum;

use app\common\Enum;

/**
 * 系统设置枚举类
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
class SettingsEnum extends Enum
{
    const SETTING_CONFIG = [
        'text'      => '系统配置',
        'value'     => '10',
    ];
    const WEIXIN_PAY = [
        'text'      => '微信支付',
        'value'     => '20',
    ];
    const ALIPAY_CONFIG = [
        'text'      => '支付宝支付',
        'value'     => '30',
    ];
    const WEIXIN_AD = [
        'text'      => '微信流量主',
        'value'     => '40',
    ];
}
