ALTER TABLE `php_plugin_articles_cate` COLLATE = utf8mb4_general_ci;
ALTER TABLE `php_plugin_articles_cate` 
MODIFY COLUMN `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类标题' AFTER `saas_appid`,
MODIFY COLUMN `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '状态：10禁用，20启用' AFTER `title`,
MODIFY COLUMN `alias` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL AFTER `sort`;
