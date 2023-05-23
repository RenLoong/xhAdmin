<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\model\SystemUpload;
use app\BaseController;
use app\service\Upload;
use support\Request;

/**
 * @title 附件管理
 * @desc 默认使用插件：https://github.com/shopwwi/webman-filesystem
 * 在线手册:https://www.workerman.net/plugin/19
 * @author 楚羽幽 <admin@hangpu.net>
 */
class SystemUploadController extends BaseController
{
    /**
     * 获取附件列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function index(Request $request)
    {
        list($where, $orderBy, $limit) = $this->getParams($request);
        $orderBy = empty($orderBy) ? ['id'=>'desc'] : $orderBy;
        $data = SystemUpload::with(['category'])
            ->where($where)
            ->order($orderBy)
            ->paginate($limit)
            ->toArray();
        return parent::successRes($data);
    }

    /**
     * 修改附件
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function edit(Request $request)
    {
        $id    = $request->get('id','');
        $where = [
            ['id','=',$id]
        ];
        $model = SystemUpload::where($where)->find();
        if (!$model) {
            return parent::fail('该附件不存在');
        }
        if ($request->method() === 'PUT') {
            $post = $request->post();
            if (!$model->save($post)) {
                return parent::fail('修改失败');
            }
            return parent::success('修改成功');
        }
        else {
            $builder = new FormBuilder;
            $data    = $builder
                ->setMethod('PUT')
                ->addRow('title', 'input', '附件名称')
                ->addRow('path', 'input', '文件地址', '', [
                    'disabled' => true,
                ])
                ->addRow('filename', 'input', '文件名称','',[
                    'disabled'  => true,
                    'col'       => [
                        'span'  => 12
                    ]
                ])
                ->addRow('format', 'input', '文件格式','',[
                    'disabled'  => true,
                    'col'       => [
                        'span'  => 12
                    ]
                ])
                ->addRow('size_format', 'input', '文件大小','',[
                    'disabled'  => true,
                    'col'       => [
                        'span'  => 12
                    ]
                ])
                ->addRow('adapter', 'input', '选定器','',[
                    'disabled'  => true,
                    'col'       => [
                        'span'  => 12
                    ]
                ])
                ->setData($model)
                ->create();
            return parent::successRes($data);
        }
    }

    /**
     * 移动选中附件
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function move(Request $request)
    {
        $cate_id = $request->post('cate_id');
        $ad_id = $request->post('ids');
        if (!$cate_id) {
            return parent::fail('请选择移动的分类');
        }
        if (!$ad_id) {
            return parent::fail('附件选择错误');
        }
        if (!is_array($ad_id)) {
            return parent::fail('请选择移动的附件');
        }
        $where[] = ['id', 'in', $ad_id];
        SystemUpload::where($where)->save(['cid' => $cate_id]);
        return parent::success('附件移动完成');
    }

    /**
     * 删除选中附件
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function del(Request $request)
    {
        $id = (int)$request->post('id',0);
        if (!$id) {
            return parent::fail('请选择需要删除的附件');
        }
        if (!Upload::delete($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除完成');
    }


    /**
     * 上传附件
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $cid = (int)$request->post('cid');
        if (!$cid) {
            return parent::fail('请选择分类上传');
        }
        if (!Upload::upload($file, $cid)) {
            return parent::fail('上传失败');
        }
        return parent::success('上传成功');
    }
}
