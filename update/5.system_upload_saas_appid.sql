CREATE PROCEDURE system_upload_saas_appid()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_upload' AND column_name = 'saas_appid')
	THEN
		ALTER TABLE `yc_system_upload` ADD COLUMN `saas_appid` int(11) NULL COMMENT '应用项目ID' AFTER `store_id`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_upload' AND column_name = 'saas_appid')
	THEN
		ALTER TABLE `yc_system_upload` MODIFY COLUMN `saas_appid` int(11) NULL COMMENT '应用项目ID' AFTER `store_id`;
	END IF;
END;

CALL system_upload_saas_appid();

DROP PROCEDURE IF EXISTS system_upload_saas_appid;