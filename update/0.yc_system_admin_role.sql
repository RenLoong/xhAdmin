DROP PROCEDURE IF EXISTS system_admin_role_is_system;
CREATE PROCEDURE system_admin_role_is_system()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_admin_role' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_admin_role` ADD COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否，20是' AFTER `rule`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_admin_role' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_admin_role` MODIFY COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否，20是' AFTER `rule`;
	END IF;
END;

CALL system_admin_role_is_system();