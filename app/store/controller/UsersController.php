<?php

namespace app\store\controller;

use app\admin\builder\ListBuilder;
use app\BaseController;
use support\Request;
use app\store\model\Users;

/**
 * 用户管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class UsersController extends BaseController
{
    /**
     * 模型
     * @var Users
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
        $this->model = new Users;
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
            ->pageConfig()
            ->addColumn('platform.configs.web_name', '所属平台')
            ->addColumn('platform_app.title', '所属应用')
            ->addColumn('username', '登录账号')
            ->addColumnEle('headimg', '头像', [
                'params' => [
                    'type' => 'image',
                ],
            ])
            ->addColumnEle('status', '状态', [
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
            ->addColumn('money', '余额')
            ->addColumn('integral', '积分')
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
        $where   = [];
        $orderBy = [
            'id' => 'desc'
        ];
        $model   = $this->model;
        $data    = $model->with(['store', 'platform', 'platform_app'])
            ->where($where)
            ->order($orderBy)
            ->paginate()
            ->toArray();
        return parent::successRes($data);
    }
}