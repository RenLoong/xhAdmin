<?php
use app\admin\utils\UploadUtil;

# 分组上传配置
return UploadUtil::getTabsGroup();

# 普通上传配置
// return [
//     [
//         'field'         => 'upload_drive',
//         'title'         => '当前使用存储',
//         'value'         => 'local',
//         'component'     => 'select',
//         'extra'         => [
//             'options'   => UploadUtil::options(),
//             'control'   => UploadUtil::controlOptions(),
//         ],
//     ],
// ];
