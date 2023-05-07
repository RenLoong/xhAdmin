<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\model\Store;
use app\admin\validate\StorePlatform;
use app\BaseController;
use app\enum\PlatformTypes;
use app\enum\StorePlatformStatus;
use app\service\Upload;
use support\Request;

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
            ->addRightButton('edit', '修改', [
                'type' => 'modal',
                'api'  => 'admin/StorePlatform/edit',
                'path' => '/StorePlatform/edit',
            ], [
                    'title' => '修改平台',
                ], [
                    'type' => 'primary',
                    'link' => true
                ])
            ->addRightButton('del', '删除', [
                'type'   => 'confirm',
                'api'    => 'admin/StorePlatform/del',
                'method' => 'delete',
            ], [
                    'title'   => '温馨提示',
                    'content' => '是否确认删除该数据',
                ], [
                    'type' => 'danger',
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
                'width'  => 100,
                'params' => [
                    'type'    => 'tags',
                    'options' => ['冻结', '正常'],
                    'style'   => [
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
        $where    = [];
        if ($store_id) {
            $where['store_id'] = $store_id;
        }
        $orderBy = [
            'id' => 'desc'
        ];
        $model   = $this->model;
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

            $post['logo'] = Upload::path($post['logo']);

            $model = $this->model;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        if (!$store_id) {
            $builder->addRow('store_id', 'select', '所属商户', '', [
                'col'     => [
                    'span' => 12
                ],
                'options' => Store::getSelectOptions()
            ]);
        }
        $builder->addRow('platform_type', 'select', '平台类型', 'other', [
            'col'     => [
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
            'col'   => [
                'span' => 12
            ],
            'props' => [
                'format' => ['jpg', 'png', 'gif']
            ],
        ]);
        $builder->addRow('status', 'radio', '平台状态', '1', [
            'col'     => [
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
        $id = $request->get('id', '');
        $model = $this->model;
        $where = [
            ['id','=',$id],
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该数据不存在');
        }
        if ($request->method() == 'POST') {
            $post = $request->post();

            $post['store_id'] = $model->store_id;

            // 数据验证
            hpValidate(StorePlatform::class, $post, 'edit');

            $post['logo'] = Upload::path($post['logo']);

            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        $builder->addRow('platform_type', 'select', '平台类型', 'other', [
            'col'     => [
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
            'col'   => [
                'span' => 12
            ],
            'props' => [
                'format' => ['jpg', 'png', 'gif']
            ],
        ]);
        $builder->addRow('status', 'radio', '平台状态', '1', [
            'col'     => [
                'span' => 12
            ],
            'options' => StorePlatformStatus::getOptions()
        ]);
        $builder->addRow('remarks', 'textarea', '平台备注', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $builder->setData($model);
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
        $id = $request->post('id');
        $where = [
            'id'        => $id
        ];
        $model = $this->model;
        if (!$model::where($where)->delete()) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }
}