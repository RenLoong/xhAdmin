<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\model\SystemAdminRole;
use app\admin\model\SystemAuthRule;
use app\BaseController;
use app\utils\DataMgr;
use support\Request;

class SystemAdminRoleController extends BaseController
{

    /**
     * 表格列
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  Request $request
     * @return void
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
                'api'           => '/admin/SystemAdminRole/add',
            ], [], [
                'type'          => 'success'
            ])
            ->addRightButton('auth', '授权', [
                'api'           => '/admin/SystemAdminRole/auth',
            ], [], [
                'type'          => 'warning',
                'link'          => true
            ])
            ->addRightButton('edit', '修改', [
                'api'           => '/admin/SystemAdminRole/edit',
            ], [], [
                'type'          => 'primary',
                'link'          => true
            ])
            ->addRightButton('del', '删除', [
                'type'          => 'confirm',
                'api'           => '/admin/SystemAdminRole/del',
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
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 10);
        $admin_id = hp_admin_id();
        $where = [
            ['pid', '=', $admin_id],
        ];
        $data = SystemAdminRole::where($where)->paginate($page)->toArray();
        return parent::successRes($data);
    }

    /**
     * 添加
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  Request $request
     * @return void
     */
    public function add(Request $request)
    {
        if ($request->method() == 'POST') {
            $post = $request->post();
            $admin_id = hp_admin_id();
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
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  Request $request
     * @return void
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
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  Request $request
     * @return void
     */
    public function del(Request $request)
    {
        $id = $request->get('id');
        if (!SystemAdminRole::destroy($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }

    /**
     * 角色授权
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  Request $request
     * @return void
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
            $model->rule = $post['rule'];
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        // 获取全部权限规则
        $rule = SystemAuthRule::order('sort asc,id asc')->select()->toArray();
        $rule = DataMgr::channelLevel($rule, '', '', 'path', 'pid');
        // 拼接规则权限为视图所需要的数组
        $authRule = $this->getAuthRule($rule);
        // 获取默认规则
        $rules = self::getDefaultRule();
        // 合并已授权规则
        if (!empty($rules)) {
            $model['rule'] = array_merge($rules, $model['rule']);
        }
        // 渲染页面
        $builder = new FormBuilder;
        $view = $builder
            ->setMethod('PUT')
            ->addRow('title', 'input', '部门名称', '', [
                'disabled'      => true
            ])
            ->addRow('rule', 'tree', '权限授权', [], [
                'data'                      => $authRule,
                'showCheckbox'              => true,
                'defaultExpandAll'          => true,
            ])
            ->setData($model)
            ->create();
        return parent::successRes($view);
    }

    /**
     * 获取默认系统规则
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-10
     * @return array
     */
    private static function getDefaultRule(): array
    {
        return SystemAuthRule::where(['is_default' => '1'])->column('path');
    }

    /**
     * 获取权限列表
     *
     * @param array $rule
     * @return array
     */
    private function getAuthRule(array $rule): array
    {
        $data = [];
        $i = 0;
        foreach ($rule as $value) {
            // 默认选选中
            $disabled = $value['is_default'] == '1' ? true : false;
            // 组装树状格式数据
            $data[$i]['title']          = $value['title'];
            $data[$i]['id']             = $value['path'];
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
