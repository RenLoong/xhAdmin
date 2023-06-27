<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\model\SystemConfigGroup;
use app\admin\validate\SystemConfigGroup as ValidateSystemConfigGroup;
use app\BaseController;
use support\Request;

/**
 * 配置分组
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
class SystemConfigGroupController extends BaseController
{

    /**
     * 表格配置
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
            ->addTopButton('add', '添加', [
                'api'           => 'admin/SystemConfigGroup/add',
                'path'          => '/SystemConfigGroup/add',
                'isBack'        => true,
            ], [], [
                'type'          => 'success'
            ])
            ->addRightButton('config', '配置项', [
                'api'           => 'admin/SystemConfig/index',
                'path'          => '/SystemConfig/index',
                'isBack'        => true,
                'aliasParams'   => [
                    'id'        => 'cid'
                ],
            ], [], [
                'link'          => true,
                'type'          => 'warning',
            ])
            ->addRightButton('edit', '修改', [
                'api'           => 'admin/SystemConfigGroup/edit',
                'path'          => '/SystemConfigGroup/edit',
                'isBack'        => true,
            ], [], [
                'link'          => true,
                'type'          => 'primary',
            ])
            ->addRightButton('del', '删除', [
                'type'          => 'confirm',
                'api'           => 'admin/SystemConfigGroup/del',
                'method'        => 'delete',
            ], [
                'title'         => '温馨提示',
                'content'       => '是否确认删除该数据',
            ], [
                'type'          => 'danger',
                'link'          => true
            ])
            ->addColumn('title', '名称')
            ->addColumn('name', '标识')
            ->addColumn('icon', '图标', [
                'width'         => 100
            ])
            ->addColumn('sort', '排序',[
                'width'         => 100
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
        $data = SystemConfigGroup::order(['sort'=>'asc','id'=>'asc'])
        ->select()
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

            // 数据验证
            hpValidate(ValidateSystemConfigGroup::class,$post,'add');

            $model = new SystemConfigGroup;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('POST')
            ->addRow('title', 'input', '名称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('name', 'input', '标识', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addComponent('icon', 'icons', '图标', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('sort', 'input', '排序', '0', [
                'col'       => [
                    'span'  => 12
                ],
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
     * @DateTime 2023-04-29
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $model = SystemConfigGroup::where(['id' => $id])->find();
        if (!$model) {
            return parent::fail('数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateSystemConfigGroup::class,$post,'edit');

            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('PUT')
            ->addRow('title', 'input', '名称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('name', 'input', '标识', '', [
                'col'       => [
                    'span'  => 12
                ],
                'disabled'  => true,
            ])
            ->addComponent('icon', 'icons', '图标', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('sort', 'input', '排序', '0', [
                'col'       => [
                    'span'  => 12
                ],
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
     * @DateTime 2023-04-29
     */
    public function del(Request $request)
    {
        $id = $request->post('id');
        if (!SystemConfigGroup::destroy($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }
}
