<?php

# 基本配置
return [
    [
        'field'         => 'web_name',
        'title'         => '应用名称',
        'value'         => '',
        'component'     => 'input',
        'extra'         => [
            'props'     => [
                'type'  => 'text',
                'placeholder'   => '请输入网站名称',
            ],
        ],
    ],
    [
        'field'         => 'web_logo',
        'title'         => '应用LOGO',
        'value'         => '',
        'component'     => 'uploadify',
        'extra'         => [],
    ],
];
