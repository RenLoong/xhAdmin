ALTER TABLE `php_plugin_users` COLLATE = utf8mb4_general_ci;
ALTER TABLE `php_plugin_users` 
MODIFY COLUMN `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录账号' AFTER `saas_appid`,
MODIFY COLUMN `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录密码' AFTER `username`,
MODIFY COLUMN `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户昵称' AFTER `password`,
MODIFY COLUMN `headimg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户头像' AFTER `nickname`,
MODIFY COLUMN `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '20' COMMENT '用户状态' AFTER `headimg`,
MODIFY COLUMN `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '最后登录IP' AFTER `last_login_time`;
