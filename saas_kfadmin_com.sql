/*
 Navicat Premium Data Transfer

 Source Server         : 内网数据库
 Source Server Type    : MySQL
 Source Server Version : 80032
 Source Host           : 127.0.0.1:3306
 Source Schema         : saas_kfadmin_com

 Target Server Type    : MySQL
 Target Server Version : 80032
 File Encoding         : 65001

 Date: 22/07/2023 13:55:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yc_store
-- ----------------------------
DROP TABLE IF EXISTS `yc_store`;
CREATE TABLE `yc_store`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `expire_time` datetime NULL DEFAULT NULL COMMENT '过期时间',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户名称',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录密码',
  `contact` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系人姓名',
  `mobile` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系电话',
  `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '使用状态：10冻结，20正常',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户图标',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '平台备注',
  `copyright_service` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '专属客服',
  `copyright_tutorial` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '系统教程',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '登录时间',
  `plugins_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '已授权应用标识',
  `wechat` int NOT NULL DEFAULT 0 COMMENT '公众号数量',
  `mini_wechat` int NOT NULL DEFAULT 0 COMMENT '小程序数量',
  `douyin` int NOT NULL DEFAULT 0 COMMENT '抖音小程序数量',
  `h5` int NOT NULL DEFAULT 0 COMMENT '网页应用数量',
  `app` int NOT NULL DEFAULT 0 COMMENT 'APP应用数量',
  `other` int NOT NULL COMMENT '其他应用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_store
-- ----------------------------
INSERT INTO `yc_store` VALUES (1, '2023-07-21 11:34:27', '2023-07-21 14:01:59', '2023-07-15 00:00:00', '楚羽幽', '18788654245', '$2y$10$QKtY/zhqlsR9MNQ8n1.2luAqSm4yp.J88c9W8xn/4lPf6OLThtFOa', '楚羽幽', '18788654245', '20', 'upload/system_upload/e696862766470b7442d179168f9d732d.jpeg', '', NULL, NULL, NULL, NULL, NULL, 10, 10, 10, 10, 10, 10);

-- ----------------------------
-- Table structure for yc_store_app
-- ----------------------------
DROP TABLE IF EXISTS `yc_store_app`;
CREATE TABLE `yc_store_app`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `store_id` int NULL DEFAULT NULL COMMENT '所属租户',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用名称',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用标识',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用LOGO',
  `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '应用状态',
  `platform` enum('wechat','mini_wechat','douyin','h5','app','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'other' COMMENT '应用平台类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-应用' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_store_app
-- ----------------------------
INSERT INTO `yc_store_app` VALUES (1, '2023-07-07 11:28:55', '2023-07-07 11:28:55', 0, '开发者测试平台', 'ycSeo1', NULL, '10', 'other');
INSERT INTO `yc_store_app` VALUES (2, '2023-07-07 11:30:53', '2023-07-07 11:30:53', 0, '开发者测试平台', 'ycSeo2', NULL, '10', 'other');
INSERT INTO `yc_store_app` VALUES (3, '2023-07-07 11:32:47', '2023-07-07 11:32:47', 0, '开发者测试平台', 'ycSeo', NULL, '10', 'other');
INSERT INTO `yc_store_app` VALUES (4, '2023-07-07 16:42:55', '2023-07-07 16:42:55', 0, '开发者测试平台', 'ycSeo', NULL, '10', 'other');
INSERT INTO `yc_store_app` VALUES (5, '2023-07-07 16:46:42', '2023-07-07 16:46:42', 0, '开发者测试平台', 'ycSeo', NULL, '10', 'other');
INSERT INTO `yc_store_app` VALUES (6, '2023-07-07 16:46:58', '2023-07-07 16:46:58', 0, '开发者测试平台', 'ycSeo1', NULL, '10', 'other');

-- ----------------------------
-- Table structure for yc_store_menus
-- ----------------------------
DROP TABLE IF EXISTS `yc_store_menus`;
CREATE TABLE `yc_store_menus`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin' COMMENT '模块名称',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求地址：控制器/操作方法',
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '命名空间',
  `pid` int NULL DEFAULT 0 COMMENT '父级菜单地址',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `sort` int NULL DEFAULT 0 COMMENT '菜单排序，值越大越靠后',
  `method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求类型：GET,POST,PUT,DELETE',
  `is_api` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否接口：10否，20是',
  `component` enum('none/index','form/index','table/index','remote/index') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'none/index' COMMENT '组件类型',
  `auth_params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附带参数：remote/index，填写远程组件路径名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `show` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否显示：10隐藏，20显示',
  `is_system` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否系统：10否，20是',
  `is_default` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '默认权限：10否，20是',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `path`(`path`) USING BTREE COMMENT '唯一索引'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-菜单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_store_menus
-- ----------------------------
INSERT INTO `yc_store_menus` VALUES (1, '2022-10-27 17:22:51', '2023-03-07 21:36:28', 'store', '', '\\app\\store\\controller\\', 0, '首页', 0, '[\"GET\"]', '10', 'none/index', '', 'HomeOutlined', '20', '20', '20');
INSERT INTO `yc_store_menus` VALUES (2, '2022-10-27 17:22:51', '2023-05-11 18:59:20', 'store', 'Index/index', '\\app\\store\\controller\\', 1, '控制台', 0, '[\"GET\"]', '10', 'remote/index', '/remote/store', '', '20', '20', '20');
INSERT INTO `yc_store_menus` VALUES (3, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/login', '\\app\\store\\controller\\', 1, '系统登录', 0, '[\"POST\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (4, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/site', '\\app\\store\\controller\\', 1, '获取应用信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (5, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/user', '\\app\\store\\controller\\', 1, '获取管理员信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (6, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/menus', '\\app\\store\\controller\\', 1, '获取菜单信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (7, '2022-11-16 19:33:49', '2023-04-16 17:16:04', 'store', 'Uploadify/tabs', '\\app\\admin\\controller\\', 1, '附件模块', 0, '[\"GET\"]', '10', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (8, '2022-11-16 19:34:37', '2023-05-03 16:12:30', 'store', 'SystemUpload/index', '\\app\\admin\\controller\\', 7, '附件管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '20', '20');
INSERT INTO `yc_store_menus` VALUES (9, '2022-11-16 19:35:31', '2023-05-03 16:12:42', 'store', 'SystemUpload/upload', '\\app\\admin\\controller\\', 8, '上传附件', 0, '[\"GET\",\"POST\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (10, '2022-11-16 19:36:17', '2023-05-03 16:12:57', 'store', 'SystemUpload/del', '\\app\\admin\\controller\\', 8, '删除附件', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (11, '2022-11-16 19:38:19', '2023-05-03 16:13:24', 'store', 'SystemUpload/table', '\\app\\admin\\controller\\', 8, '附件列表', 0, '[\"GET\"]', '10', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (12, '2022-11-16 19:41:08', '2023-04-16 17:16:04', 'store', 'SystemUploadCate/index', '\\app\\admin\\controller\\', 7, '附件分类', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '20', '20');
INSERT INTO `yc_store_menus` VALUES (13, '2022-11-16 19:41:58', '2023-04-23 19:25:14', 'store', 'SystemUploadCate/add', '\\app\\admin\\controller\\', 12, '添加附件分类', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (14, '2022-11-16 19:42:37', '2023-04-21 11:51:40', 'store', 'SystemUploadCate/edit', '\\app\\admin\\controller\\', 12, '修改附件分类', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (15, '2022-11-16 19:43:35', '2023-04-21 11:51:48', 'store', 'SystemUploadCate/del', '\\app\\admin\\controller\\', 12, '删除附件分类', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (16, '2022-11-16 19:45:01', '2023-04-21 11:51:56', 'store', 'SystemUploadCate/table', '\\app\\admin\\controller\\', 12, '附件分类表格', 0, '[\"GET\"]', '10', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (17, '2022-11-16 23:04:14', '2023-04-16 17:16:04', 'store', 'Publics/loginout', '\\app\\admin\\controller\\', 1, '退出登录', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `yc_store_menus` VALUES (18, '2023-05-03 16:57:25', '2023-05-03 17:06:25', 'store', '', '\\app\\store\\controller\\', 0, '平台', 0, '[\"GET\"]', '10', 'none/index', '', 'ShopOutlined', '20', '10', '10');
INSERT INTO `yc_store_menus` VALUES (19, '2023-05-03 17:05:10', '2023-05-03 17:06:56', 'store', '', '\\app\\store\\controller\\', 0, '用户', 0, '[\"GET\"]', '10', 'none/index', '', 'UserOutlined', '20', '10', '10');
INSERT INTO `yc_store_menus` VALUES (20, '2023-05-03 17:08:07', '2023-05-03 19:54:47', 'store', 'PlatformApp/index', '\\app\\store\\controller\\', 21, '应用管理', 0, '[\"GET\"]', '20', 'remote/index', 'remote/app/index', 'SettingTwotone', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (21, '2023-05-03 17:11:21', '2023-05-03 17:11:21', 'store', 'Platform/index', '\\app\\store\\controller\\', 18, '平台管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '10', '10');
INSERT INTO `yc_store_menus` VALUES (22, '2023-05-03 17:11:55', '2023-05-03 17:11:55', 'store', 'Platform/add', '\\app\\store\\controller\\', 21, '开通平台', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (23, '2023-05-03 17:13:06', '2023-05-03 17:13:28', 'store', 'Users/index', '\\app\\store\\controller\\', 19, '用户管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '10', '10');
INSERT INTO `yc_store_menus` VALUES (24, '2023-05-03 17:13:59', '2023-05-03 19:25:03', 'store', 'Platform/config', '\\app\\store\\controller\\', 21, '平台设置', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (25, '2023-05-03 17:38:41', '2023-05-03 17:38:41', 'store', 'Users/add', '\\app\\store\\controller\\', 23, '添加用户', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (26, '2023-05-03 17:39:14', '2023-05-03 17:39:14', 'store', 'Store/edit', '\\app\\store\\controller\\', 23, '修改用户', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (27, '2023-05-03 17:39:56', '2023-05-03 17:39:56', 'store', 'Users/del', '\\app\\store\\controller\\', 23, '删除用户', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (28, '2023-05-03 17:40:24', '2023-05-03 17:40:34', 'store', 'Users/indexGetTable', '\\app\\store\\controller\\', 23, '用户表格', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (29, '2023-05-03 17:42:57', '2023-05-03 17:42:57', 'store', 'UserFinance/index', '\\app\\store\\controller\\', 23, '财务管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (30, '2023-05-03 17:44:02', '2023-05-03 17:44:36', 'store', 'UserFinance/actionFinance', '\\app\\store\\controller\\', 29, '操作财务', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (31, '2023-05-03 19:53:40', '2023-05-03 19:53:40', 'store', '', '', 20, '分配应用', 0, '[\"GET\"]', '10', 'remote/index', 'remote/app/index', '', '10', '10', '10');
INSERT INTO `yc_store_menus` VALUES (32, '2023-06-16 11:44:08', '2023-06-16 11:44:08', 'store', 'Platform/del', '\\app\\store\\controller\\', 21, '删除平台', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '10', '10');

-- ----------------------------
-- Table structure for yc_system_admin
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_admin`;
CREATE TABLE `yc_system_admin`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `role_id` int NULL DEFAULT NULL COMMENT '所属部门',
  `pid` int NULL DEFAULT 0 COMMENT '上级管理员ID',
  `username` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录账户',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '用户状态：10禁用，20启用',
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户昵称',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间，删除则有数据',
  `headimg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户头像',
  `is_system` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否系统：10否，20是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-管理员' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_admin
-- ----------------------------
INSERT INTO `yc_system_admin` VALUES (1, '2023-07-06 11:01:59', '2023-07-21 14:34:12', 1, 0, 'admin', '$2y$10$0HotvAi8dJE.SeN/OH9j4edNC3omuRhqMxvUjlbZ8YJn.ivUyJPDK', '10', '系统管理员', '192.168.63.128', '2023-07-20 11:17:02', NULL, 'upload/system_upload/e696862766470b7442d179168f9d732d.jpeg', '');

-- ----------------------------
-- Table structure for yc_system_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_admin_log`;
CREATE TABLE `yc_system_admin_log`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `admin_id` int NULL DEFAULT NULL COMMENT '管理员',
  `role_id` int NULL DEFAULT NULL COMMENT '管理员角色',
  `action_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '本次操作菜单名称',
  `action_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `city_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '城市名',
  `isp_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '网络运营商',
  `action_type` enum('0','1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '操作类型：0登录，1新增，2修改，3删除',
  `path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作路由',
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作日志JSON格式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-操作日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for yc_system_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_admin_role`;
CREATE TABLE `yc_system_admin_role`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `pid` int NULL DEFAULT 0 COMMENT '上级管理员，0顶级',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '部门名称',
  `rule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '部门权限',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0不是系统，1是系统',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-角色管理' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_admin_role
-- ----------------------------
INSERT INTO `yc_system_admin_role` VALUES (1, '2022-10-28 07:11:51', '2022-11-16 11:30:39', 0, '系统管理员', '[\"Index\\/index\",\"SystemConfigGroup\\/table\",\"Plugin\\/create\"]', '1');
INSERT INTO `yc_system_admin_role` VALUES (3, '2023-07-20 14:26:06', '2023-07-20 14:26:06', 1, 'ddd', '[1,2,3,4,5,6,41,42,43,45,46,50,51,83,84,178]', '0');

-- ----------------------------
-- Table structure for yc_system_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_auth_rule`;
CREATE TABLE `yc_system_auth_rule`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin' COMMENT '模块名称',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求地址：控制器/操作方法',
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '命名空间',
  `pid` int NULL DEFAULT 0 COMMENT '父级菜单地址',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `sort` int NULL DEFAULT 0 COMMENT '菜单排序，值越大越靠后',
  `method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求类型：GET,POST,PUT,DELETE',
  `is_api` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否接口：0否，1是',
  `component` enum('none/index','form/index','table/index','remote/index') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'none/index' COMMENT '组件类型',
  `auth_params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附带参数：remote/index，填写远程组件路径名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `show` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否显示：0隐藏，1显示（仅针对1-2级菜单）',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  `is_default` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '默认权限：0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `path`(`path`) USING BTREE COMMENT '唯一索引'
) ENGINE = InnoDB AUTO_INCREMENT = 190 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-权限规则' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_auth_rule
-- ----------------------------
INSERT INTO `yc_system_auth_rule` VALUES (1, '2022-10-27 17:22:51', '2023-03-07 21:36:28', 'admin', 'SystemIndex/group', '\\app\\admin\\controller\\', 0, '首页', 0, '[\"GET\"]', '10', 'none/index', '', 'HomeOutlined', '20', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (2, '2022-10-27 17:22:51', '2023-05-10 14:47:24', 'admin', 'Index/index', '\\app\\admin\\controller\\', 1, '控制台', 0, '[\"GET\"]', '10', 'remote/index', '/remote/welcome', 'FolderOpenOutlined', '20', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (3, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Publics/login', '\\app\\admin\\controller\\', 1, '系统登录', 0, '[\"POST\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (4, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Publics/site', '\\app\\admin\\controller\\', 1, '获取应用信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (5, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Publics/user', '\\app\\admin\\controller\\', 1, '获取管理员信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (6, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Publics/menus', '\\app\\admin\\controller\\', 1, '获取菜单信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (7, '2022-10-27 17:22:51', '2023-03-07 21:36:32', 'admin', 'SystemSettings/group', '\\app\\admin\\controller\\', 0, '系统', 0, '[\"GET\"]', '10', 'none/index', '', 'HomeOutlined', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (8, '2022-10-27 17:22:51', '2023-05-10 18:24:10', 'admin', 'Webconfig/tabs', '\\app\\admin\\controller\\', 7, '配置项', 0, '[\"GET\"]', '10', 'none/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (9, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Auth/tabs', '\\app\\admin\\controller\\', 7, '权限管理', 0, '[\"GET\"]', '10', 'none/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (10, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfig/form', '\\app\\admin\\controller\\', 8, '保存配置', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (11, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/index', '\\app\\admin\\controller\\', 8, '配置分组', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (12, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemAuthRule/index', '\\app\\admin\\controller\\', 9, '菜单管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (13, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemAdminRole/index', '\\app\\admin\\controller\\', 9, '部门管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (14, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemAdmin/index', '\\app\\admin\\controller\\', 9, '账户管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (17, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Modules/tabs', '\\app\\admin\\controller\\', 7, '功能模块', 0, '[\"GET\"]', '10', 'none/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (20, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Modules/index', '\\app\\admin\\controller\\', 17, '一键功能', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (21, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/add', '\\app\\admin\\controller\\', 11, '添加配置分组', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (22, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/edit', '\\app\\admin\\controller\\', 11, '修改配置分组', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (23, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/del', '\\app\\admin\\controller\\', 11, '删除配置分组', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (24, '2022-10-27 17:22:51', '2023-07-21 12:27:49', 'admin', 'SystemConfig/index', '\\app\\admin\\controller\\', 10, '配置项列表', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (28, '2022-10-27 17:22:51', '2023-04-21 11:53:26', 'admin', 'SystemAuthRule/add', '\\app\\admin\\controller\\', 12, '添加权限菜单', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (29, '2022-10-27 17:22:51', '2023-04-21 11:53:35', 'admin', 'SystemAuthRule/edit', '\\app\\admin\\controller\\', 12, '修改权限菜单', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (30, '2022-10-27 17:22:51', '2023-04-21 11:53:55', 'admin', 'SystemAuthRule/del', '\\app\\admin\\controller\\', 12, '删除权限菜单', 0, '[\"GET\",\"DELETE\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (32, '2022-11-15 02:42:33', '2023-04-21 11:58:32', 'admin', 'SystemAdminRole/add', '\\app\\admin\\controller\\', 13, '添加部门', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (33, '2022-11-15 02:44:31', '2023-04-21 11:59:16', 'admin', 'SystemAdminRole/edit', '\\app\\admin\\controller\\', 13, '修改部门', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (34, '2022-11-15 02:46:04', '2023-04-21 11:59:24', 'admin', 'SystemAdminRole/del', '\\app\\admin\\controller\\', 13, '删除部门', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (35, '2022-11-15 02:48:09', '2023-04-21 11:59:54', 'admin', 'SystemAdmin/add', '\\app\\admin\\controller\\', 14, '添加账户', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (36, '2022-11-15 02:49:00', '2023-04-21 12:00:01', 'admin', 'SystemAdmin/edit', '\\app\\admin\\controller\\', 14, '修改账户', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (37, '2022-11-15 02:49:46', '2023-04-21 12:00:09', 'admin', 'SystemAdmin/del', '\\app\\admin\\controller\\', 14, '删除账户', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (38, '2022-11-15 09:23:53', '2023-04-21 11:59:32', 'admin', 'SystemAdminRole/auth', '\\app\\admin\\controller\\', 13, '设置权限', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (40, '2022-11-16 15:36:42', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/indexGetTable', '\\app\\admin\\controller\\', 11, '配置分组列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (41, '2022-11-16 19:33:49', '2023-04-16 17:16:04', 'admin', 'Uploadify/tabs', '\\app\\admin\\controller\\', 7, '附件模块', 0, '[\"GET\"]', '10', 'none/index', '', '', '20', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (42, '2022-11-16 19:34:37', '2023-04-16 17:16:04', 'admin', 'SystemUpload/index', '\\app\\admin\\controller\\', 41, '附件管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (43, '2022-11-16 19:35:31', '2023-04-21 11:51:04', 'admin', 'SystemUpload/upload', '\\app\\admin\\controller\\', 42, '上传附件', 0, '[\"GET\",\"POST\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (44, '2022-11-16 19:36:17', '2023-04-21 11:51:13', 'admin', 'SystemUpload/del', '\\app\\admin\\controller\\', 42, '删除附件', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (45, '2022-11-16 19:38:19', '2023-04-21 11:51:23', 'admin', 'SystemUpload/table', '\\app\\admin\\controller\\', 42, '附件列表', 0, '[\"GET\"]', '10', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (46, '2022-11-16 19:41:08', '2023-04-16 17:16:04', 'admin', 'SystemUploadCate/index', '\\app\\admin\\controller\\', 41, '附件分类', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (47, '2022-11-16 19:41:58', '2023-04-23 19:25:14', 'admin', 'SystemUploadCate/add', '\\app\\admin\\controller\\', 46, '添加附件分类', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (48, '2022-11-16 19:42:37', '2023-04-21 11:51:40', 'admin', 'SystemUploadCate/edit', '\\app\\admin\\controller\\', 46, '修改附件分类', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (49, '2022-11-16 19:43:35', '2023-04-21 11:51:48', 'admin', 'SystemUploadCate/del', '\\app\\admin\\controller\\', 46, '删除附件分类', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (50, '2022-11-16 19:45:01', '2023-04-21 11:51:56', 'admin', 'SystemUploadCate/table', '\\app\\admin\\controller\\', 46, '附件分类表格', 0, '[\"GET\"]', '10', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (51, '2022-11-16 23:04:14', '2023-04-16 17:16:04', 'admin', 'Publics/loginout', '\\app\\admin\\controller\\', 1, '退出登录', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (64, '2022-11-18 16:47:26', '2023-04-16 17:16:04', 'admin', 'Modules/add', '\\app\\admin\\controller\\', 20, '创建数据表', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (65, '2022-11-18 16:48:09', '2023-04-16 17:16:04', 'admin', 'Modules/edit', '\\app\\admin\\controller\\', 20, '修改数据表', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (66, '2022-11-18 16:48:42', '2023-04-16 17:16:04', 'admin', 'Modules/del', '\\app\\admin\\controller\\', 20, '删除数据表', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (67, '2022-11-19 04:13:32', '2023-04-16 17:16:04', 'admin', 'Fields/index', '\\app\\admin\\controller\\', 20, '字段管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (68, '2022-11-19 04:14:14', '2023-04-16 17:16:04', 'admin', 'Fields/add', '\\app\\admin\\controller\\', 67, '添加字段', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (69, '2022-11-19 04:14:52', '2023-04-16 17:16:04', 'admin', 'Fields/edit', '\\app\\admin\\controller\\', 67, '修改字段', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (70, '2022-11-19 04:15:52', '2023-04-16 17:16:04', 'admin', 'Fields/del', '\\app\\admin\\controller\\', 67, '删除字段', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (71, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/add', '\\app\\admin\\controller\\', 24, '添加配置项', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (72, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/edit', '\\app\\admin\\controller\\', 24, '修改配置项', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (73, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/del', '\\app\\admin\\controller\\', 24, '删除配置项', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (76, '2023-03-10 19:37:45', '2023-04-16 17:16:04', 'admin', 'SystemAuthRule/indexGetTable', '\\app\\admin\\controller\\', 12, '菜单列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (77, '2023-03-11 16:46:33', '2023-04-21 11:59:40', 'admin', 'SystemAdminRole/indexGetTable', '\\app\\admin\\controller\\', 13, '部门列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (78, '2023-03-11 16:52:34', '2023-04-21 12:00:25', 'admin', 'SystemAdmin/indexGetTable', '\\app\\admin\\controller\\', 14, '部门列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (79, '2023-03-12 13:56:06', '2023-04-21 11:52:13', 'admin', 'SystemConfig/indexGetTable', '\\app\\admin\\controller\\', 24, '配置项表格', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (81, '2023-03-12 17:23:11', '2023-04-16 17:16:04', 'admin', 'Modules/indexGetTable', '\\app\\admin\\controller\\', 20, '数据表列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (82, '2023-03-12 17:24:36', '2023-04-16 17:16:04', 'admin', 'Fields/indexGetTable', '\\app\\admin\\controller\\', 67, '字段列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '0');
INSERT INTO `yc_system_auth_rule` VALUES (83, '2023-03-12 20:02:14', '2023-04-16 17:16:04', 'admin', 'Index/clear', '\\app\\admin\\controller\\', 1, '清除缓存', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (84, '2023-03-12 20:11:14', '2023-04-16 17:16:04', 'admin', 'Index/lock', '\\app\\admin\\controller\\', 1, '解除锁定', 0, '[\"POST\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (137, '2023-04-30 18:48:45', '2023-04-30 19:06:45', 'admin', 'pluginGroup/group', '\\app\\admin\\controller\\', 0, '应用', 0, '[\"GET\"]', '10', 'none/index', '', 'AppstoreOutlined', '20', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (138, '2023-04-30 18:54:39', '2023-04-30 18:54:39', 'admin', 'pluginGroup/tabs', '\\app\\admin\\controller\\', 137, '应用插件', 0, '[\"GET\"]', '10', 'none/index', '', '', '20', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (139, '2023-04-30 19:05:31', '2023-04-30 19:05:31', 'admin', 'Plugin/index', '\\app\\admin\\controller\\', 138, '应用中心', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (140, '2023-04-30 19:23:50', '2023-04-30 19:33:18', 'admin', 'Plugin/buy', '\\app\\admin\\controller\\', 139, '购买应用', 0, '[\"GET\",\"POST\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (141, '2023-04-30 19:24:28', '2023-04-30 19:33:06', 'admin', 'Plugin/install', '\\app\\admin\\controller\\', 139, '安装应用', 0, '[\"GET\",\"POST\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (142, '2023-04-30 19:32:16', '2023-04-30 19:32:56', 'admin', 'Plugin/update', '\\app\\admin\\controller\\', 139, '更新应用', 0, '[\"GET\",\"PUT\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (143, '2023-04-30 19:32:44', '2023-04-30 19:32:44', 'admin', 'Plugin/uninstall', '\\app\\admin\\controller\\', 139, '卸载应用', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (144, '2023-04-30 19:34:48', '2023-04-30 19:52:20', 'admin', 'storeGroup/group', '\\app\\admin\\controller\\', 0, '代理', 0, '[\"GET\"]', '10', 'none/index', '', 'InboxOutlined', '20', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (145, '2023-04-30 21:11:43', '2023-04-30 21:13:12', 'admin', 'Plugin/indexGetTable', '\\app\\admin\\controller\\', 139, '表格列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (146, '2023-04-30 21:52:41', '2023-04-30 21:52:41', 'admin', 'storeGroup/tabs', '\\app\\admin\\controller\\', 144, '代理模块', 0, '[\"GET\"]', '10', 'none/index', '', '', '20', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (147, '2023-04-30 21:53:43', '2023-04-30 21:53:43', 'admin', 'Store/index', '\\app\\admin\\controller\\', 146, '代理管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (148, '2023-04-30 21:54:09', '2023-04-30 22:18:26', 'admin', 'Store/add', '\\app\\admin\\controller\\', 147, '开通代理', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (149, '2023-04-30 21:54:40', '2023-04-30 21:55:55', 'admin', 'Store/edit', '\\app\\admin\\controller\\', 147, '修改代理', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (150, '2023-04-30 21:55:22', '2023-04-30 21:55:22', 'admin', 'Store/del', '\\app\\admin\\controller\\', 147, '删除代理', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (151, '2023-04-30 21:57:39', '2023-04-30 21:57:39', 'admin', 'Store/indexGetTable', '\\app\\admin\\controller\\', 147, '代理表格', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (157, '2023-05-01 02:23:35', '2023-05-01 02:23:35', 'admin', 'StorePlatform/index', '\\app\\admin\\controller\\', 146, '平台管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (158, '2023-05-01 02:39:51', '2023-05-01 02:39:51', 'admin', 'StorePlatform/add', '\\app\\admin\\controller\\', 157, '创建平台', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (159, '2023-05-01 02:56:21', '2023-05-01 02:56:21', 'admin', 'StorePlatform/edit', '\\app\\admin\\controller\\', 157, '修改平台', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (160, '2023-05-01 03:03:01', '2023-05-01 03:03:01', 'admin', 'StorePlatform/del', '\\app\\admin\\controller\\', 157, '删除平台', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (161, '2023-05-01 03:04:09', '2023-05-01 03:04:09', 'admin', 'StorePlatform/indexGetTable', '\\app\\admin\\controller\\', 157, '平台列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (162, '2023-05-03 12:18:46', '2023-05-03 12:18:46', 'admin', 'Store/login', '\\app\\admin\\controller\\', 147, '管理代理表格', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (163, '2023-05-03 14:45:48', '2023-05-03 14:45:48', 'admin', 'StoreMenus/index', '\\app\\admin\\controller\\', 146, '代理菜单', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (164, '2023-05-03 14:46:25', '2023-05-03 14:48:02', 'admin', 'StoreMenus/add', '\\app\\admin\\controller\\', 163, '添加代理菜单', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (165, '2023-05-03 14:46:53', '2023-05-03 16:11:18', 'admin', 'StoreMenus/edit', '\\app\\admin\\controller\\', 163, '修改代理菜单', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (166, '2023-05-03 14:47:20', '2023-05-03 14:47:20', 'admin', 'StoreMenus/del', '\\app\\admin\\controller\\', 163, '删除代理菜单', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (167, '2023-05-03 14:47:48', '2023-05-03 14:47:48', 'admin', 'StoreMenus/indexGetTable', '\\app\\admin\\controller\\', 163, '代理菜单表格', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (174, '2023-05-05 18:19:23', '2023-05-05 18:19:23', 'admin', 'PluginCloud/index', '\\app\\admin\\controller\\', 138, '云服务服务信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (175, '2023-05-06 14:53:49', '2023-05-06 15:02:38', 'admin', 'PluginCloud/login', '\\app\\admin\\controller\\', 174, '云服务登录', 0, '[\"GET\",\"POST\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (176, '2023-05-06 14:54:35', '2023-05-06 14:54:35', 'admin', 'PluginCloud/captcha', '\\app\\admin\\controller\\', 174, '云服务验证码', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (177, '2023-05-06 18:18:39', '2023-05-06 18:18:39', 'admin', 'Plugin/detail', '\\app\\admin\\controller\\', 139, '插件详情', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (178, '2023-05-06 18:20:14', '2023-05-12 16:26:59', 'admin', 'Index/consoleCount', '\\app\\admin\\controller\\', 2, '控制台数据统计', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '1', '1');
INSERT INTO `yc_system_auth_rule` VALUES (179, '2023-05-06 20:21:59', '2023-05-06 20:21:59', 'admin', 'Plugin/getDoc', '\\app\\admin\\controller\\', 139, '获取文档地址', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (180, '2023-05-09 16:14:10', '2023-05-09 16:14:10', 'admin', 'StoreApp/index', '\\app\\admin\\controller\\', 147, '授权应用', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (181, '2023-05-15 13:04:59', '2023-07-20 12:16:08', 'admin', 'Updated/updateCheck', '\\app\\admin\\controller\\', 2, '版本更新', 0, '[\"GET\",\"POST\"]', '20', 'remote/index', 'remote/update/index', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (182, '2023-06-12 16:07:52', '2023-06-12 16:07:52', 'admin', 'SystemUpload/config', '\\app\\admin\\controller\\', 41, '附件库设置', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '20', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (183, '2023-06-17 11:33:56', '2023-06-17 11:33:56', 'admin', 'Store/copyrightSet', '\\app\\admin\\controller\\', 147, '代理版权设置', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (184, '2023-06-17 12:12:00', '2023-06-17 12:12:23', 'admin', 'StorePlatform/restore', '\\app\\admin\\controller\\', 157, '恢复删除平台', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (185, '2023-07-01 14:03:18', '2023-07-01 14:03:18', 'admin', 'Curd/index', '\\app\\admin\\controller\\', 20, 'CURD管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (186, '2023-07-01 14:04:12', '2023-07-01 14:04:12', 'admin', 'Curd/add', '\\app\\admin\\controller\\', 185, '新建CURD', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (187, '2023-07-01 14:04:52', '2023-07-01 14:05:53', 'admin', 'Curd/edit', '\\app\\admin\\controller\\', 185, '修改CURD', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (188, '2023-07-01 14:05:29', '2023-07-03 10:14:49', 'admin', 'Curd/detail', '\\app\\admin\\controller\\', 185, 'CURD详情', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (189, '2023-07-01 14:08:42', '2023-07-01 14:09:16', 'admin', 'Curd/indexGetTable', '\\app\\admin\\controller\\', 185, 'CURD数据列表', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '0', '0');

-- ----------------------------
-- Table structure for yc_system_config
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_config`;
CREATE TABLE `yc_system_config`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `cid` int NULL DEFAULT NULL COMMENT '配置分组（外键）',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题名称',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '字段名称',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '数据值',
  `component` enum('input','select','radio','checkbox','textarea','uploadify') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'input' COMMENT '表单类型',
  `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '选项数据',
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '配置描述',
  `sort` int NULL DEFAULT 0 COMMENT '配置排序',
  `store_id` int NULL DEFAULT NULL COMMENT '租户ID',
  `saas_appid` int NULL DEFAULT NULL COMMENT '应用ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置项' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_config
-- ----------------------------
INSERT INTO `yc_system_config` VALUES (1, '2023-07-06 11:01:59', '2023-07-20 11:32:50', 1, '站点名称', 'web_name', 'KF开发框架测试', 'input', '', '请输入站点名称', 0, NULL, NULL);
INSERT INTO `yc_system_config` VALUES (2, '2023-07-06 11:01:59', '2023-07-06 11:01:59', 1, '站点域名', 'web_url', 'http://saas.nat.renloong.com', 'input', '', '请输入站点域名', 0, NULL, NULL);
INSERT INTO `yc_system_config` VALUES (3, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 1, '后台图标', 'admin_logo', '', 'uploadify', '', '请上传后台图标', 0, NULL, NULL);
INSERT INTO `yc_system_config` VALUES (4, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 2, '租户版权', 'store_copyright_name', '', 'input', '贵州猿创科技有限公司', '展示在租户统计页面的版权名称', 0, NULL, NULL);
INSERT INTO `yc_system_config` VALUES (5, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 2, '系统教程', 'store_copyright_tutorial', '', 'textarea', '使用文档|http://www.kfadmin.net/#/document在线社区|http://www.kfadmin.net/#/document微信群|http://www.kfadmin.net/#/document', '一行一个信息，示例：名称|网址', 0, NULL, NULL);
INSERT INTO `yc_system_config` VALUES (6, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 2, '专属客服', 'store_copyright_service', '18786709420（微信同号）', 'input', '', '客服展示信息', 0, NULL, NULL);

-- ----------------------------
-- Table structure for yc_system_config_group
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_config_group`;
CREATE TABLE `yc_system_config_group`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分组名称',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '分组标识',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `sort` int NULL DEFAULT 0 COMMENT '排序',
  `layout_col` enum('10','20','30') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '表单布局 10单列，20二列，30四列',
  `is_system` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否系统：10否，20是',
  `store_id` int NULL DEFAULT NULL COMMENT '租户ID',
  `saas_appid` int NULL DEFAULT NULL COMMENT '平台ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置分组' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_config_group
-- ----------------------------
INSERT INTO `yc_system_config_group` VALUES (1, '2023-03-12 13:59:18', '2023-04-21 16:54:45', '系统设置', 'system_config', 'AntDesignOutlined', 0, '10', '', NULL, NULL);
INSERT INTO `yc_system_config_group` VALUES (2, '2023-03-12 13:59:18', '2023-04-21 16:54:45', '租户版权', 'store_copyright', 'AntDesignOutlined', 0, '10', '', NULL, NULL);

-- ----------------------------
-- Table structure for yc_system_curd
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_curd`;
CREATE TABLE `yc_system_curd`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '数据表名称',
  `field_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '字段名称',
  `field_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '字段注释',
  `list_title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '列表名称',
  `list_sort` int NULL DEFAULT 0 COMMENT '字段排序',
  `list_type` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '表格数据显示：10不显示，20显示',
  `form_add` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '新增表单显示：10不显示，20显示',
  `form_edit` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '修改表单显示：10不显示，20显示',
  `is_del` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '是否支持删除：10不支持，20支持',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-curd记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_curd
-- ----------------------------

-- ----------------------------
-- Table structure for yc_system_upload
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_upload`;
CREATE TABLE `yc_system_upload`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '上传时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  `store_id` int NULL DEFAULT NULL COMMENT '租户ID',
  `saas_appid` int NULL DEFAULT NULL COMMENT '应用ID',
  `uid` int NULL DEFAULT NULL COMMENT '用户ID',
  `cid` int NULL DEFAULT 0 COMMENT '所属分类',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '附件名称',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件名称',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件地址',
  `format` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件格式',
  `size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件大小',
  `adapter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '选定器：oss阿里云，qiniu七牛云等等',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件管理器' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_upload
-- ----------------------------
INSERT INTO `yc_system_upload` VALUES (1, '2023-07-21 11:31:44', '2023-07-21 11:33:22', NULL, NULL, NULL, NULL, 1, 'e696862766470b7442d179168f9d732d.jpeg', 'e696862766470b7442d179168f9d732d.jpeg', 'upload/system_upload/e696862766470b7442d179168f9d732d.jpeg', 'jpeg', '87779', 'public');

-- ----------------------------
-- Table structure for yc_system_upload_cate
-- ----------------------------
DROP TABLE IF EXISTS `yc_system_upload_cate`;
CREATE TABLE `yc_system_upload_cate`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  `store_id` int NULL DEFAULT NULL COMMENT '租户ID',
  `saas_appid` int NULL DEFAULT NULL COMMENT '应用ID',
  `uid` int NULL DEFAULT NULL COMMENT '用户ID',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  `dir_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类目录',
  `sort` int NULL DEFAULT 0 COMMENT '分类排序',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件分类' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_system_upload_cate
-- ----------------------------
INSERT INTO `yc_system_upload_cate` VALUES (1, '2022-11-16 20:06:19', '2023-03-06 12:00:36', NULL, NULL, NULL, NULL, '系统附件', 'system_upload', 0, '1');

-- ----------------------------
-- Table structure for yc_users
-- ----------------------------
DROP TABLE IF EXISTS `yc_users`;
CREATE TABLE `yc_users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `store_id` int NULL DEFAULT NULL COMMENT '所属租户',
  `saas_appid` int NULL DEFAULT NULL COMMENT '所属应用',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录密码',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户昵称',
  `headimg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户头像',
  `status` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '用户状态',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '最后登录IP',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户-记录' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of yc_users
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
