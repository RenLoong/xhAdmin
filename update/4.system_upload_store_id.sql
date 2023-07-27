CREATE PROCEDURE system_upload_store_id()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_upload' AND column_name = 'store_id')
	THEN
		ALTER TABLE `yc_system_upload` ADD COLUMN `store_id` int(11) NULL COMMENT '租户ID' AFTER `update_at`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_upload' AND column_name = 'store_id')
	THEN
		ALTER TABLE `yc_system_upload` MODIFY COLUMN `store_id` int(11) NULL COMMENT '租户ID' AFTER `update_at`;
	END IF;
END;

CALL system_upload_store_id();

DROP PROCEDURE IF EXISTS system_upload_store_id;