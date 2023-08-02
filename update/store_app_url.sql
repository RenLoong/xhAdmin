DROP PROCEDURE IF EXISTS store_app_url;

DELIMITER //

CREATE PROCEDURE store_app_url()
BEGIN
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store_app' AND column_name = 'url') THEN
        ALTER TABLE `yc_store_app` ADD COLUMN `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '项目网址，不带结尾斜杠' AFTER `name`;
    END IF;
END //

DELIMITER ;

CALL store_app_url();