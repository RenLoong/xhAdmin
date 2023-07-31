DROP PROCEDURE IF EXISTS users_saas_appid;

CREATE PROCEDURE users_saas_appid()
BEGIN
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store_app' AND column_name = 'appid')
	THEN
		ALTER TABLE `yc_users` CHANGE COLUMN `appid` `saas_appid` int(10) NULL DEFAULT NULL COMMENT '应用项目ID' AFTER `store_id`;
	END IF;
END;

CALL users_saas_appid();
