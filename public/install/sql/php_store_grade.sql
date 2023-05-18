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

 Date: 18/05/2023 18:05:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_store_grade
-- ----------------------------
DROP TABLE IF EXISTS `php_store_grade`;
CREATE TABLE `php_store_grade`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '等级名称',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '状态：0冻结，1正常',
  `is_default` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '默认：0否，1是',
  `platform_wechat` int(11) NULL DEFAULT 0 COMMENT '微信公众号次数',
  `platform_mini_wechat` int(11) NULL DEFAULT 0 COMMENT '微信小程序次数',
  `platform_app` int(1) NULL DEFAULT 0 COMMENT 'APP次数',
  `platform_h5` int(11) NULL DEFAULT 0 COMMENT '网页应用次数（PC，H5）',
  `platform_douyin` int(11) NULL DEFAULT 0 COMMENT '抖音应用次数',
  `platform_other` int(11) NULL DEFAULT 0 COMMENT '其他平台次数',
  `expire_day` int(11) NULL DEFAULT 0 COMMENT '使用期限（天）0则无限期',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商户-商户等级' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_store_grade
-- ----------------------------
INSERT INTO `php_store_grade` VALUES (1, '2023-05-01 00:16:29', '2023-05-11 18:54:18', '高级', '1', '0', 20, 20, 20, 20, 20, 20, 30, 0);
INSERT INTO `php_store_grade` VALUES (2, '2023-05-01 00:39:32', '2023-05-11 18:54:14', '中级', '1', '0', 15, 15, 15, 15, 15, 15, 15, 0);
INSERT INTO `php_store_grade` VALUES (3, '2023-05-01 05:54:53', '2023-05-11 18:53:51', '基础', '1', '1', 7, 7, 7, 7, 7, 7, 7, 0);

SET FOREIGN_KEY_CHECKS = 1;
