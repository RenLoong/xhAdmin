DROP PROCEDURE IF EXISTS system_config_store_id;
CREATE PROCEDURE system_config_store_id()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config' AND column_name = 'store_id')
	THEN
		ALTER TABLE `yc_system_config` ADD COLUMN `store_id` int(10) NULL COMMENT '租户ID' AFTER `update_at`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config' AND column_name = 'store_id')
	THEN
		ALTER TABLE `yc_system_config` MODIFY COLUMN `store_id` int(10) NULL COMMENT '租户ID' AFTER `update_at`;
	END IF;
END;

CALL system_config_store_id();


DROP PROCEDURE IF EXISTS system_config_saas_appid;
CREATE PROCEDURE system_config_saas_appid()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config' AND column_name = 'saas_appid')
	THEN
		ALTER TABLE `yc_system_config` ADD COLUMN `saas_appid` int(10) NULL COMMENT '项目ID' AFTER `store_id`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config' AND column_name = 'saas_appid')
	THEN
		ALTER TABLE `yc_system_config` MODIFY COLUMN `saas_appid` int(10) NULL COMMENT '项目ID' AFTER `store_id`;
	END IF;
END;

CALL system_config_saas_appid();


DROP PROCEDURE IF EXISTS system_config_show;
CREATE PROCEDURE system_config_show()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config' AND column_name = 'show')
	THEN
		ALTER TABLE `yc_system_config` ADD COLUMN `show` enum('10','20') NULL COMMENT '是否显示：10否，20是' AFTER `saas_appid`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config' AND column_name = 'show')
	THEN
		ALTER TABLE `yc_system_config` MODIFY COLUMN `show` enum('10','20') NULL COMMENT '是否显示：10否，20是' AFTER `saas_appid`;
	END IF;
END;

CALL system_config_show();