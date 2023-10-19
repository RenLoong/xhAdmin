UPDATE `yc_system_upload` SET `adapter` = 'local' WHERE `adapter` = 'public'
UPDATE `yc_system_upload` SET `adapter` = 'aliyun' WHERE `adapter` = 'oss'
UPDATE `yc_system_upload` SET `adapter` = 'qcloud' WHERE `adapter` = 'cos'