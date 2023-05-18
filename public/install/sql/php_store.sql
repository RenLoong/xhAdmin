/*
 Navicat Premium Data Transfer

 Source Server         : 腾讯云服务器-KfAdmin-cloud
 Source Server Type    : MySQL
 Source Server Version : 50737 (5.7.37-log)
 Source Host           : 1.116.41.3:3306
 Source Schema         : cloud8_hangpu_ne

 Target Server Type    : MySQL
 Target Server Version : 50737 (5.7.37-log)
 File Encoding         : 65001

 Date: 18/05/2023 18:05:33
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_store
-- ----------------------------
DROP TABLE IF EXISTS `php_store`;
CREATE TABLE `php_store`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `grade_id` int(11) NULL DEFAULT NULL COMMENT '商户等级',
  `expire_time` datetime NULL DEFAULT NULL COMMENT '过期时间',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户名称',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录密码',
  `contact` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系人姓名',
  `mobile` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系电话',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '使用状态：0冻结，1正常',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户图标',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '平台备注',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '登录时间',
  `plugins_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '已授权应用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商户-商户列表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_store
-- ----------------------------
INSERT INTO `php_store` VALUES (2, '2023-05-01 05:57:55', '2023-05-10 18:25:04', 3, '2023-05-08 00:00:00', '测试租户', 'cyu100235', '$2y$10$TBLdQajdHp2pyAVhz3LV/OlKe1OY43nF4a6lcCqmQnN3vSrv.Nywu', 'sasa', '123', '1', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '', '117.188.18.174', '2023-05-04 11:39:49', '[\"yc_test\"]');
INSERT INTO `php_store` VALUES (3, '2023-05-03 16:16:01', '2023-05-10 18:24:58', 1, '2023-05-03 00:00:00', '开通一个租户', 'cyu1002355', '$2y$10$dI9u/vkvqjlbQNAPkIHwO.M2CZg4i1XtYfTXnwFL14m.Eoukj4/4e', '123456', '123456', '1', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '', '117.188.18.174', '2023-05-03 17:47:01', '[\"yc_test\"]');
INSERT INTO `php_store` VALUES (4, '2023-05-03 16:16:38', '2023-05-12 20:38:52', 2, '2023-05-03 00:00:00', '贵州猿创科技有限公司', 'cyu100235555', '$2y$10$GbuVFmedDDyH5jbVVQhNeum9p44VOMm0uQIoD5XgjOgYUTSDOdXfy', '1111', '111', '1', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '', '117.188.18.133', '2023-05-06 11:54:50', '[\"superseo\"]');

SET FOREIGN_KEY_CHECKS = 1;
