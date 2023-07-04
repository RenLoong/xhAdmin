ALTER TABLE `yc_system_upload_cate` ADD COLUMN `store_id` int NULL DEFAULT NULL COMMENT '租户ID' AFTER `delete_time`;
ALTER TABLE `yc_system_upload_cate` ADD COLUMN `platform_id` int NULL DEFAULT NULL COMMENT '平台ID' AFTER `store_id`;
ALTER TABLE `yc_system_upload_cate` ADD COLUMN `appid` int NULL DEFAULT NULL COMMENT '应用ID' AFTER `platform_id`;
ALTER TABLE `yc_system_upload_cate` ADD COLUMN `uid` int NULL DEFAULT NULL COMMENT '用户ID' AFTER `appid`;