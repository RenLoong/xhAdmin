ALTER TABLE `yc_plugin_tags` ADD COLUMN `sort` int(11) NULL DEFAULT 100 COMMENT '排序' AFTER `title`;