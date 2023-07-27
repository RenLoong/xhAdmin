CREATE PROCEDURE change_store_app_status()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store_app' AND column_name = 'status')
	THEN
		ALTER TABLE `yc_store_app` ADD COLUMN `status` enum('10','20') NULL COMMENT '应用状态' AFTER `logo`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store_app' AND column_name = 'status')
	THEN
		ALTER TABLE `yc_store_app` MODIFY COLUMN `status` enum('10','20') NULL COMMENT '应用状态' AFTER `logo`;
		UPDATE `yc_store_app` SET `status` = '20' WHERE `status` = '10';
		UPDATE `yc_store_app` SET `status` = '10' WHERE `status` = '';
	END IF;
END;

CALL change_store_app_status();

DROP PROCEDURE IF EXISTS change_store_app_status;