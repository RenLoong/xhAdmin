<?php
namespace app\common\enum;

use app\Enum;

/**
 * 应用平台类型
 * 支持类型：'wechat','mini_wechat','douyin','h5','app','other'
 * 图标类型支持：url，path，icon
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-22
 */
class PlatformTypes extends Enum
{
    const WECHAT = [
        'text'      => '微信公众号',
        'value'     => 'wechat',
        'type'      => 'path',
        'icon'      => '/image/new_wechat.png',
    ];
    const MINI_WECHAT = [
        'text'      => '微信小程序',
        'value'     => 'mini_wechat',
        'type'      => 'path',
        'icon'      => '/image/new_wx_mini.png',
    ];
    const DOUYIN = [
        'text'      => '抖音应用',
        'value'     => 'douyin',
        'type'      => 'path',
        'icon'      => '/image/new_douyin.png',
    ];
    const H5 = [
        'text'      => '网页应用',
        'value'     => 'h5',
        'type'      => 'path',
        'icon'      => '/image/new_h5.png',
    ];
    const APP = [
        'text'      => 'APP应用',
        'value'     => 'app',
        'type'      => 'path',
        'icon'      => '/image/new_other.png',
    ];
    const OTHER = [
        'text'      => '其他应用',
        'value'     => 'other',
        'type'      => 'path',
        'icon'      => '/image/new_other.png',
    ];
}
