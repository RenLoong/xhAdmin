<?php

return [
    [
        # 分组标识
        'name' => 'system_config',
        # 分组名称
        'title' => '系统设置',
        # 布局模式：10单列，20双列并排，30四列并排
        'layout_col' => '10',
        # 分组排序
        'sort' => 100,
        # 配置项
        'children' => [
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'web_name',
                # 配置项名称
                'title' => '网站名称',
                # 配置项排序
                'sort' => 100,
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 配置项是否显示
                'show' => '20',
                # 表单底部描述
                'placeholder' => "网站的系统名称",
                # 数据默认值
                'value' => 'KFAdmin',
                # 额外扩展参数
                'extra'         => [],
            ],
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'web_url',
                # 配置项名称
                'title' => '网站域名',
                # 配置项排序
                'sort' => 100,
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 配置项是否显示
                'show' => '20',
                # 表单底部描述
                'placeholder' => '网站的域名，以斜杠结尾',
                # 数据默认值
                'value' => '',
                # 额外扩展参数
                'extra'         => [],
            ],
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'admin_logo',
                # 配置项名称
                'title' => '网站图标',
                # 配置项排序
                'sort' => 100,
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'uploadify',
                # 配置项是否显示
                'show' => '20',
                # 表单底部描述
                'placeholder' => '网站图标',
                # 数据默认值
                'value' => '',
                # 额外扩展参数
                'extra'         => [],
            ],
        ],
    ],
    [
        # 分组标识
        'name' => 'aligns_copyright_config',
        # 分组名称
        'title' => '代理商版权',
        # 布局模式：10单列，20双列并排，30四列并排
        'layout_col' => '10',
        # 分组排序
        'sort' => 100,
        # 配置项
        'children' => [
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'store_copyright_name',
                # 配置项名称
                'title' => '版权名称',
                # 配置项排序
                'sort' => 100,
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 配置项是否显示
                'show' => '20',
                # 表单底部描述
                'placeholder'   => '展示在租户统计页面的版权名称',
                # 数据默认值
                'value'         => 'KFAdmin',
                # 额外扩展参数
                'extra'         => [],
            ],
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'store_copyright_tutorial',
                # 配置项名称
                'title' => '系统教程',
                # 配置项排序
                'sort' => 100,
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'textarea',
                # 配置项是否显示
                'show' => '20',
                # 表单底部描述
                'placeholder' => "一行一个信息，示例：名称|网址",
                # 数据默认值
                'value' => '',
                # 额外扩展参数
                'extra'         => [],
            ],
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'store_copyright_service',
                # 配置项名称
                'title' => '专属客服',
                # 配置项排序
                'sort' => 100,
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 配置项是否显示
                'show' => '20',
                # 表单底部描述
                'placeholder' => '客服展示信息',
                # 数据默认值
                'value' => '18786709420（微信同号）',
                # 额外扩展参数
                'extra'         => [],
            ],
        ],
    ],
];
