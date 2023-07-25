DROP TABLE IF EXISTS `yc_store_app`;

CREATE TABLE `yc_store_app`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `store_id` int NULL DEFAULT NULL COMMENT '所属租户',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用名称',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用网址，不带结尾斜杠',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用标识',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用LOGO',
  `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '应用状态',
  `platform` enum('wechat','mini_wechat','douyin','h5','app','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'other' COMMENT '应用平台类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-应用' ROW_FORMAT = DYNAMIC;