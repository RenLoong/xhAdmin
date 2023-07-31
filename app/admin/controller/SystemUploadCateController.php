<?php

namespace app\admin\controller;

use app\common\builder\FormBuilder;
use app\common\model\SystemUpload;
use app\common\model\SystemUploadCate;
use app\admin\validate\SystemUploadCate as ValidateSystemUploadCate;
use app\BaseController;
use support\Request;

/**
 * 附件分类管理器
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-04
 */
class SystemUploadCateController extends BaseController
{
    public $model;

    /**
     * 
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-23
     */
    public function __construct()
    {
        $this->model = new SystemUploadCate();
    }

    /**
     * 获取分类列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function index(Request $request)
    {
        $where = [
            'store_id'      => null,
            'saas_appid'    => null,
            'uid'           => null,
        ];
        $data = SystemUploadCate::where($where)
        ->order(['sort' => 'asc'])
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
        if ($request->method() === 'POST') {
            $post = $request->post();
            // 数据验证
            hpValidate(ValidateSystemUploadCate::class, $post, 'add');

            $model = new SystemUploadCate;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data    = $builder
            ->setMethod('POST')
            ->addRow('title', 'input', '分类名称')
            ->addRow('dir_name', 'input', '分类标识')
            ->addRow('sort', 'input', '分类排序', '0')
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
        $id    = $request->get('id', '');
        $model = SystemUploadCate::where(['id' => $id])->find();
        if (!$model) {
            return parent::fail('该分类不存在');
        }
        if ($request->method() === 'PUT') {
            $post = $request->post();
            // 数据验证
            hpValidate(ValidateSystemUploadCate::class, $post, 'edit');
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data    = $builder
            ->setMethod('PUT')
            ->addRow('title', 'input', '分类名称')
            ->addRow('dir_name', 'input', '分类标识', '', [
                'disabled' => true,
            ])
            ->addRow('sort', 'input', '分类排序')
            ->setData($model)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 删除分类
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function del(Request $request)
    {
        $id    = $request->post('id', '');
        $model = $this->model;
        $model = $model->where(['id' => $id])->find();
        if (!$model) {
            return parent::fail('该附件分类不存在');
        }
        if ($model->is_system === '20') {
            return parent::fail('系统分类，禁止删除');
        }
        if (SystemUpload::where('cid',$id)->count()) {
            return parent::fail('该分类下已有附件，禁止删除');
        }
        if (!$model->delete()) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }
}