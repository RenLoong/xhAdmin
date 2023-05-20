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

 Date: 20/05/2023 14:03:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_users_integral_bill
-- ----------------------------
DROP TABLE IF EXISTS `php_users_integral_bill`;
CREATE TABLE `php_users_integral_bill`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL COMMENT '账单时间',
  `uid` int(11) NULL DEFAULT NULL COMMENT '所属用户',
  `bill_type` int(3) NULL DEFAULT 0 COMMENT '账单类型：0减少，1增加，2无变动',
  `value` int(10) NULL DEFAULT 0 COMMENT '本次变动',
  `old_value` int(10) NULL DEFAULT 0 COMMENT '变动前',
  `new_value` int(10) NULL DEFAULT 0 COMMENT '变动后',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '变动理由',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户-积分账单' ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
