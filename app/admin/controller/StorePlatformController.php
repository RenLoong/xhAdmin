<?php

namespace app\admin\controller;

use app\admin\builder\ListBuilder;
use app\BaseController;
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
                'api'           => '/admin/StorePlatform/add',
            ], [
                'title'         => '添加商户',
            ], [
                'type'          => 'success'
            ])
            ->addRightButton('edit', '修改', [
                'type'          => 'modal',
                'api'           => '/admin/StorePlatform/edit',
            ], [
                'title'         => '修改租户',
            ], [
                'type'          => 'primary',
                'link'          => true
            ])
            ->addRightButton('del', '删除', [
                'type'          => 'confirm',
                'api'           => '/admin/StorePlatform/del',
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
        return parent::successRes([]);
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
    }
}
