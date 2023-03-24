<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\logic\StoreGrade;
use app\admin\model\Store;
use app\admin\validate\Store as ValidateStore;
use app\BaseController;
use app\enum\StoreStatus;
use support\Request;

/**
 * 租户管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StoreController extends BaseController
{
    /**
     * 表格列
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @param  Request $request
     * @return void
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data = $builder
            ->addActionOptions('操作', [
                'width'         => 210
            ])
            ->pageConfig()
            ->addTopButton('add', '添加', [
                'type'          => 'modal',
                'api'           => '/admin/Store/add',
            ], [
                'title'         => '添加商户',
            ], [
                'type'          => 'success'
            ])
            ->addRightButton('platforms', '平台', [
                'type'          => 'table',
                'api'           => '/admin/StorePlatform/index',
            ], [], [
                'type'          => 'primary',
                'link'          => true
            ])
            ->addRightButton('edit', '修改', [
                'type'          => 'modal',
                'api'           => '/admin/Store/edit',
            ], [
                'title'         => '修改租户',
            ], [
                'type'          => 'primary',
                'link'          => true
            ])
            ->addRightButton('del', '删除', [
                'type'          => 'confirm',
                'api'           => '/admin/Store/del',
                'method'        => 'delete',
            ], [
                'title'         => '温馨提示',
                'content'       => '是否确认删除该数据',
            ], [
                'type'          => 'danger',
                'link'          => true
            ])
            ->addColumn('title', '租户名称')
            ->addColumn('username', '租户账号')
            ->addColumnEle('logo', '租户图标', [
                'params'        => [
                    'type'      => 'image',
                ],
            ])
            ->addColumnEle('status', '租户状态', [
                'width'         => 100,
                'params'        => [
                    'type'      => 'tags',
                    'options'   => ['冻结', '正常'],
                    'props'     => [
                        [
                            'type'  => 'danger'
                        ],
                        [
                            'type'  => 'success'
                        ],
                    ],
                ],
            ])
            ->addColumn('grade.title', '租户等级')
            ->addColumn('platform_wechat', '平台资产')
            ->addColumn('expire_time', '过期时间')
            ->create();
        return parent::successRes($data);
    }

    /**
     * 列表
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $data = Store::order(['id' => 'desc'])
            ->paginate()
            ->toArray();
        return parent::successRes($data);
    }

    /**
     * 添加
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @param  Request $request
     * @return void
     */
    public function add(Request $request)
    {
        if ($request->method() == 'POST') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateStore::class, $post, 'add');

            $model = new Store;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('POST')
            ->addRow('username', 'input', '租户账号', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('title', 'input', '租户名称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('remarks', 'textarea', '租户备注', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('contact', 'input', '联系人姓名', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('mobile', 'input', '联系电话', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addComponent('logo', 'uploadify', '租户图标', '', [
                'col'           => [
                    'span'      => 8
                ],
                'props'         => [
                    'type'      => 'image',
                    'ext'       => ['jpg', 'png', 'gif']
                ],
            ])
            ->addRow('status', 'radio', '租户状态', '1', [
                'col'       => [
                    'span'  => 8
                ],
                'options'   => StoreStatus::getOptions()
            ])
            ->addRow('grade_id', 'select', '租户等级', '', [
                'col'       => [
                    'span'  => 8
                ],
                'options'   => StoreGrade::getOptions()
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 修改
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @param  Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $where = [
            'id'    => $id
        ];
        $model = Store::where($where)->find();
        if (!$model) {
            return parent::fail('该数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateStore::class, $post, 'edit');

            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('PUT')
            ->addRow('username', 'input', '租户账号', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('title', 'input', '租户名称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('remarks', 'textarea', '租户备注', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('contact', 'input', '联系人姓名', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('mobile', 'input', '联系电话', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addComponent('logo', 'uploadify', '租户图标', '', [
                'col'           => [
                    'span'      => 8
                ],
                'props'         => [
                    'type'      => 'image',
                    'ext'       => ['jpg', 'png', 'gif']
                ],
            ])
            ->addRow('status', 'radio', '租户状态', '1', [
                'col'       => [
                    'span'  => 8
                ],
                'options'   => StoreStatus::getOptions()
            ])
            ->addRow('grade_id', 'select', '租户等级', '', [
                'col'       => [
                    'span'  => 8
                ],
                'options'   => StoreGrade::getOptions()
            ])
            ->setData($model)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 删除
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @param  Request $request
     * @return void
     */
    public function del(Request $request)
    {
        $id = $request->get('id');
        if (!Store::destroy($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }
}
