<?php

namespace app\common\model;

use app\Model;
use think\model\concern\SoftDelete;

class SystemUploadCate extends Model
{
    # 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}
