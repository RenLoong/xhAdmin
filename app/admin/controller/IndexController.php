<?php

namespace app\admin\controller;

use app\BaseController;
use support\Request;

/**
 * Undocumented class
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-02-27
 */
class IndexController extends BaseController
{
    /**
     * 后台视图
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-08
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        return view('index');
    }

    /**
     * 清理服务端缓存
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-03
     * @return void
     */
    public function clear()
    {
        return parent::success('缓存清理成功');
    }

    /**
     * 解除UI锁定
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-03
     * @return void
     */
    public function lock()
    {
        return parent::success('解锁成功');
    }
}
