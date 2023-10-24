ALTER TABLE `yc_store` 
ADD COLUMN `is_uploadify` enum('10','20') NOT NULL DEFAULT '10' COMMENT '附件库权限：10 无本地权限，20有本地权限' AFTER `other`;

UPDATE `yc_store` SET `is_uploadify` = '20' WHERE `is_uploadify` = '10'