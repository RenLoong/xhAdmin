DROP PROCEDURE IF EXISTS system_admin_role_is_system;
CREATE PROCEDURE system_admin_role_is_system()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_admin_role' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_admin_role` ADD COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否 20是' AFTER `rule`;
		UPDATE `yc_system_admin_role` SET `is_system` = '20' WHERE `is_system` = '1';
		UPDATE `yc_system_admin_role` SET `is_system` = '10' WHERE `is_system` = '';
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_admin_role' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_admin_role` MODIFY COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否 20是' AFTER `rule`;
		UPDATE `yc_system_admin_role` SET `is_system` = '20' WHERE `is_system` = '1';
		UPDATE `yc_system_admin_role` SET `is_system` = '10' WHERE `is_system` = '';
	END IF;
END;

CALL system_admin_role_is_system();