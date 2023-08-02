DROP PROCEDURE IF EXISTS store_app_url;

CREATE PROCEDURE store_app_url()
BEGIN
	IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'yc_store_app' AND column_name = 'url')
	THEN
		ALTER TABLE `yc_store_app` ADD COLUMN `url` int(10) NULL COMMENT '项目网址，不带结尾斜杠' AFTER `name` ;
	END IF;
END;

CALL store_app_url() ;