<?php

namespace app\admin\controller;

use app\admin\model\SystemUploadCate;
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
    /**
     * 获取分类列表
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $data = SystemUploadCate::order(['sort' => 'asc'])->select()->toArray();
        return parent::successRes($data);
    }

    /**
     * 保存或修改
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  Request $request
     * @return void
     */
    public function form(Request $request)
    {
        $post = $request->post();
        // 数据验证
        $scene = isset($post['id']) && $post['id'] ? 'edit' : 'add';
        hpValidate(ValidateSystemUploadCate::class, $post, $scene);

        $model = new SystemUploadCate;
        if (isset($post['id']) && $post['id']) {
            $model = SystemUploadCate::where(['id' => $post['id']])->find();
            if (!$model) {
                return parent::fail('该分类不存在');
            }
        }
        if (!$model->save($post)) {
            return parent::fail('保存失败');
        }
        return parent::success('保存成功');
    }

    /**
     * 添加
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
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
     * @DateTime 2023-03-11
     * @param  Request $request
     * @return void
     */
    public function edit(Request $request)
    {
    }

    /**
     * 删除分类
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  Request $request
     * @return void
     */
    public function del(Request $request)
    {
        $id = $request->get('id');
        if (!SystemUploadCate::destroy($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }
}
