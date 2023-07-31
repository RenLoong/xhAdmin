DROP PROCEDURE IF EXISTS change_store_app_platform;

CREATE PROCEDURE change_store_app_platform()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store_app' AND column_name = 'platform')
	THEN
		ALTER TABLE `yc_store_app` ADD COLUMN `platform` enum('wechat','mini_wechat','douyin','h5','app','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'h5' COMMENT '应用平台类型' AFTER `status`;
	END IF;
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store_app' AND column_name = 'platform')
	THEN
		ALTER TABLE `yc_store_app` MODIFY COLUMN `platform` enum('wechat','mini_wechat','douyin','h5','app','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'h5' COMMENT '应用平台类型' AFTER `status`;
	END IF;
END;

CALL change_store_app_platform();