<?php

namespace app\admin\controller;

use app\BaseController;
use support\Request;

/**
 * 后台首页
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-21
 */
class IndexController extends BaseController
{
    /**
     * 清理服务端缓存
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-21
     * @param  Request $request
     * @return void
     */
    public function clear(Request $request)
    {
        return parent::success('缓存清理成功');
    }

    /**
     * 解除UI锁定
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-21
     * @param  Request $request
     * @return void
     */
    public function lock(Request $request)
    {
        return parent::success('解锁成功');
    }
}
