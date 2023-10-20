<?php
namespace app\common\trait;

use app\common\trait\config\Config;
use app\common\trait\config\TabsConfig;
use app\common\trait\config\UploadConfig;
use app\common\utils\Json;

/**
 * 系统设置管理
 * 对外暴露接口
 * 
 * 1. settings 普通配置项
 * 2. tabs 选项卡配置项
 * 3. uploadify 附件库配置项
 * 
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
trait SettingsTrait
{
    # 使用JSON工具类
    use Json;
    # 使用基础配置
    use Config;
    # 使用选项卡配置
    use TabsConfig;
    # 使用附件库配置
    use UploadConfig;

    /**
     * 应用ID（null则获取系统配置）
     * @var string|null
     */
    protected $saas_appid = null;
}