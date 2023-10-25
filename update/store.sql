ALTER TABLE `yc_store` 
ADD COLUMN `is_uploadify` enum('10','20') NULL DEFAULT '10' COMMENT '附件库权限：10 无权限，20有权限' AFTER `other`;