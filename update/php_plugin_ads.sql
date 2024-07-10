ALTER TABLE `php_plugin_ads` COLLATE = utf8mb4_general_ci;
ALTER TABLE `php_plugin_ads` 
MODIFY COLUMN `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '位置标识' AFTER `saas_appid`,
MODIFY COLUMN `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '位置名称' AFTER `name`,
MODIFY COLUMN `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '广告标题' AFTER `category`,
MODIFY COLUMN `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '状态：10禁用，20启用' AFTER `title`,
MODIFY COLUMN `image_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '图片链接' AFTER `status`,
MODIFY COLUMN `link_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '跳转链接' AFTER `image_url`;
