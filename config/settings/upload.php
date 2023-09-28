<?php
use app\admin\utils\UploadUtil;

return [
    [
        'field'         => 'upload_drive',
        'title'         => '当前使用存储',
        'value'         => 'local',
        'component'     => 'select',
        'extra'         => [
            'options'   => UploadUtil::options(),
            'control'   => UploadUtil::controlOptions(),
        ],
    ],
];
