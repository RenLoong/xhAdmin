<?php
namespace app\common\enum;

use app\Enum;

/**
 * 小程序基本数据
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class AppletMiniSettins extends Enum
{
    // 微信小程序
    const group = [
        [
            'title'         => '小程序配置',
            'name'          => 'system_mini_config',
            'icon'          => '',
            'sort'          => 0,
            'layout_col'    => '20',
            'show'          => '20'
        ],
    ];
    // 抖音小程序
    const configs = [
        [
            'group_name'    => 'system_mini_config',
            'title'         => 'appid',
            'name'          => 'applet_appid',
            'value'         => '',
            'type'          => 'input',
            'extra'         => [],
            'placeholder'   => '',
            'sort'          => 100,
            'show'          => '10'
        ],
        [
            'group_name'    => 'system_mini_config',
            'title'         => 'secret',
            'name'          => 'applet_secret',
            'value'         => '',
            'type'          => 'input',
            'extra'         => [],
            'placeholder'   => '',
            'sort'          => 100,
            'show'          => '10'
        ],
        [
            'group_name'    => 'system_mini_config',
            'title'         => 'privatekey',
            'name'          => 'applet_privatekey',
            'value'         => '',
            'type'          => 'input',
            'extra'         => [],
            'placeholder'   => '',
            'sort'          => 100,
            'show'          => '10'
        ],
        [
            'group_name'    => 'system_mini_config',
            'title'         => '显示预览',
            'name'          => 'applet_state',
            'value'         => '10',
            'type'          => 'input',
            'extra'         => [],
            'placeholder'   => '',
            'sort'          => 100,
            'show'          => '10'
        ],
    ];
}
