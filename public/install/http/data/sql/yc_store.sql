DROP TABLE IF EXISTS `__PREFIX__store`;

CREATE TABLE `__PREFIX__store`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户名称',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录密码',
  `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '20' COMMENT '使用状态：10冻结，20正常',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户图标',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '平台备注',
  `copyright_service` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '专属客服',
  `copyright_tutorial` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '系统教程',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '登录时间',
  `plugins_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '已授权应用标识',
  `wechat` int NOT NULL DEFAULT 0 COMMENT '公众号数量',
  `mini_wechat` int NOT NULL DEFAULT 0 COMMENT '小程序数量',
  `douyin` int NOT NULL DEFAULT 0 COMMENT '抖音小程序数量',
  `h5` int NOT NULL DEFAULT 0 COMMENT '网页应用数量',
  `app` int NOT NULL DEFAULT 0 COMMENT 'APP应用数量',
  `other` int NOT NULL COMMENT '其他应用',
  `is_uploadify` enum('10','20') NOT NULL DEFAULT '10' COMMENT '附件库权限：10 无本地权限，20有本地权限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '渠道-记录' ROW_FORMAT = DYNAMIC;
