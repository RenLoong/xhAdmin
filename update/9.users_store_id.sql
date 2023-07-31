DROP PROCEDURE IF EXISTS users_store_id;

CREATE PROCEDURE users_store_id()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_users' AND column_name = 'store_id')
	THEN
		ALTER TABLE `yc_users` ADD COLUMN `store_id` int(11) NULL COMMENT '租户ID' AFTER `update_at`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_users' AND column_name = 'store_id')
	THEN
		ALTER TABLE `yc_users` MODIFY COLUMN `store_id` int(11) NULL COMMENT '租户ID' AFTER `update_at`;
	END IF;
END;

CALL users_store_id();
