<?php

namespace app\store\controller;

use app\admin\builder\FormBuilder;
use app\admin\validate\StorePlatform;
use app\BaseController;
use app\enum\PlatformTypes;
use app\enum\StorePlatformStatus;
use app\service\Upload;
use app\store\validate\Store;
use support\Request;

/**
 * 租户信息管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StoreController extends BaseController
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
        $this->model = new \app\store\model\Store;
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
        $store_id = hp_admin_id('hp_store');
        $model = $this->model;
        $where = [
            ['id','=',$store_id],
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该数据不存在');
        }
        if ($request->method() == 'POST') {
            $post = $request->post();

            $post['store_id'] = $model->store_id;

            // 数据验证
            hpValidate(Store::class, $post, 'edit');

            $post['logo'] = Upload::path($post['logo']);

            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST')
        ->addRow('title', 'input', '租户名称', '', [
            'col' => [
                'span' => 12
            ],
        ])
        ->addRow('password', 'input', '登录密码', '', [
            'col' => [
                'span' => 12
            ],
        ])
        ->addRow('contact', 'input', '联系人姓名', '', [
            'col' => [
                'span' => 12
            ],
        ])
        ->addRow('mobile', 'input', '联系电话', '', [
            'col' => [
                'span' => 12
            ],
        ])
        ->addComponent('logo', 'uploadify', '租户图标', '', [
            'col'   => [
                'span' => 6
            ],
            'props' => [
                'type'   => 'image',
                'format' => ['jpg', 'png', 'gif']
            ],
        ]);
        $builder->setData($model);
        $data = $builder->create();
        return parent::successRes($data);
    }
}