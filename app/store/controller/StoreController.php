<?php

namespace app\store\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\model\Store;
use app\admin\validate\StorePlatform;
use app\BaseController;
use app\enum\PlatformTypes;
use app\enum\StorePlatformStatus;
use app\service\Upload;
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
        $this->model = new \app\store\model\StorePlatform;
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
        $id = $request->get('id', '');
        $model = $this->model;
        $where = [
            ['id','=',$id],
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该数据不存在');
        }
        if ($request->method() == 'POST') {
            $post = $request->post();

            $post['store_id'] = $model->store_id;

            // 数据验证
            hpValidate(StorePlatform::class, $post, 'edit');

            $post['logo'] = Upload::path($post['logo']);

            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        $builder->addRow('platform_type', 'select', '平台类型', 'other', [
            'col'     => [
                'span' => 12
            ],
            'options' => PlatformTypes::getOptions()
        ]);
        $builder->addRow('title', 'input', '平台名称', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $builder->addComponent('logo', 'uploadify', '平台图标', '', [
            'col'   => [
                'span' => 12
            ],
            'props' => [
                'format' => ['jpg', 'png', 'gif']
            ],
        ]);
        $builder->addRow('status', 'radio', '平台状态', '1', [
            'col'     => [
                'span' => 12
            ],
            'options' => StorePlatformStatus::getOptions()
        ]);
        $builder->addRow('remarks', 'textarea', '平台备注', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        $builder->setData($model);
        $data = $builder->create();
        return parent::successRes($data);
    }
}