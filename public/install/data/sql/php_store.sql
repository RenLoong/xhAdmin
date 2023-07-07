SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `php_store`;
CREATE TABLE `php_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `expire_time` datetime DEFAULT NULL COMMENT '过期时间',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '商户名称',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '商户账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登录密码',
  `contact` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '联系人姓名',
  `mobile` varchar(30) DEFAULT NULL COMMENT '联系电话',
  `status` enum('0','1') DEFAULT '1' COMMENT '使用状态：0冻结，1正常',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '商户图标',
  `remarks` text COMMENT '平台备注',
  `copyright_service` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '专属客服',
  `copyright_tutorial` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '系统教程',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登录IP',
  `last_login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `plugins_name` text COMMENT '已授权应用标识',
  `wechat` int(11) NOT NULL DEFAULT '0' COMMENT '公众号数量',
  `mini_wechat` int(11) NOT NULL DEFAULT '0' COMMENT '小程序数量',
  `douyin` int(11) NOT NULL DEFAULT '0' COMMENT '抖音小程序数量',
  `h5` int(11) NOT NULL DEFAULT '0' COMMENT '网页应用数量',
  `app` int(11) NOT NULL DEFAULT '0' COMMENT 'APP应用数量',
  `other` int(11) NOT NULL COMMENT '其他应用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT='商户-商户列表' ROW_FORMAT = Dynamic;
SET FOREIGN_KEY_CHECKS = 1;
