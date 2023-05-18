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

 Date: 18/05/2023 18:05:42
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_store_app
-- ----------------------------
DROP TABLE IF EXISTS `php_store_app`;
CREATE TABLE `php_store_app`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `store_id` int(11) NULL DEFAULT NULL COMMENT '所属租户',
  `platform_id` int(11) NULL DEFAULT NULL COMMENT '所属平台',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用名称',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用标识',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用LOGO',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '应用状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-平台应用' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of php_store_app
-- ----------------------------
INSERT INTO `php_store_app` VALUES (1, '2023-05-11 19:01:18', '2023-05-12 19:10:10', 4, 1, '测试', 'yc_test', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '0');
INSERT INTO `php_store_app` VALUES (2, '2023-05-12 19:10:22', '2023-05-12 19:10:22', 4, 1, '超级SEO', 'superseo', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '1');

SET FOREIGN_KEY_CHECKS = 1;
