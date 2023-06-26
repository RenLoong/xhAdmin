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

 Date: 18/05/2023 18:06:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_system_config
-- ----------------------------
DROP TABLE IF EXISTS `php_system_config`;
CREATE TABLE `php_system_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `cid` int(11) NULL DEFAULT NULL COMMENT '配置分组（外键）',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题名称',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '字段名称',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '数据值',
  `component` enum('input','select','radio','checkbox','textarea','uploadify') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'input' COMMENT '表单类型',
  `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '选项数据',
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '配置描述',
  `sort` int(11) NULL DEFAULT 0 COMMENT '配置排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置项' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_config
-- ----------------------------
INSERT INTO `php_system_config` VALUES (3, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 1, '后台图标', 'admin_logo', '', 'uploadify', '', '请上传后台图标', 0);
INSERT INTO `php_system_config` VALUES (4, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 2, '租户版权', 'store_copyright_name', '', 'input', '贵州猿创科技有限公司', '展示在租户统计页面的版权名称', 0);
INSERT INTO `php_system_config` VALUES (5, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 2, '系统教程', 'store_copyright_tutorial', '', 'textarea', '使用文档|http://www.kfadmin.net/#/document
在线社区|http://www.kfadmin.net/#/document
微信群|http://www.kfadmin.net/#/document', '一行一个信息，示例：名称|网址', 0);
INSERT INTO `php_system_config` VALUES (6, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 2, '专属客服', 'store_copyright_service', '18786709420（微信同号）', 'input', '', '客服展示信息', 0);

SET FOREIGN_KEY_CHECKS = 1;
