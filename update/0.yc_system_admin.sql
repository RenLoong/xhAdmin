
DROP PROCEDURE IF EXISTS system_admin_status;
CREATE PROCEDURE system_admin_status()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_admin' AND column_name = 'status')
	THEN
		ALTER TABLE `yc_system_admin` ADD COLUMN `status` enum('10','20') NULL COMMENT '用户状态：10禁用，20启用' AFTER `password`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_admin' AND column_name = 'status')
	THEN
		ALTER TABLE `yc_system_admin` MODIFY COLUMN `status` enum('10','20') NULL COMMENT '用户状态：10禁用，20启用' AFTER `password`;
		UPDATE `yc_system_admin` SET `status` = '20' WHERE `status` = '10';
		UPDATE `yc_system_admin` SET `status` = '10' WHERE `status` = '';
	END IF;
END;

CALL system_admin_status();

DROP PROCEDURE IF EXISTS system_admin_is_system;
CREATE PROCEDURE system_admin_is_system()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_admin' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_admin` ADD COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否，20是' AFTER `headimg`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_admin' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_admin` MODIFY COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否，20是' AFTER `headimg`;
	END IF;
END;

CALL system_admin_is_system();