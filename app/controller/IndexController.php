<?php

namespace app\controller;

use app\BaseController;
use support\Request;

/**
 * 默认控制器
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
class IndexController extends BaseController
{
    /**
     * 首页视图
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function index(Request $request)
    {
        return redirect('/store/');
    }
}
