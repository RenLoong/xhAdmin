<?php

namespace plugin\{PLUGIN_NAME}\app\admin\controller;

use app\CurdController;
use plugin\{PLUGIN_NAME}\app\admin\model\{CLASS_NAME};

class {CLASS_NAME}{SUFFIX} extends CurdController
{
    /**
     * 操作模型
     * @var \plugin\{PLUGIN_NAME}\app\admin\model\{CLASS_NAME}
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $model;

    /**
     * 模型名称
     * @var string
     */
    protected $modelName = null;

    /**
     * 完整表名
     * @var string
     */
    protected $prefixTableName = null;

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