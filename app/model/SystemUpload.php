<?php

namespace app\model;

use app\Model;
use app\service\Upload;

class SystemUpload extends Model
{
    // 模型输出字段
    protected $append = [
        'url',
        'size_format',
    ];

    /**
     * 关联附件分类
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @return void
     */
    public function category()
    {
        return $this->hasOne(SystemUploadCate::class, 'id', 'cid');
    }

    /**
     * 追加URL参数
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  type $value
     * @param  type $data
     * @return void
     */
    protected function getUrlAttr($value, $data)
    {
        return Upload::url((string) $data['path']);
    }

    /**
     * 追加文件大小
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  type $value
     * @param  type $data
     * @return void
     */
    protected function getSizeFormatAttr($value, $data)
    {
        $size = isset($data['size']) ? get_size($data['size']) : '0KB';
        return $size;
    }
}
