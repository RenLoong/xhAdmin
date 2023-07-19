<?php

namespace app\admin\controller;

use app\common\builder\FormBuilder;
use app\common\builder\ListBuilder;
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
            ->addRightButton('restore', '恢复', [
                'type' => 'confirm',
                'api' => 'admin/StorePlatform/restore',
                'method' => 'delete',
                'params'      => [
                    'field' => 'delete_time',
                    'where' => '!=',
                    'value' => null,
                ],
            ], [
                'title' => '温馨提示',
                'content' => '是否确认恢复该数据',
            ], [
                'type' => 'warning',
                'link' => true
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
            $model = $model->onlyTrashed();
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
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function add()
    {
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
        $platformModel = $model->where($where)->find();
        if (!$platformModel) {
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
                    throw new Exception('该平台图配置不存在');
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
                if (!$platformModel->save($platformData)) {
                    throw new Exception('保存失败');
                }
                Db::commit();
                return parent::success('保存成功');
            } catch (\Throwable $e) {
                Db::rollback();
                return parent::fail($e->getMessage());
            }
        }
        $platformData          = $platformModel->toArray();
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

    /**
     * 恢复平台
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function restore(Request $request)
    {
        try {
            # 获取ID
            $id = (int) $request->post('id', 0);

            $model = $this->model;
            $where = [
                'id'=> $id
            ];
            $model = $model->where($where)->onlyTrashed()->find();
            if (!$model) {
                throw new Exception('该数据不存在');
            }
            if (!$model->restore()) {
                throw new Exception('恢复失败');
            }
            return $this->success('恢复成功');
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }
}