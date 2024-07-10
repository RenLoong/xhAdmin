ALTER TABLE `php_plugin_roles` COLLATE = utf8mb4_general_ci;
ALTER TABLE `php_plugin_roles` 
MODIFY COLUMN `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '部门名称' AFTER `pid`,
MODIFY COLUMN `rule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '部门权限' AFTER `title`,
MODIFY COLUMN `is_system` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否系统：10不是系统，20是系统' AFTER `rule`;
