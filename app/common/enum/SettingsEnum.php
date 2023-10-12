<?php
namespace app\common\enum;

use app\common\Enum;

/**
 * 系统设置分组枚举类
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
class SettingsEnum extends Enum
{
    const SETTING_CONFIG = [
        'text'      => '系统配置',
        'disabled'  => true,
        'value'     => 'system',
    ];
    const WEIXIN_PAY = [
        'text'      => '微信支付',
        'value'     => 'wxpay',
    ];
    const ALIPAY_CONFIG = [
        'text'      => '支付宝支付',
        'value'     => 'alipay',
    ];
    const WEIXIN_AD = [
        'text'      => '微信流量主',
        'value'     => 'advertisement',
    ];
}
