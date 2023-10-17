<?php

# 短信配置
return [
    [
        'title'         => '阿里云短信',
        'name'          => 'aliun_sms',
        'children'      => [
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'aliyun_accessKeyId',
                # 配置项名称
                'title' => 'accessKeyId',
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 数据默认值
                'value' => '',
            ],
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'aliyun_accessKeySecret',
                # 配置项名称
                'title' => 'aliyun_accessKeySecret',
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 数据默认值
                'value' => '',
            ],
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'aliyun_TemplateCode',
                # 配置项名称
                'title' => '短信模板',
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 数据默认值
                'value' => '',
            ],
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'aliyun_signName',
                # 配置项名称
                'title' => '短信签名',
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 数据默认值
                'value' => '',
            ],
        ],
    ],
    [
        'title'         => '腾讯云短信',
        'name'          => 'qq_sms',
        'children'      => [
            [
                # 配置项标识(可通过getHpconfig进行读取)
                'name' => 'wechat_notice_status',
                # 配置项名称
                'title' => '公众号消息，待定',
                # 表单组件(参考common/enum/FormType.php)
                'component' => 'input',
                # 数据默认值
                'value' => '',
            ],
        ],
    ],
];