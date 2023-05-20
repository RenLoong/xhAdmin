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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商户-商户列表' ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
