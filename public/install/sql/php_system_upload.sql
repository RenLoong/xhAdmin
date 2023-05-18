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

 Date: 18/05/2023 18:06:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_system_upload
-- ----------------------------
DROP TABLE IF EXISTS `php_system_upload`;
CREATE TABLE `php_system_upload`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '上传时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `appid` int(11) NULL DEFAULT NULL COMMENT '所属应用',
  `cid` int(11) NULL DEFAULT NULL COMMENT '所属分类',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '附件名称',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件名称',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件地址',
  `format` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件格式',
  `size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件大小',
  `adapter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '选定器：oss阿里云，qiniu七牛云等等',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 132 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件管理器' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_upload
-- ----------------------------
INSERT INTO `php_system_upload` VALUES (106, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (107, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (108, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (109, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (110, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (111, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (112, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (113, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (114, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (115, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (116, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (117, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (118, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (119, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (120, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (121, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (122, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (123, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (124, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (125, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (126, '2023-04-30 03:46:06', '2023-04-30 03:46:06', NULL, 2, '20150220111037_yvBNu.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');
INSERT INTO `php_system_upload` VALUES (129, '2023-05-10 16:42:42', '2023-05-10 16:42:42', NULL, 2, '20210622154903_3c36a (1).jpg', 'a2a1e959f0b1c1780b59fd0dff942092.jpg', 'upload/user/a2a1e959f0b1c1780b59fd0dff942092.jpg', 'jpg', '288653', 'public');
INSERT INTO `php_system_upload` VALUES (130, '2023-05-10 16:42:49', '2023-05-10 16:42:49', NULL, 2, '20210622154903_3c36a.jpeg', 'bd2f76d46755dcb656f144b19b882692.jpeg', 'upload/user/bd2f76d46755dcb656f144b19b882692.jpeg', 'jpeg', '125127', 'public');
INSERT INTO `php_system_upload` VALUES (131, '2023-05-10 16:44:28', '2023-05-10 16:44:28', NULL, 2, 'weixin-logo.jpg', 'cbe66b28e2006f9fc75a0de17ead0a12.jpg', 'upload/user/cbe66b28e2006f9fc75a0de17ead0a12.jpg', 'jpg', '8351', 'public');

SET FOREIGN_KEY_CHECKS = 1;
