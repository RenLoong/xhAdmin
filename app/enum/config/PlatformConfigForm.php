<?php
namespace app\enum\config;

use app\enum\WechatType;

/**
 * 平台配置表单
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-04
 */
class PlatformConfigForm
{
    /**
     * 获取平台配置表单
     * @param string $key
     * @return mixed
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-04
     */
    public static function getConfig(string $key)
    {
        $config = self::parseConfig();
        return isset($config[$key]) ? $config[$key] : [];
    }

    /**
     * 通用配置
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    private static function baseConfig()
    {
        return [
            [
                'type'  => 'input',
                'field' => 'web_name',
                'title' => '平台名称',
                'value' => '',
                'extra' => [
                    'col' => [
                        'span' => 12
                    ],
                ]
            ],
            [
                'type'  => 'input',
                'field' => 'domain',
                'title' => '平台域名',
                'value' => '',
                'extra' => [
                    'col' => [
                        'span' => 12
                    ],
                ]
            ],
            [
                'type'  => 'uploadify',
                'field' => 'logo',
                'title' => '平台图标',
                'value' => '',
                'extra' => [
                    'col' => [
                        'span' => 12
                    ],
                ]
            ],
            [
                'type'  => 'textarea',
                'field' => 'description',
                'title' => '平台描述',
                'value' => '',
                'extra' => [
                    'col' => [
                        'span' => 12
                    ],
                ]
            ],
        ];
    }

    /**
     * 获取表单配置
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-04
     */
    private static function parseConfig()
    {
        return [
            // 微信公众号
            'wechat'      => [
                'active' => 'setting',
                'list'   => [
                    [
                        'label'    => '平台设置',
                        'value'    => 'setting',
                        'children' => array_merge(
                            self::baseConfig(),
                            [
                                [
                                    'type'  => 'select',
                                    'field' => 'wechat_type',
                                    'title' => '公众号类型',
                                    'value' => '0',
                                    'extra' => [
                                        'col'     => [
                                            'span' => 12
                                        ],
                                        'options' => WechatType::parseAlias('label')
                                    ]
                                ],
                                [
                                    'type'  => 'input',
                                    'field' => 'original_appid',
                                    'title' => '原始ID',
                                    'value' => '',
                                    'extra' => [
                                        'col' => [
                                            'span' => 12
                                        ],
                                    ]
                                ],
                                [
                                    'type'  => 'input',
                                    'field' => 'wechat_appid',
                                    'title' => 'APPID',
                                    'value' => '',
                                    'extra' => [
                                        'col' => [
                                            'span' => 12
                                        ],
                                    ]
                                ],
                                [
                                    'type'  => 'input',
                                    'field' => 'wechat_app_secret',
                                    'title' => 'appSecret',
                                    'value' => '',
                                    'extra' => []
                                ],
                            ]
                        ),
                    ],
                    [
                        'label'    => '公众号通信',
                        'value'    => 'wechat_message',
                        'children' => [
                            [
                                'type'  => 'n-tag',
                                'field' => 'wechat_api_url',
                                'title' => '通信地址',
                                'value' => '',
                                'extra' => [
                                    'props'    => [
                                        'type' => 'warning',
                                    ],
                                    'children' => ['default' => '']
                                ]
                            ],
                            [
                                'type'  => 'n-tag',
                                'field' => 'wechat_token',
                                'title' => 'token',
                                'value' => '',
                                'extra' => [
                                    'props'    => [
                                        'type' => 'warning',
                                    ],
                                    'children' => ['default' => '']
                                ]
                            ],
                            [
                                'type'  => 'n-tag',
                                'field' => 'wechat_encoding_aes_key',
                                'title' => 'encodingAESKey',
                                'value' => '',
                                'extra' => [
                                    'props'    => [
                                        'type' => 'warning',
                                    ],
                                    'children' => ['default' => '']
                                ]
                            ],
                        ],
                    ],
                    [
                        'label'    => '支付设置',
                        'value'    => 'pay',
                        'children' => [
                            [
                                'type'  => 'input',
                                'field' => 'mch_id',
                                'title' => 'mch_id',
                                'value' => '',
                                'extra' => [
                                    'col' => [
                                        'span' => 12
                                    ],
                                ]
                            ],
                            [
                                'type'  => 'input',
                                'field' => 'wechat_alipay_key',
                                'title' => '支付密钥',
                                'value' => '',
                                'extra' => [
                                    'col' => [
                                        'span' => 12
                                    ],
                                ]
                            ],
                            [
                                'type'  => 'uploadify',
                                'field' => 'wechat_cart_file',
                                'title' => 'cert证书',
                                'value' => '',
                                'extra' => [
                                    'col'   => [
                                        'span' => 12
                                    ],
                                    'props' => [
                                        'format' => ['cart', 'pem']
                                    ],
                                ]
                            ],
                            [
                                'type'  => 'uploadify',
                                'field' => 'wechat_key_file',
                                'title' => 'key证书',
                                'value' => '',
                                'extra' => [
                                    'col'   => [
                                        'span' => 12
                                    ],
                                    'props' => [
                                        'format' => ['key', 'pem']
                                    ],
                                ]
                            ],
                        ],
                    ],
                ],
            ],
            // 微信小程序
            'mini_wechat' => [
                'active' => 'setting',
                'list'   => [
                    [
                        'label'    => '平台设置',
                        'value'    => 'setting',
                        'children' => self::baseConfig(),
                    ]
                ],
            ],
            // 抖音应用
            'douyin'      => [
                'active' => 'setting',
                'list'   => [
                    [
                        'label'    => '平台设置',
                        'value'    => 'setting',
                        'children' => self::baseConfig(),
                    ]
                ],
            ],
            // 网页应用
            'h5'          => [
                'active' => 'setting',
                'list'   => [
                    [
                        'label'    => '平台设置',
                        'value'    => 'setting',
                        'children' => self::baseConfig(),
                    ],
                    [
                        'label'    => '支付设置',
                        'value'    => 'pay',
                        'children' => [
                            [
                                'type'  => 'input',
                                'field' => 'appid',
                                'title' => 'APPID',
                                'value' => '',
                                'extra' => [
                                    'col' => [
                                        'span' => 12
                                    ],
                                ]
                            ],
                            [
                                'type'  => 'input',
                                'field' => 'mch_id',
                                'title' => 'MCH_ID',
                                'value' => '',
                                'extra' => [
                                    'col' => [
                                        'span' => 12
                                    ],
                                ]
                            ],
                            [
                                'type'  => 'input',
                                'field' => 'mch_key',
                                'title' => 'MCH_KEY',
                                'value' => '',
                                'extra' => [
                                    'col' => [
                                        'span' => 12
                                    ],
                                ]
                            ],
                        ],
                    ]
                ],
            ],
            // APP应用
            'app'         => [
                'active' => 'setting',
                'list'   => [
                    [
                        'label'    => '平台设置',
                        'value'    => 'setting',
                        'children' => self::baseConfig(),
                    ]
                ],
            ],
            // 其他应用
            'other'       => [
                'active' => 'setting',
                'list'   => [
                    [
                        'label'    => '平台设置',
                        'value'    => 'setting',
                        'children' => self::baseConfig(),
                    ]
                ],
            ],
        ];
    }
}