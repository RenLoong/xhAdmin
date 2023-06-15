<?php

namespace app\store\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\validate\StorePlatform;
use app\BaseController;
use app\enum\config\PlatformConfigForm;
use app\enum\PlatformTypes;
use app\enum\PlatformTypesStyle;
use app\manager\StorePlatforms;
use app\service\Upload;
use app\store\model\Store;
use app\store\model\StorePlatformConfig;
use Exception;
use support\Request;
use app\store\model\StorePlatform as modelStorePlatform;
use think\facade\Db;

/**
 * 平台管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class PlatformController extends BaseController
{
    /**
     * 模型
     * @var modelStorePlatform
     */
    public $model;

    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function __construct()
    {
        $this->model = new modelStorePlatform;
    }

    /**
     * 表格
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-01
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data    = $builder
            ->addActionOptions('操作', [
                'width' => 230
            ])
            ->pageConfig()
            ->tabsConfig([
                'active' => '1',
                'field'  => 'status',
                'list'   => [
                    [
                        'label' => '平台列表',
                        'value' => '1'
                    ],
                    [
                        'label' => '回 收 站',
                        'value' => '0'
                    ],
                ]
            ])
            ->addTopButton('add', '开通平台', [
                'type' => 'modal',
                'api'  => 'store/Platform/add',
                'path' => '/Platform/add',
            ], [
                'title' => '开通平台',
            ], [
                'type' => 'success'
            ])
            ->addRightButton(
                'app',
                '应用管理',
                [
                    'type' => 'table',
                    'api'  => 'store/PlatformApp/index',
                    'path' => '/PlatformApp/index',
                ],
                [
                    'title' => '配置应用',
                ],
                [
                    'type' => 'primary',
                ]
            )
            ->addRightButton(
                'config',
                '配置平台',
                [
                    'type' => 'modal',
                    'api'  => 'store/Platform/config',
                    'path' => '/Platform/config',
                ],
                [
                    'title' => '平台配置',
                ],
                [
                    'type' => 'warning',
                ]
            )
            ->addColumn('configs.web_name', '平台名称')
            ->addColumnEle('configs.logo', '平台图标', [
                'params' => [
                    'type' => 'image',
                ],
            ])
            ->addColumnEle('platform_type', '平台类型', [
                'width'  => 150,
                'params' => [
                    'type'    => 'tags',
                    'options' => PlatformTypes::dictOptions(),
                    'style'   => PlatformTypesStyle::parseAlias('type'),
                ],
            ])
            ->addColumnEle('status', '状态', [
                'width'  => 100,
                'params' => [
                    'type'      => 'switch',
                    'checked'   => [
                        'text'  => '正常',
                        'value' => '1'
                    ],
                    'unchecked' => [
                        'text'  => '停用',
                        'value' => '0'
                    ]
                ],
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-01
     */
    public function index(Request $request)
    {
        $status = $request->get('status', '1');
        $where[] = [
            ['status', '=', $status]
        ];
        $orderBy = [
            'id' => 'desc'
        ];
        $model   = $this->model;
        $data    = $model->where($where)
            ->fetchSql(true)
            ->order($orderBy)
            ->paginate()
            ->toArray();
        return parent::successRes($data);
    }

    /**
     * 添加
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function add(Request $request)
    {
        $store_id   = hp_admin_id('hp_store');
        $model = $this->model;
        if ($request->method() === 'POST') {
            $post             = $request->post();
            $post['store_id'] = $store_id;

            // 数据验证
            hpValidate(StorePlatform::class, $post, 'add');

            // 验证参数可创建数量
            $surplusNum = StorePlatforms::surplusNum($store_id);
            if (!isset($surplusNum[$post['platform_type']])) {
                throw new Exception('平台类型参数错误');
            }
            $surplusNum = $surplusNum[$post['platform_type']];
            if ($surplusNum <= 0) {
                throw new Exception("您该平台类型已使用完");
            }

            // 事务
            Db::startTrans();
            try {
                $model        = $this->model;
                $platformData = [
                    'store_id'      => $store_id,
                    'platform_type' => $post['platform_type']
                ];
                if (!$model->save($platformData)) {
                    throw new Exception('保存失败');
                }
                // 保存配置项
                $configData = [
                    'store_id'    => $store_id,
                    'platform_id' => $model->id,
                ];
                $fields     = [
                    'web_name'    => $post['title'],
                    'domain'      => $post['domain'],
                    'logo'        => Upload::path($post['logo']),
                    'description' => '',
                ];
                if ($post['platform_type'] === 'wechat') {
                    $apiUrl                            = "/store/Wechat?store_id={$store_id}";
                    $fields['wechat_api_url']          = $apiUrl;
                    $fields['wechat_token']            = md5(time());
                    $fields['wechat_encoding_aes_key'] = md5($model->id . time());
                }
                foreach ($fields as $field => $val) {
                    $configData['config_field'] = $field;
                    $configData['config_value'] = $val;
                    if (!(new StorePlatformConfig)->save($configData)) {
                        throw new Exception('保存配置项失败');
                    }
                }
                // 扣除平台数量
                $where = [
                    'id'        => $model->store_id
                ];
                if (!Store::where($where)->setDec($post['platform_type'], 1)) {
                    throw new Exception('创建平台失败');
                }
                Db::commit();
                return parent::success('保存成功');
            } catch (\Throwable $e) {
                Db::rollback();
                return parent::fail($e->getMessage());
            }
        }
        $platformTypeOptions = StorePlatforms::getSelectOptions($store_id, true);
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        $builder->addRow('title', 'input', '平台名称', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $builder->addRow('domain', 'input', '平台域名', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $builder->addRow('platform_type', 'select', '平台类型', 'other', [
            'col'     => [
                'span' => 12
            ],
            'options' => $platformTypeOptions
        ]);
        $builder->addComponent('logo', 'uploadify', '平台图标', '', [
            'col'   => [
                'span' => 12
            ],
            'props' => [
                'format' => ['jpg', 'png', 'gif']
            ],
        ]);
        $data = $builder->create();
        return parent::successRes($data);
    }

    /**
     * 平台配置
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-04
     */
    public function config(Request $request)
    {
        $platform_id = $request->get('id');
        $model       = $this->model;
        if ($request->method() === 'PUT') {
            $post = $request->post();

            foreach ($post as $field => $value) {
                $where          = [
                    'platform_id'  => $platform_id,
                    'config_field' => $field
                ];
                $platformConfig = StorePlatformConfig::where($where)->find();
                if (!$platformConfig) {
                    $platformConfig               = new StorePlatformConfig;
                    $platformConfig->store_id     = hp_admin_id('hp_store');
                    $platformConfig->platform_id  = $platform_id;
                    $platformConfig->config_field = $field;
                    $platformConfig->config_value = $value;
                } else {
                    $platformConfig->config_value = $value;
                }
                if (!$platformConfig->save()) {
                    return $this->fail('保存失败');
                }
            }
            return $this->success('保存成功');
        }
        $where    = [
            'id' => $platform_id
        ];
        $platform = $model->where($where)->find();
        if (!$platform) {
            return $this->fail('该平台不存在');
        }
        $formConfig = $this->getTabsFormConfig($platform);
        if (!isset($formConfig['active']) || !isset($formConfig['list'])) {
            return $this->fail('表单错误，请检查');
        }
        if (!is_string($formConfig['active']) || !is_array($formConfig['list'])) {
            return $this->fail('表单类型配置错误，请检查');
        }
        $data = $this->getTabsForm($formConfig, $platform);
        return $this->successRes($data);
    }

    /**
     * 获取平台表单
     * @param array $config
     * @param modelStorePlatform $model
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-04
     */
    private function getTabsForm(array $config, modelStorePlatform $model)
    {
        $builder = new FormBuilder;
        $builder = $builder->initTabs($config['active'], [
            'props' => [
                // 选项卡样式
                'type' => 'line'
            ],
        ]);
        $builder = $builder->setMethod('PUT');

        // 添加Tabs组件
        foreach ($config['list'] as $value) {
            // 获取表单
            $tabsPanel = $this->getTabsPanel($value['children'], $model);
            $builder   = $builder->addTab(
                $value['value'],
                $value['label'],
                $tabsPanel
            );
        }
        $data = $builder->endTabs()->create();
        return $data;
    }

    /**
     * 获取系统配置
     * @param array $list
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-04
     */
    private function getTabsPanel(array $list, modelStorePlatform $model): array
    {
        $config  = $this->getPlatformConfig($model);
        $builder = new FormBuilder;
        foreach ($list as $value) {
            $extra = isset($value['extra']) ? $value['extra'] : [];
            if (in_array($value['type'], ['uploadify', 'n-tag'])) {
                // TAG标签
                if ($value['type'] === 'n-tag') {
                    $slot                         = isset($config[$value['field']]) && $config[$value['field']] ? $config[$value['field']] : '暂无数据';
                    $extra['children']['default'] = $slot;
                }
                $builder = $builder->addComponent(
                    $value['field'],
                    $value['type'],
                    $value['title'],
                    $value['value'],
                    $extra
                );
            } else {
                $builder = $builder->addRow(
                    $value['field'],
                    $value['type'],
                    $value['title'],
                    $value['value'],
                    $extra
                );
            }
        }
        $data = $builder->setFormData($config)->getBuilder()->formRule();
        return $data;
    }

    /**
     * 获取平台已保存配置
     * @param modelStorePlatform $model
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-04
     */
    private function getPlatformConfig(modelStorePlatform $model)
    {
        $request = request();
        $where   = [
            'platform_id' => $model->id
        ];
        $field   = [
            'config_field',
            'config_value',
        ];
        $list    = StorePlatformConfig::where($where)
            ->field($field)
            ->select()
            ->toArray();

        $data = [];

        // 标记为URL的字段
        $isUrl = [
            'wechat_api_url'
        ];
        $url   = "http://{$request->host(true)}";
        foreach ($list as $value) {
            if (in_array($value['config_field'], $isUrl)) {
                $value['config_value'] = "{$url}{$value['config_value']}";
            }
            $data[$value['config_field']] = $value['config_value'];
        }
        return $data;
    }

    /**
     * 获取平台表单配置
     * @param modelStorePlatform $model
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-04
     */
    private function getTabsFormConfig(modelStorePlatform $model)
    {
        $data = PlatformConfigForm::getConfig($model['platform_type']);
        return $data;
    }

    /**
     * 获取平台详情
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-10
     */
    public function detail(Request $request)
    {
        $id    = $request->get('id');
        $model = $this->model;
        $where = [
            'id' => $id,
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该平台不存在在');
        }
        $data = $model->toArray();
        return $this->successRes($data);
    }
}
