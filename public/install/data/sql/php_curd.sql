/*
 Navicat Premium Data Transfer

 Source Server         : 雷神-笔记本-虚拟机
 Source Server Type    : MySQL
 Source Server Version : 80032
 Source Host           : localhost:3306
 Source Schema         : saas_kfadmin_com

 Target Server Type    : MySQL
 Target Server Version : 80032
 File Encoding         : 65001

 Date: 03/07/2023 18:06:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_curd
-- ----------------------------
DROP TABLE IF EXISTS `php_curd`;
CREATE TABLE `php_curd`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '主键',
  `create_at` datetime DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime DEFAULT NULL COMMENT '更新时间',
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '数据表名称',
  `field_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '字段名称',
  `field_comment` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '字段注释',
  `list_sort` int DEFAULT '0' COMMENT '字段排序',
  `list_type` enum('','text','assets','icons','image','images','input','money','remote','select','switch','tags') COLLATE utf8mb4_general_ci DEFAULT 'text' COMMENT '列表类型',
  `list_extra` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '列表额外参数',
  `form_type` enum('input','select','radio','textarea','icons','uploadify','remote') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'input' COMMENT '表单类型',
  `form_add` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '10' COMMENT '新增表单显示：10不显示，20显示',
  `form_edit` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '10' COMMENT '修改表单显示：10不显示，20显示',
  `form_extra_add` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '增加表单额外参数',
  `form_extra_edit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '修改表单额外参数',
  `is_del` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '10' COMMENT '是否支持删除：10不支持，20支持',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-curd记录' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
