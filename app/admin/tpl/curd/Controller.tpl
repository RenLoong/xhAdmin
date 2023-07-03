<?php

namespace plugin\{PLUGIN_NAME}\app\admin\controller;
use app\CurdController;
use plugin\{PLUGIN_NAME}\app\admin\model\{CLASS_NAME};

class {CLASS_NAME}{SUFFIX} extends CurdController
{
    /**
     * 构造函数
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function __construct()
    {
        $this->model = new {CLASS_NAME};
        parent::__construct();
    }
}