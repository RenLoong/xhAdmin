DROP PROCEDURE IF EXISTS system_upload_cate_is_system;

CREATE PROCEDURE system_upload_cate_is_system()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_upload_cate' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_upload_cate` ADD COLUMN `is_system` enum('10','20') COMMENT '是否系统：10否 20是' AFTER `update_at`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_system_upload_cate' AND column_name = 'is_system')
	THEN
		ALTER TABLE `yc_system_upload_cate` MODIFY COLUMN `is_system` enum('10','20') NULL COMMENT '是否系统：10否 20是' AFTER `update_at`;
	END IF;
END;

CALL system_upload_cate_is_system();