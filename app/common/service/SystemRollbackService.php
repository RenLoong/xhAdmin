<?php
namespace app\common\service;
use support\Request;

/**
 * 框架更新回滚服务类
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class SystemRollbackService extends SystemUpdateService
{
    /**
     * 构造函数
     * @param \support\Request $request
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * 开始进行回滚
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function startRollback()
    {
    }
}