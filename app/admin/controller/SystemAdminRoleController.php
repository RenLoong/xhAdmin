<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\model\SystemAdminRole;
use app\admin\model\SystemAuthRule;
use app\BaseController;
use app\utils\DataMgr;
use support\Request;

/**
 * 部门管理
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
class SystemAdminRoleController extends BaseController
{

    /**
     * 表格
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
            ->addActionOptions('操作')
            ->pageConfig()
            ->addTopButton('add', '添加', [
                'api'           => 'admin/SystemAdminRole/add',
                'path'          => '/SystemAdminRole/add',
            ], [], [
                'type'          => 'success'
            ])
            ->addRightButton('auth', '授权', [
                'api'           => 'admin/SystemAdminRole/auth',
                'path'          => '/SystemAdminRole/auth',
            ], [], [
                'type'          => 'warning',
                'link'          => true
            ])
            ->addRightButton('edit', '修改', [
                'api'           => 'admin/SystemAdminRole/edit',
                'path'          => '/SystemAdminRole/edit',
            ], [], [
                'type'          => 'primary',
                'link'          => true
            ])
            ->addRightButton('del', '删除', [
                'type'          => 'confirm',
                'api'           => 'admin/SystemAdminRole/del',
                'method'        => 'delete',
            ], [
                'title'         => '温馨提示',
                'content'       => '是否确认删除该数据',
            ], [
                'type'          => 'danger',
                'link'          => true
            ])
            ->addColumn('id', '序号', [
                'width'         => 90,
            ])
            ->addColumn('create_at', '创建时间')
            ->addColumn('title', '部门名称')
            ->addColumn('rule_name', '部门权限')
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
        $admin_id = hp_admin_id('hp_admin');
        $where = [
            ['pid', '=', $admin_id],
        ];
        $data = SystemAdminRole::where($where)->paginate()->toArray();
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
        $admin_id = hp_admin_id('hp_admin');
        if ($request->method() == 'POST') {
            $post = $request->post();
            $post['pid'] = $admin_id;
            // 默认权限
            $post['rule'] = self::getDefaultRule();
            $model = new SystemAdminRole;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $view = $builder
            ->setMethod('POST')
            ->addRow('title', 'input', '部门名称')
            ->create();
        return parent::successRes($view);
    }

    /**
     * 修改
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
            ['id', '=', $id],
        ];
        $model = SystemAdminRole::where($where)->find();
        if (!$model) {
            return parent::fail('数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $view = $builder
            ->setMethod('PUT')
            ->addRow('title', 'input', '部门名称')
            ->setData($model)
            ->create();
        return parent::successRes($view);
    }

    /**
     * 删除
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function del(Request $request)
    {
        $id = $request->post('id');
        if (!SystemAdminRole::where(['id'=> $id])->delete()) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }

    /**
     * 授权
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function auth(Request $request)
    {
        $id = $request->get('id');
        $where = [
            ['id', '=', $id],
        ];
        $model = SystemAdminRole::where($where)->find();
        if (!$model) {
            return parent::fail('数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();
            if (empty($post['rule'])) {
                return $this->fail('规则授权错误');
            }
            $rule = $post['rule'];
            $where   = [
                ['id','in',$rule]
            ];
            $paths = SystemAuthRule::where($where)->column('path');
            $model->rule = array_values(array_filter($paths));
            if (!$model->save()) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        // 查询已授权关联规则ID
        $where         = [
            ['path','in',$model['rule']]
        ];
        $model['rule'] = SystemAuthRule::where($where)->column('id');
        // 获取全部权限规则
        $rule = SystemAuthRule::order(['sort'=>'asc','id'=>'asc'])
        ->select()
        ->toArray();
        $rule = DataMgr::channelLevel($rule, 0, '', 'id', 'pid');
        // 拼接规则权限为视图所需要的数组
        $authRule = $this->getAuthRule($rule);
        // 获取默认规则
        $defaultActive = self::getDefaultRule();
        // 默认选中规则 与 已授权规则合并
        if (!empty($defaultActive)) {
            $model['rule'] = array_values(array_unique(array_merge($defaultActive, $model['rule'])));
        }
        // 渲染页面
        $builder = new FormBuilder;
        $view = $builder
            ->setMethod('PUT')
            ->addRow('title', 'input', '部门名称', '', [
                'disabled'      => true
            ])
            ->addRow('rule', 'tree', '权限授权', [], [
                // 节点数据
                'data'                      => $authRule,
                // 是否展开全部
                'defaultExpandAll'          => true,
                // 透传组件属性
                'props'                     => [
                    // 是否允许多选节点
                    'multiple'              => true,
                    // 是否关联子节点
                    // 'cascade'               => true,
                    // 选择框位置
                    'checkboxPlacement'     => 'left',
                    // 子节点为块元素
                    'blockNode'             => true
                ],
            ])
            ->setData($model)
            ->create();
        return parent::successRes($view);
    }

    /**
     * 获取默认选中规则
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    private static function getDefaultRule(): array
    {
        return SystemAuthRule::where(['is_default' => '1'])->column('id');
    }

    /**
     * 获取权限列表
     * @param array $rule
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    private function getAuthRule(array $rule): array
    {
        $data = [];
        $i = 0;
        foreach ($rule as $value) {
            // 默认选选中
            $disabled = false;
            $disabled = $value['is_default'] === '1';
            // 组装树状格式数据
            $label                = $value['title'];
            if ($value['path']) {
                $label .= "（{$value['path']}）";
            }
            $data[$i]['label']          = $label;
            $data[$i]['key']            = $value['id'];
            $data[$i]['disabled']       = $disabled;
            $data[$i]['level']          = $value['_level'];
            if ($value['children']) {
                $data[$i]['children']   = $this->getAuthRule($value['children']);
            }
            $i++;
        }
        return $data;
    }
}
