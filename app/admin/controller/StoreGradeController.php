<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\model\StoreGrade as ModelStoreGrade;
use app\admin\validate\StoreGrade as ValidateStoreGrade;
use app\BaseController;
use app\enum\StoreGradeDefault;
use support\Request;

/**
 * 租户等级管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StoreGradeController extends BaseController
{
    // 操作模型
    public $model;

    /**
     * 构造函数
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-13
     */
    public function __construct()
    {
        $this->model = new ModelStoreGrade;
    }

    /**
     * 表格
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-02
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data = $builder
            ->addActionOptions('操作', [
                'width'         => 180
            ])
            ->pageConfig()
            ->addTopButton('add', '添加', [
                'type'          => 'modal',
                'api'           => 'admin/StoreGrade/add',
                'path'          => '/StoreGrade/add',
            ], [
                'title'         => '添加等级',
            ], [
                'type'          => 'success'
            ])
            ->addRightButton('edit', '修改', [
                'type'          => 'modal',
                'api'           => 'admin/StoreGrade/edit',
                'path'          => '/StoreGrade/edit',
            ], [
                'title'         => '修改等级',
            ], [
                'type'          => 'primary',
                'link'          => true
            ])
            ->addRightButton('del', '删除', [
                'type'          => 'confirm',
                'api'           => 'admin/StoreGrade/del',
                'method'        => 'delete',
            ], [
                'title'         => '温馨提示',
                'content'       => '是否确认删除该数据',
            ], [
                'type'          => 'error',
            ])
            ->editConfig()
            ->addColumn('title', '等级名称')
            ->addColumn('expire_day', '使用期限(天)')
            ->addColumnEdit('platform_wechat', '微信公众号', [
                'params'        => [
                    'api'       => '/admin/StoreGrade/rowEdit'
                ],
            ])
            ->addColumnEdit('platform_min_wechat', '微信小程序', [
                'params'        => [
                    'api'       => '/admin/StoreGrade/rowEdit'
                ],
            ])
            ->addColumnEdit('platform_app', 'APP应用', [
                'params'        => [
                    'api'       => '/admin/StoreGrade/rowEdit'
                ],
            ])
            ->addColumnEdit('platform_h5', '网页应用', [
                'params'        => [
                    'api'       => '/admin/StoreGrade/rowEdit'
                ],
            ])
            ->addColumnEdit('platform_douyin', '抖音应用', [
                'params'        => [
                    'api'       => '/admin/StoreGrade/rowEdit'
                ],
            ])
            ->addColumnEdit('platform_other', '其他应用', [
                'params'        => [
                    'api'       => '/admin/StoreGrade/rowEdit'
                ],
            ])
            ->addColumnEle('is_default', '是否默认', [
                'width'         => 100,
                'params'        => [
                    'type'      => 'switch',
                    'api'       => '/admin/StoreGrade/rowEdit',
                    'checked'   => [
                        'text'  => '默认',
                        'value' => '1'
                    ],
                    'unchecked' => [
                        'text'  => '不默认',
                        'value' => '0'
                    ]
                ],
            ])
            ->addColumnEle('status', '等级状态', [
                'width'         => 100,
                'params'        => [
                    'type'      => 'switch',
                    'api'       => '/admin/StoreGrade/rowEdit',
                    'checked'   => [
                        'text'  =>'开启',
                        'value' => '1'
                    ],
                    'unchecked' => [
                        'text'  => '禁用',
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
     * @DateTime 2023-05-02
     */
    public function index(Request $request)
    {
        $data = ModelStoreGrade::order(['sort' => 'asc', 'id' => 'desc'])
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
     * @DateTime 2023-05-02
     */
    public function add(Request $request)
    {
        if ($request->method() == 'POST') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateStoreGrade::class, $post, 'add');

            $model = $this->model;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('POST')
            ->addRow('title', 'input', '等级名称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('sort', 'input', '等级排序', '0', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('expire_day', 'input', '使用期限(天)', '0', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('is_default', 'radio', '是否默认', '0', [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => StoreGradeDefault::getOptions()
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 修改
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-02
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $where = [
            'id'    => $id
        ];
        $model = $this->model;
        $model = $model->where($where)->find();
        if (!$model) {
            return parent::fail('该数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateStoreGrade::class, $post, 'edit');

            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('PUT')
            ->addRow('title', 'input', '等级名称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('sort', 'input', '等级排序', '0', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('expire_day', 'input', '使用期限(天)', '0', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('is_default', 'radio', '是否默认', '0', [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => StoreGradeDefault::getOptions()
            ])
            ->setData($model)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 删除
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-02
     */
    public function del(Request $request)
    {
        $id = $request->get('id');
        $model = $this->model;
        if (!$model->destroy($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }
}
