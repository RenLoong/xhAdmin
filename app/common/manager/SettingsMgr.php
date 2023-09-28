<?php
namespace app\common\manager;

use app\common\model\SystemConfig;
use support\Request;

/**
 * 系统设置管理
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
class SettingsMgr
{
    /**
     * 请求对象
     * @var Request
     */
    protected $request = null;

    /**
     * 应用ID（null则获取系统配置）
     * @var string|null
     */
    protected $appid = null;

    /**
     * 构造函数
     * @param mixed $appid
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct($appid = null)
    {
        $this->request = request();
        $this->appid = $appid;
    }

    /**
     * 获取单项配置
     * @param mixed $name
     * @param mixed $appid
     * @return string
     * @author John
     */
    public function get(string $name)
    {
        return '';
    }

    /**
     * 获取配置分组数据
     * @param string $group
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function group(string $group)
    {
        $where = [
            'group'     => $group
        ];
        return SystemConfig::where($where)->value('value');
    }
}