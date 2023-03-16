<?php

namespace app\model;

use app\Model;

class SystemUploadCate extends Model
{
    /**
     * 删除前置事件
     * TODO:该功能待完善
     * 需在删除分类前置事件中，先检测是否有关联分类
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-05
     * @return void
     */
    public static function onBeforeDelete($model)
    {
    }
}
