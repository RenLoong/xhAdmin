<?php

namespace app\admin\controller;

use app\common\trait\SettingsTrait;
use app\common\BaseController;
use support\Request;

/**
 * 系统配置项
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
class SystemConfigController extends BaseController
{
    # 系统配置项
    use SettingsTrait;

    /**
     * 附件库设置
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function uploadify(Request $request)
    {
        return $this->settings($request);
    }
}