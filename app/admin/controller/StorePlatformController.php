<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\model\Store;
use app\admin\validate\StorePlatform;
use app\BaseController;
use app\enum\PlatformTypes;
use app\enum\StorePlatformStatus;
use app\manager\StorePlatforms;
use app\model\StorePlatformConfig;
use app\service\Upload;
use Exception;
use support\Request;
use think\facade\Db;

/**
 * 商户平台管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StorePlatformController extends BaseController
{
    /**
     * 模型
     * @var \app\admin\model\StorePlatform
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
        $this->model = new \app\admin\model\StorePlatform;
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
                'width' => 210
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
            ->addRightButton('edit', '修改', [
                'type' => 'modal',
                'api' => 'admin/StorePlatform/edit',
                'path' => '/StorePlatform/edit',
            ], [
                'title' => '修改平台',
            ], [
                'type' => 'primary',
                'link' => true
            ])
            ->addRightButton('del', '删除', [
                'type' => 'confirm',
                'api' => 'admin/StorePlatform/del',
                'method' => 'delete',
            ], [
                'title' => '温馨提示',
                'content' => '是否确认删除该数据',
            ], [
                'type' => 'error',
                'link' => true
            ])
            ->addColumn('configs.web_name', '平台名称')
            ->addColumn('store.title', '所属租户')
            ->addColumnEle('configs.logo', '平台图标', [
                'params' => [
                    'type' => 'image',
                ],
            ])
            ->addColumnEle('status', '平台状态', [
                'width' => 100,
                'params' => [
                    'type' => 'tags',
                    'options' => ['冻结', '正常'],
                    'style' => [
                        [
                            'type' => 'error'
                        ],
                        [
                            'type' => 'success'
                        ],
                    ],
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
        $store_id = $request->get('id', '');
        $status = $request->get('status', '1');
        $model   = $this->model;
        $where    = [];
        if ($store_id) {
            $where['store_id'] = $store_id;
        }
        if ($status === '0') {
            $model = $model->withTrashed();
        }
        $orderBy = [
            'id' => 'desc'
        ];
        $data    = $model->with(['store'])
            ->where($where)
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
        $store_id = $request->get('id', '');
        if ($request->method() == 'POST') {
            $post = $request->post();
            if (!isset($post['store_id'])) {
                $post['store_id'] = $store_id;
            }

            // 数据验证
            hpValidate(StorePlatform::class, $post, 'add');

            // 验证参数可创建数量
            $surplusNum = StorePlatforms::surplusNum($store_id);
            if (!isset($surplusNum[$post['platform_type']])) {
                return $this->fail('平台类型参数错误');
            }
            $surplusNum = $surplusNum[$post['platform_type']];
            if ($surplusNum <= 0) {
                return $this->fail('您该平台类型已使用完');
            }

            $post['logo'] = Upload::path($post['logo']);


            // 事务
            Db::startTrans();
            try {
                $model        = $this->model;
                $platformData = [
                    'store_id' => $store_id,
                    'platform_type' => $post['platform_type']
                ];
                if (!$model->save($platformData)) {
                    throw new Exception('保存失败');
                }
                // 保存配置项
                $configData = [
                    'store_id' => $store_id,
                    'platform_id' => $model->id,
                ];
                $fields     = [
                    'web_name' => $post['title'],
                    'domain' => $post['domain'],
                    'logo' => Upload::path($post['logo']),
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
                Db::commit();
                return parent::success('保存成功');
            } catch (\Throwable $e) {
                Db::rollback();
                return parent::fail($e->getMessage());
            }
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        if (!$store_id) {
            $builder->addRow('store_id', 'select', '所属商户', '', [
                'col' => [
                    'span' => 12
                ],
                'options' => Store::getSelectOptions()
            ]);
        }
        $builder->addRow('platform_type', 'select', '平台类型', 'other', [
            'col' => [
                'span' => 12
            ],
            'options' => PlatformTypes::getOptions()
        ]);
        $builder->addRow('title', 'input', '平台名称', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $builder->addComponent('logo', 'uploadify', '平台图标', '', [
            'col' => [
                'span' => 12
            ],
            'props' => [
                'format' => ['jpg', 'png', 'gif']
            ],
        ]);
        $builder->addRow('status', 'radio', '平台状态', '1', [
            'col' => [
                'span' => 12
            ],
            'options' => StorePlatformStatus::getOptions()
        ]);
        $builder->addRow('remarks', 'textarea', '平台备注', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $data = $builder->create();
        return parent::successRes($data);
    }

    /**
     * 修改
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function edit(Request $request)
    {
        $id    = $request->get('id', '');
        $model = $this->model;
        $where = [
            ['id', '=', $id],
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该平台不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();

            // 事务
            Db::startTrans();
            try {
                // 保存配置项
                $where       = [
                    ['platform_id', '=', $id],
                    ['config_field', 'in', ['web_name', 'logo']],
                ];
                $configModel = StorePlatformConfig::where($where)->select();
                if (!$configModel) {
                    throw new Exception('该平台图置不存在');
                }
                foreach ($configModel as $model) {
                    if ($model->config_field === 'web_name') {
                        $model->config_value = $post['title'];
                    }
                    if ($model->config_field === 'logo') {
                        $model->config_value = Upload::path($post['logo']);
                    }
                    if (!$model->save()) {
                        throw new Exception('保存平台配置失败');
                    }
                }
                // 保存平台数据
                $platformData = [
                    'status' => $post['status'],
                    'remarks' => $post['remarks'],
                ];
                if (!$model->save($platformData)) {
                    throw new Exception('保存失败');
                }
                Db::commit();
                return parent::success('保存成功');
            } catch (\Throwable $e) {
                Db::rollback();
                return parent::fail($e->getMessage());
            }
        }
        $platformData          = $model->toArray();
        $platformData['title'] = $platformData['configs']['web_name'];
        $platformData['logo']  = $platformData['configs']['logo'];

        $builder = new FormBuilder;
        $builder->setMethod('PUT');
        $builder->addRow('title', 'input', '平台名称', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $builder->addComponent('logo', 'uploadify', '平台图标', '', [
            'col' => [
                'span' => 12
            ],
            'props' => [
                'format' => ['jpg', 'png', 'gif']
            ],
        ]);
        $builder->addRow('status', 'radio', '平台状态', '1', [
            'col' => [
                'span' => 12
            ],
            'options' => StorePlatformStatus::getOptions()
        ]);
        $builder->addRow('remarks', 'textarea', '平台备注', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $builder->setFormData($platformData);
        $data = $builder->create();
        return parent::successRes($data);
    }

    /**
     * 删除
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function del(Request $request)
    {
        try {
            # 获取ID
            $id = (int) $request->post('id', 0);
            # 执行删除
            StorePlatforms::deletePlatform($id);
            # 删除成功
            return $this->success('删除成功');
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }
}