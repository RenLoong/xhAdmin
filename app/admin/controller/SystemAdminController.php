<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\logic\SystemAdminRole;
use app\admin\model\SystemAdmin;
use app\admin\validate\SystemAdmin as ValidateSystemAdmin;
use app\BaseController;
use app\enum\AdminStatus;
use support\Request;

/**
 * 管理员列表
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-08
 */
class SystemAdminController extends BaseController
{
    /**
     * 获取表格
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data = $builder
            ->addActionOptions('操作', [
                'width'         => 150
            ])
            ->pageConfig()
            ->addTopButton('add', '添加', [
                'api'           => 'admin/SystemAdmin/add',
                'path'          => '/SystemAdmin/add',
            ], [], [
                'type'          => 'success'
            ])
            ->addRightButton('edit', '修改', [
                'api'           => 'admin/SystemAdmin/edit',
                'path'          => '/SystemAdmin/edit',
            ], [], [
                'type'          => 'primary',
                'link'          => true
            ])
            ->addRightButton('del', '删除', [
                'type'          => 'confirm',
                'api'           => 'admin/SystemAdmin/del',
                'method'        => 'delete',
            ], [
                'title'         => '温馨提示',
                'content'       => '是否确认删除该数据',
            ], [
                'type'          => 'danger',
                'link'          => true
            ])
            ->addColumn('username', '登录账号')
            ->addColumn('nickname', '用户昵称')
            ->addColumnEle('headimg', '用户头像', [
                'params'        => [
                    'type'      => 'image',
                ],
            ])
            ->addColumn('role.title', '所属部门')
            ->addColumn('last_login_ip', '最近登录IP')
            ->addColumn('last_login_time', '最近登录时间')
            ->addColumnEle('status', '当前状态', [
                'width'             => 90,
                'params'            => [
                    'type'          => 'tags',
                    'options'       => ['禁用', '正常'],
                    'style'         => [
                        [
                            'type'  => 'error',
                        ],
                        [
                            'type'  => 'success',
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
     * @DateTime 2023-04-29
     */
    public function index(Request $request)
    {
        $admin_id = hp_admin_id();
        $where = [
            ['pid', '=', $admin_id]
        ];
        $data = SystemAdmin::with(['role'])
            ->where($where)
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
     * @DateTime 2023-04-29
     */
    public function add(Request $request)
    {
        if ($request->method() == 'POST') {
            $post = $request->post();
            $admin_id = hp_admin_id();
            $post['pid'] = $admin_id;

            // 数据验证
            hpValidate(ValidateSystemAdmin::class, $post, 'add');

            // 处理头像
            $post['headimg'] = current($post['headimg']);

            $model = new SystemAdmin;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $admin_id = hp_admin_id();
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('POST')
            ->addRow('role_id', 'select', '所属部门', '', [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => SystemAdminRole::getOptions($admin_id)
            ])
            ->addRow('status', 'radio', '用户状态', '1', [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => AdminStatus::getOptions()
            ])
            ->addRow('username', 'input', '登录账号', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col'       => [
                    'span'  => 12
                ],
                'placeholder'   => '不填写，则不修改密码',
            ])
            ->addRow('nickname', 'input', '用户昵称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addComponent('headimg', 'uploadify', '用户头像', '', [
                'col'           => [
                    'span'      => 12
                ],
                'props'         => [
                    'format'    => ['jpg', 'png', 'gif']
                ],
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 修改数据
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $where = [
            ['id', '=', $id]
        ];
        $model = SystemAdmin::where($where)->find();
        if (!$model) {
            return parent::fail('该数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateSystemAdmin::class, $post, 'edit');

            // 空密码，不修改
            if (empty($post['password'])) {
                unset($post['password']);
            }
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $admin_id = hp_admin_id();
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('PUT')
            ->addRow('role_id', 'select', '所属部门', '', [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => SystemAdminRole::getOptions($admin_id)
            ])
            ->addRow('status', 'radio', '用户状态', '1', [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => AdminStatus::getOptions()
            ])
            ->addRow('username', 'input', '登录账号', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col'       => [
                    'span'  => 12
                ],
                'placeholder'   => '不填写，则不修改密码',
            ])
            ->addRow('nickname', 'input', '用户昵称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addComponent('headimg', 'uploadify', '用户头像', '', [
                'col'           => [
                    'span'      => 12
                ],
                'props'         => [
                    'format' => ['jpg', 'png', 'gif']
                ],
            ])
            ->setData($model)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 修改自身数据
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function editSelf(Request $request)
    {
        $admin_id = hp_admin_id('hp_admin');
        $where    = [
            ['id', '=', $admin_id]
        ];
        $model    = SystemAdmin::where($where)->find();
        if (!$model) {
            return parent::fail('该数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateSystemAdmin::class, $post, 'editSelf');

            // 空密码，不修改
            if (empty($post['password'])) {
                unset($post['password']);
            }
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            $adminModel = SystemAdmin::with(['role'])->where($where)->find();

            $session = $request->session();
            $session->set('hp_admin', $adminModel);
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data    = $builder
            ->setMethod('PUT')
            ->addRow('username', 'input', '登录账号', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col'         => [
                    'span' => 12
                ],
                'placeholder' => '不填写，则不修改密码',
            ])
            ->addRow('nickname', 'input', '用户昵称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addComponent('headimg', 'uploadify', '用户头像', '', [
                'col'   => [
                    'span' => 12
                ],
                'props' => [
                    'ext'  => ['jpg', 'png', 'gif']
                ],
            ])
            ->setData($model)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 删除数据
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function del(Request $request)
    {
        $id = $request->post('id');
        if (!SystemAdmin::destroy($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }
}
