DROP PROCEDURE IF EXISTS change_store_status;

CREATE PROCEDURE change_store_status()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store' AND column_name = 'status')
	THEN
		ALTER TABLE `yc_store` ADD COLUMN `status` enum('10','20') NULL COMMENT '使用状态：10冻结，20正常' AFTER `mobile`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store' AND column_name = 'status')
	THEN
		ALTER TABLE `yc_store` MODIFY COLUMN `status` enum('10','20') NULL COMMENT '使用状态：10冻结，20正常' AFTER `mobile`;
		UPDATE `yc_store` SET `status` = '20' WHERE `status` = '10';
		UPDATE `yc_store` SET `status` = '10' WHERE `status` = '';
	END IF;
END;

CALL change_store_status();