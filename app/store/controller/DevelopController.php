<?php

namespace app\store\controller;

use app\common\BaseController;
use app\common\builder\FormBuilder;
use app\store\model\StoreApp;
use support\Request;
use think\facade\Db;

/**
 * 开发者项目
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
class DevelopController extends BaseController
{
    /**
     * 模型
     * @var StoreApp
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
        $this->model = new StoreApp;
    }

    /**
     * 创建开发者应用
     * @param \support\Request $request
     * @return mixed
     * @author John
     */
    public function create(Request $request)
    {
        if ($request->method() === 'POST') {
            $post = $request->post();
            $store = $request->user;
            $post['store_id'] = $store['id'];
            $post['status'] = '20';

            hpValidate(StoreApp::class, $post, 'add');

            Db::startTrans();
            try {
                Db::commit();
                return $this->success('操作成功');
            } catch (\Throwable $e) {
                Db::rollback();
                return $this->fail($e->getMessage());
            }
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        $builder->addRow('title111', 'input', '项目名称', '', [
            'col' => 12,
        ]);
        $builder->addRow('title11', 'input', '项目类型', '', [
            'col' => 12,
        ]);
        $builder->addRow('title1', 'input', '团队标识', '', [
            'col' => 12,
        ]);
        $builder->addRow('title2', 'input', '应用标识', '', [
            'col' => 12,
        ]);
        $builder->addRow('type1', 'input', '系统配置项', '', [
            'col' => 12,
        ]);
        $builder->addRow('type2', 'input', '权限管理', '', [
            'col' => 12,
        ]);
        $builder->addRow('username', 'input', '超管账号', '', [
            'col' => 12,
        ]);
        $builder->addRow('password', 'password', '登录密码', '', [
            'col' => 12,
        ]);
        $data = $builder->create();
        return parent::successRes($data);
    }
}