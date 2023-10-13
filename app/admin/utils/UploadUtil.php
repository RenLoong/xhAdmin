<?php
namespace app\admin\utils;

use FormBuilder\Factory\Elm;

class UploadUtil
{
    /**
     * 根据表单类型显示（入口）
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function controlOptions()
    {
        return [
            // 本地储存
            [
                'value' => 'local',
                'where' => '==',
                'rule' => self::local(),
            ],
            // 阿里云
            [
                'value' => 'aliyun',
                'where' => '==',
                'rule' => self::aliyun(),
            ],
            // 腾讯云
            [
                'value' => 'qcloud',
                'where' => '==',
                'rule' => self::qcloud(),
            ],
            // 七牛云
            [
                'value' => 'qiniu',
                'where' => '==',
                'rule' => self::qiniu(),
            ],
        ];
    }

    /**
     * 本地云储存表单
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function local()
    {
        $domain   = request()->domain();
        $rootPath = "uploads";
        return [
            Elm::input('url', '访问域名', $domain)->disabled(true),
            Elm::input('root', '储存路径', $rootPath)->disabled(true)
                ->appendRule('suffix', [
                    'type' => 'prompt-tip',
                    'props' => [
                        'text' => '例如：uploads，储存路径：/public/uploads/20230101/xxx.jpg，访问地址：http://www.xxx.com/uploads/20230101/xxx.jpg',
                    ],
                ])
        ];
    }

    /**
     * 阿里云储存表单
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function aliyun()
    {
        return [
            Elm::input('access_id', 'access_id', '')->col(12),
            Elm::input('access_secret', 'access_secret', '')->col(12),
            Elm::input('bucket', '存储空间名称：Bucket', '')->col(12),
            Elm::input('endpoint', 'Bucket 域名', '')->col(12),
        ];
    }

    /**
     * 腾讯云储存表单
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function qcloud()
    {
        return [
            Elm::input('region', '所属地域：Region')->appendRule('suffix', [
                'type' => 'prompt-tip',
                'props' => [
                    'text' => '请填写地域简称，例如：ap-beijing、ap-hongkong、eu-frankfurt',
                ],
            ])
                ->col(12),
            Elm::input('domain', '空间域名：Domain')->appendRule('suffix', [
                'type' => 'prompt-tip',
                'props' => [
                    'text' => '请填写不用带协议的域名，例如：static.cloud.com',
                ],
            ])
                ->col(12),
            Elm::input('app_id', 'APPID')->col(12),
            Elm::input('bucket', '存储空间名称：Bucket')->col(12),
            Elm::input('secret_id', 'SECRET_ID')->col(12),
            Elm::input('secret_key', 'SECRET_KEY')->col(12),
        ];
    }

    /**
     * 七牛云储存表单
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function qiniu()
    {
        return [
            Elm::input('access_key', 'access_key')->col(12),
            Elm::input('secret_key', 'secret_key')->col(12),

            Elm::input('bucket', '存储空间名称：Bucket')->appendRule('suffix', [
                'type' => 'prompt-tip',
                'props' => [
                    'text' => '',
                ],
            ])
                ->col(12),
            Elm::input('domain', '空间域名：Domain')->appendRule('suffix', [
                'type' => 'prompt-tip',
                'props' => [
                    'text' => '请补全http:// 或 https://，例如：http://static.cloud.com',
                ],
            ])
                ->col(12),
        ];
    }

    /**
     * 获取选项
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function options()
    {
        return [
            [
                'label' => '本地附件(不推荐)',
                'value' => 'local',
            ],
            [
                'label' => '阿里云储存',
                'value' => 'aliyun',
            ],
            [
                'label' => '腾讯云储存',
                'value' => 'qcloud',
            ],
            [
                'label' => '七牛云储存',
                'value' => 'qiniu',
            ],
        ];
    }
}