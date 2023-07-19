<?php

namespace app\model;

use app\Model;
use app\service\Upload;
use think\model\concern\SoftDelete;

/**
 * 商户平台配置
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StorePlatformConfig extends Model
{
    # 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * 获取数据外链地址
     * @param mixed $value
     * @param mixed $data
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function getConfigValueAttr($value, $data)
    {
        if ($value && $data['form_type'] === 'uploadify') {
            $value = Upload::url((string) $value);
        }
        return $value;
    }
}