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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件管理器' ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
