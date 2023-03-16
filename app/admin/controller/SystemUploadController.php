<?php

namespace app\admin\controller;

use app\admin\model\SystemUpload;
use app\BaseController;
use app\service\Upload;
use Exception;
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
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        list($where, $orderBy, $limit) = $this->getParams($request);
        $data = SystemUpload::with(['category'])
            ->where($where)
            ->order($orderBy)
            ->paginate($limit)
            ->toArray();
        return parent::successRes($data);
    }

    /**
     * 修改附件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        $post = $request->post();
        if (!isset($post['id']) || !$post['id']) {
            return parent::fail('附件数据错误');
        }
        if (!SystemUpload::where(['id' => $post['id']])->strict(false)->save($post)) {
            return parent::fail('修改失败');
        }
        return parent::success('修改成功');
    }

    /**
     * 移动选中附件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-05
     * @param  Request $request
     * @return void
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
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  Request $request
     * @return void
     */
    public function del(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids)) {
            return parent::fail('删除附件出错');
        }
        if (empty($ids)) {
            return parent::fail('请选择附件');
        }
        if (!Upload::delete($ids)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除完成');
    }


    /**
     * 上传附件
     *
     * @param Request $request
     * @return void
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $cid = (int)$request->post('cid');
        if (!Upload::upload($file, [], $cid)) {
            return parent::fail('上传失败');
        }
        return parent::success('上传成功');
    }
}
