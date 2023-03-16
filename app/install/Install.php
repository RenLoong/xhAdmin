<?php

namespace app\install;

use app\BaseController;
use support\Request;

/**
 * 框架安装
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-11
 */
class Install extends BaseController
{
    /**
     * 框架协议
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  Request $request
     * @return void
     */
    public function step1(Request $request)
    {
    }

    /**
     * 环境检测
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  Request $request
     * @return void
     */
    public function step2(Request $request)
    {
    }

    /**
     * 设置云服务/创始人信息
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  Request $request
     * @return void
     */
    public function step3(Request $request)
    {
    }

    /**
     * 安装进行
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  Request $request
     * @return void
     */
    public function step4(Request $request)
    {
    }

    /**
     * 安装完成
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  Request $request
     * @return void
     */
    public function step5(Request $request)
    {
        return parent::success('ok');
    }
}
