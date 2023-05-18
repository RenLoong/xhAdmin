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

 Date: 18/05/2023 18:06:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_store_platform_config
-- ----------------------------
DROP TABLE IF EXISTS `php_store_platform_config`;
CREATE TABLE `php_store_platform_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `store_id` int(11) NULL DEFAULT NULL,
  `platform_id` int(11) NULL DEFAULT NULL,
  `config_field` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `config_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-平台配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of php_store_platform_config
-- ----------------------------
INSERT INTO `php_store_platform_config` VALUES (1, '2023-05-05 12:43:27', '2023-05-05 12:59:04', 2, 1, 'web_name', '测试');
INSERT INTO `php_store_platform_config` VALUES (2, '2023-05-05 12:43:27', '2023-05-05 12:59:15', 2, 1, 'domain', 'dsdasa');
INSERT INTO `php_store_platform_config` VALUES (3, '2023-05-05 12:43:27', '2023-05-05 12:59:15', 2, 1, 'logo', 'upload/user/e696862766470b7442d179168f9d732d.jpeg');
INSERT INTO `php_store_platform_config` VALUES (4, '2023-05-05 12:43:27', '2023-05-05 12:43:27', 2, 1, 'description', '');
INSERT INTO `php_store_platform_config` VALUES (5, '2023-05-05 16:02:36', '2023-05-05 16:02:36', 4, 2, 'web_name', 'dsadasdsa');
INSERT INTO `php_store_platform_config` VALUES (6, '2023-05-05 16:02:36', '2023-05-05 16:02:36', 4, 2, 'domain', 'dsadsa');
INSERT INTO `php_store_platform_config` VALUES (7, '2023-05-05 16:02:36', '2023-05-05 16:02:36', 4, 2, 'logo', 'upload/user/e696862766470b7442d179168f9d732d.jpeg');
INSERT INTO `php_store_platform_config` VALUES (8, '2023-05-05 16:02:36', '2023-05-05 16:02:36', 4, 2, 'description', '');
INSERT INTO `php_store_platform_config` VALUES (9, '2023-05-05 16:05:05', '2023-05-05 16:05:05', 4, 3, 'web_name', 'dasdsadasdddffffadas');
INSERT INTO `php_store_platform_config` VALUES (10, '2023-05-05 16:05:05', '2023-05-05 16:05:05', 4, 3, 'domain', 'dasdsadasdddffffadas');
INSERT INTO `php_store_platform_config` VALUES (11, '2023-05-05 16:05:05', '2023-05-08 13:51:41', 4, 3, 'logo', 'upload/user/e696862766470b7442d179168f9d732d.jpeg');
INSERT INTO `php_store_platform_config` VALUES (12, '2023-05-05 16:05:05', '2023-05-05 16:05:05', 4, 3, 'description', '');
INSERT INTO `php_store_platform_config` VALUES (13, '2023-05-08 13:51:41', '2023-05-08 13:51:41', 4, 3, 'appid', '');
INSERT INTO `php_store_platform_config` VALUES (14, '2023-05-08 13:51:41', '2023-05-08 13:51:41', 4, 3, 'mch_id', '');
INSERT INTO `php_store_platform_config` VALUES (15, '2023-05-08 13:51:41', '2023-05-08 13:51:41', 4, 3, 'mch_key', '');
INSERT INTO `php_store_platform_config` VALUES (16, '2023-05-11 16:31:59', '2023-05-11 16:31:59', 4, 4, 'web_name', 'dsagfasdas');
INSERT INTO `php_store_platform_config` VALUES (17, '2023-05-11 16:31:59', '2023-05-11 16:31:59', 4, 4, 'domain', 'dsadddfs');
INSERT INTO `php_store_platform_config` VALUES (18, '2023-05-11 16:31:59', '2023-05-11 16:31:59', 4, 4, 'logo', 'upload/user/e696862766470b7442d179168f9d732d.jpeg');
INSERT INTO `php_store_platform_config` VALUES (19, '2023-05-11 16:31:59', '2023-05-11 16:31:59', 4, 4, 'description', '');
INSERT INTO `php_store_platform_config` VALUES (20, '2023-05-11 16:31:59', '2023-05-11 16:31:59', 4, 4, 'wechat_api_url', '/store/Wechat?store_id=4');
INSERT INTO `php_store_platform_config` VALUES (21, '2023-05-11 16:31:59', '2023-05-11 16:31:59', 4, 4, 'wechat_token', 'b9186923f91b5bf69a7cac3d2d6100ee');
INSERT INTO `php_store_platform_config` VALUES (22, '2023-05-11 16:31:59', '2023-05-11 16:31:59', 4, 4, 'wechat_encoding_aes_key', 'fda5f29f9dfd188a67bcb982e4131e4d');
INSERT INTO `php_store_platform_config` VALUES (23, '2023-05-11 18:44:41', '2023-05-11 18:44:41', 4, 1, 'web_name', 'dsadsaddd');
INSERT INTO `php_store_platform_config` VALUES (24, '2023-05-11 18:44:41', '2023-05-11 18:44:41', 4, 1, 'domain', 'dsadas');
INSERT INTO `php_store_platform_config` VALUES (25, '2023-05-11 18:44:41', '2023-05-11 18:44:41', 4, 1, 'logo', 'upload/user/e696862766470b7442d179168f9d732d.jpeg');
INSERT INTO `php_store_platform_config` VALUES (26, '2023-05-11 18:44:41', '2023-05-11 18:44:41', 4, 1, 'description', '');
INSERT INTO `php_store_platform_config` VALUES (27, '2023-05-11 18:44:41', '2023-05-11 18:44:41', 4, 1, 'wechat_api_url', '/store/Wechat?store_id=4');
INSERT INTO `php_store_platform_config` VALUES (28, '2023-05-11 18:44:41', '2023-05-11 18:44:41', 4, 1, 'wechat_token', 'a302067aea48e1c2688f3868813232e1');
INSERT INTO `php_store_platform_config` VALUES (29, '2023-05-11 18:44:41', '2023-05-11 18:44:41', 4, 1, 'wechat_encoding_aes_key', '8ff3990d3188a428e9b8bf96b8421d86');
INSERT INTO `php_store_platform_config` VALUES (30, '2023-05-18 16:23:41', '2023-05-18 16:23:41', 4, 2, 'web_name', '测试');
INSERT INTO `php_store_platform_config` VALUES (31, '2023-05-18 16:23:41', '2023-05-18 16:23:41', 4, 2, 'domain', 'http://cloud8.hangpu.net');
INSERT INTO `php_store_platform_config` VALUES (32, '2023-05-18 16:23:41', '2023-05-18 16:23:41', 4, 2, 'logo', 'upload/user/e696862766470b7442d179168f9d732d.jpeg');
INSERT INTO `php_store_platform_config` VALUES (33, '2023-05-18 16:23:41', '2023-05-18 16:23:41', 4, 2, 'description', '');

SET FOREIGN_KEY_CHECKS = 1;
