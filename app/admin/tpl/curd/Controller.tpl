<?php

namespace plugin\{PLUGIN_NAME}\app\admin\controller;

use app\CurdController;
use plugin\{PLUGIN_NAME}\app\model\{CLASS_NAME};

class {CLASS_NAME}{SUFFIX} extends CurdController
{
    /**
     * 操作模型
     * @var \app\Model
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
     * 获取表名（不包含前缀）
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $tableName = null;

    /**
     * 完整表名
     * @var string
     */
    protected $prefixTableName = null;

    /**
     * 表格规则
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $tableRule = {TABLE_RULE};

    /**
     * 表单规则
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $formRule = {FORM_RULE};

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