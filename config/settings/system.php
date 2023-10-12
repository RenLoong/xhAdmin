<?php

return [
    [
        'field'         => 'web_name',
        'title'         => '网站名称',
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
        'field'         => 'web_url',
        'title'         => '网站域名',
        'value'         => '',
        'component'     => 'input',
        'extra'         => [
            'props'     => [
                'type'          => 'text',
                'placeholder'   => '请输入网站链接，无斜杠结尾，如：https://www.xxxx.com',
            ],
        ],
    ],
    [
        'field'         => 'admin_logo',
        'title'         => '后台LOGO',
        'value'         => '',
        'component'     => 'uploadify',
        'extra'         => [],
    ],
];
