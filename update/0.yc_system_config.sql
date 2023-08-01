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
		ALTER TABLE `yc_system_config` ADD COLUMN `show` enum('10','20') NULL DEFAULT '20' COMMENT '是否显示：10否，20是' AFTER `saas_appid`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config' AND column_name = 'show')
	THEN
		ALTER TABLE `yc_system_config` MODIFY COLUMN `show` enum('10','20') NULL DEFAULT '20' COMMENT '是否显示：10否，20是' AFTER `saas_appid`;
		UPDATE `yc_system_config` SET `show` = '20' WHERE `show` = '10';
		UPDATE `yc_system_config` SET `show` = '10' WHERE `show` = '';
	END IF;
END;

CALL system_config_show();


DROP PROCEDURE IF EXISTS system_config_group_name;

CREATE PROCEDURE system_config_group_name()
BEGIN
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_config' AND column_name = 'cid')
	THEN
		ALTER TABLE `yc_system_config` CHANGE COLUMN `cid` `group_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分组标识（外键）' AFTER `show`;
		UPDATE `yc_system_config` SET `group_name` = 'system' WHERE `group_name` = '1';
		UPDATE `yc_system_config` SET `group_name` = 'store_copyright' WHERE `group_name` = '2';
	END IF;
END;

CALL system_config_group_name();