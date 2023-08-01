DROP PROCEDURE IF EXISTS system_upload_cate_saas_appid;

CREATE PROCEDURE system_upload_cate_saas_appid()
BEGIN
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_upload_cate' AND column_name = 'appid')
	THEN
		ALTER TABLE `yc_system_upload_cate` CHANGE COLUMN `appid` `saas_appid` int(10) NULL DEFAULT NULL COMMENT '应用项目ID' AFTER `store_id`;
	END IF;
END;

CALL system_upload_cate_saas_appid();
