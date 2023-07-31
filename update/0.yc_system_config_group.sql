DROP PROCEDURE IF EXISTS system_config_group_is_system;
CREATE PROCEDURE system_config_group_is_system()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config_group' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_config_group` ADD COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否，20是' AFTER `layout_col`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config_group' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_config_group` MODIFY COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否，20是' AFTER `layout_col`;
	END IF;
END;

CALL system_config_group_is_system();


DROP PROCEDURE IF EXISTS system_config_group_store_id;
CREATE PROCEDURE system_config_group_store_id()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config_group' AND column_name = 'store_id')
	THEN
		ALTER TABLE `yc_system_config_group` ADD COLUMN `store_id` int(10) NULL COMMENT '租户ID' AFTER `is_system`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config_group' AND column_name = 'store_id')
	THEN
		ALTER TABLE `yc_system_config_group` MODIFY COLUMN `store_id` int(10) NULL COMMENT '租户ID' AFTER `is_system`;
	END IF;
END;

CALL system_config_group_store_id();


DROP PROCEDURE IF EXISTS system_config_group_saas_appid;
CREATE PROCEDURE system_config_group_saas_appid()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config_group' AND column_name = 'saas_appid')
	THEN
		ALTER TABLE `yc_system_config_group` ADD COLUMN `saas_appid` int(10) NULL COMMENT '项目ID' AFTER `store_id`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config_group' AND column_name = 'saas_appid')
	THEN
		ALTER TABLE `yc_system_config_group` MODIFY COLUMN `saas_appid` int(10) NULL COMMENT '项目ID' AFTER `store_id`;
	END IF;
END;

CALL system_config_group_saas_appid();


DROP PROCEDURE IF EXISTS system_config_group_show;
CREATE PROCEDURE system_config_group_show()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config_group' AND column_name = 'show')
	THEN
		ALTER TABLE `yc_system_config_group` ADD COLUMN `show` enum('10','20') NULL COMMENT '是否显示：10否，20是' AFTER `saas_appid`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config_group' AND column_name = 'show')
	THEN
		ALTER TABLE `yc_system_config_group` MODIFY COLUMN `show` enum('10','20') NULL COMMENT '是否显示：10否，20是' AFTER `saas_appid`;
		UPDATE `yc_system_config_group` SET `show` = '20' WHERE `show` = '10';
		UPDATE `yc_system_config_group` SET `show` = '10' WHERE `show` = '';
	END IF;
END;

CALL system_config_group_show();