CREATE PROCEDURE users_saas_appid()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_users' AND column_name = 'saas_appid')
	THEN
		ALTER TABLE `yc_users` ADD COLUMN `saas_appid` int(11) NULL COMMENT '应用项目ID' AFTER `store_id`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store_app' AND column_name = 'saas_appid')
	THEN
		ALTER TABLE `yc_users` MODIFY COLUMN `saas_appid` int(11) NULL COMMENT '应用项目ID' AFTER `store_id`;
	END IF;
END;

CALL users_saas_appid();

DROP PROCEDURE IF EXISTS users_saas_appid;