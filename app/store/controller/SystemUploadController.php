<?php

namespace app\store\controller;

use app\common\trait\UploadTrait;
use app\common\BaseController;

/**
 * @title 附件管理
 * @desc 默认使用插件：https://github.com/shopwwi/webman-filesystem
 * 在线手册:https://www.workerman.net/plugin/19
 * @author 楚羽幽 <admin@hangpu.net>
 */
class SystemUploadController extends BaseController
{
    # 附件库
    use UploadTrait;

    /**
     * 渠道ID
     * @var int|null
     */
    protected $store_id = null;

    /**
     * 构造函数
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function initialize()
    {
        $this->store_id = $this->request->user['id'];
    }
}
