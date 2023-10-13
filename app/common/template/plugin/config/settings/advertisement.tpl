<?php

# 微信流量主配置
return [
    [
        # 配置项标识(可通过getHpconfig进行读取)
        'name' => 'banner_status',
        # 配置项名称
        'title' => 'banner广告状态',
        # 表单组件(参考common/enum/FormType.php)
        'component' => 'radio',
        # 数据默认值
        'value' => '',
        # 额外扩展
        'extra' => [
            'options' => [
                ['label' => '开启', 'value' => '1'],
                ['label' => '关闭', 'value' => '0'],
            ]
        ],
    ],
    [
        # 配置项标识(可通过getHpconfig进行读取)
        'name' => 'banner_ad_id',
        # 配置项名称
        'title' => 'banner广告ID',
        # 表单组件(参考common/enum/FormType.php)
        'component' => 'input',
        # 数据默认值
        'value' => '',
    ],
    [
        # 配置项标识(可通过getHpconfig进行读取)
        'name' => 'video_status',
        # 配置项名称
        'title' => '视频广告状态',
        # 表单组件(参考common/enum/FormType.php)
        'component' => 'radio',
        'extra' => [
            'options' => [
                ['label' => '开启', 'value' => '1'],
                ['label' => '关闭', 'value' => '0'],
            ]
        ],
        # 数据默认值
        'value' => '',
    ],
    [
        # 配置项标识(可通过getHpconfig进行读取)
        'name' => 'video_ad_id',
        # 配置项名称
        'title' => '视频广告ID',
        # 表单组件(参考common/enum/FormType.php)
        'component' => 'input',
        # 数据默认值
        'value' => '',
    ],
    [
        # 配置项标识(可通过getHpconfig进行读取)
        'name' => 'incentive_status',
        # 配置项名称
        'title' => '激励广告状态',
        # 表单组件(参考common/enum/FormType.php)
        'component' => 'radio',
        'extra' => [
            'options' => [
                ['label' => '开启', 'value' => '1'],
                ['label' => '关闭', 'value' => '0'],
            ]
        ],
        # 数据默认值
        'value' => '',
    ],
    [
        # 配置项标识(可通过getHpconfig进行读取)
        'name' => 'incentive_ad_id',
        # 配置项名称
        'title' => '激励广告ID',
        # 配置项排序
        'sort' => 100,
        # 表单组件(参考common/enum/FormType.php)
        'component' => 'input',
        # 数据默认值
        'value' => '',
    ],
    [
        # 配置项标识(可通过getHpconfig进行读取)
        'name' => 'interstitial_status',
        # 配置项名称
        'title' => '插屏广告状态',
        # 配置项排序
        'sort' => 100,
        # 表单组件(参考common/enum/FormType.php)
        'component' => 'radio',
        'extra' => [
            'options' => [
                ['label' => '开启', 'value' => '1'],
                ['label' => '关闭', 'value' => '0'],
            ]
        ],
        # 数据默认值
        'value' => '',
    ],
    [
        # 配置项标识(可通过getHpconfig进行读取)
        'name' => 'interstitial_ad_id',
        # 配置项名称
        'title' => '插屏广告ID',
        # 配置项排序
        'sort' => 100,
        # 表单组件(参考common/enum/FormType.php)
        'component' => 'input',
        # 数据默认值
        'value' => '',
    ],
];