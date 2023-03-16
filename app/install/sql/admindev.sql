/*
 Navicat Premium Data Transfer

 Source Server         : 新框架开发
 Source Server Type    : MySQL
 Source Server Version : 50737
 Source Host           : 1.116.41.3:3306
 Source Schema         : admindev

 Target Server Type    : MySQL
 Target Server Version : 50737
 File Encoding         : 65001

 Date: 12/03/2023 20:28:04
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_system_admin
-- ----------------------------
DROP TABLE IF EXISTS `php_system_admin`;
CREATE TABLE `php_system_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `role_id` int(11) NULL DEFAULT NULL COMMENT '所属部门',
  `pid` int(11) NULL DEFAULT 0 COMMENT '上级管理员ID',
  `username` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录账户',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '用户状态：0禁用，1启用',
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户昵称',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间，删除则有数据',
  `headimg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '用户头像',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-管理员' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_admin
-- ----------------------------
INSERT INTO `php_system_admin` VALUES (1, NULL, '2023-03-12 20:24:17', 1, 0, 'admin', '$2y$10$K.0DWIBPyTXnINYX1M9M5O73MpneZTtAWkYGa7OMpU2Gzsgi8rKsq', '1', '楚羽幽', '117.188.18.48', '2023-03-12 20:24:16', NULL, '', '0');
INSERT INTO `php_system_admin` VALUES (16, '2023-03-11 22:50:47', '2023-03-11 22:50:47', 4, 1, 'dsadsadfdddd', '$2y$10$F/3yaqKxNngTS/kql35Pa.5RDoHN2ZvtWpDxVeefYSSNJibVi1hc.', '1', 'dsadas', '', NULL, NULL, 'upload/system/22f607ac001ea12c2a9b4b54222442c2.png', '0');
INSERT INTO `php_system_admin` VALUES (17, '2023-03-11 22:53:09', '2023-03-11 22:53:09', 4, 1, 'dasdasd', '$2y$10$.VPbti1Na4eeej/EPBR..OakgkDY8lsiWaFZLTWVHvj4TyBfSCw0.', '1', 'dsadas', '', NULL, NULL, 'upload/system/1b40974457ad19565aaddc92ba66574e.png', '0');

-- ----------------------------
-- Table structure for php_system_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `php_system_admin_log`;
CREATE TABLE `php_system_admin_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `admin_id` int(11) NULL DEFAULT NULL COMMENT '管理员',
  `role_id` int(11) NULL DEFAULT NULL COMMENT '管理员角色',
  `action_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '本次操作菜单名称',
  `action_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `city_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '城市名',
  `isp_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '网络运营商',
  `action_type` enum('0','1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '操作类型：0登录，1新增，2修改，3删除',
  `path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作路由',
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '操作日志JSON格式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-操作日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_admin_log
-- ----------------------------

-- ----------------------------
-- Table structure for php_system_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `php_system_admin_role`;
CREATE TABLE `php_system_admin_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `pid` int(11) NULL DEFAULT 0 COMMENT '上级管理员，0顶级',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '部门名称',
  `rule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '部门权限',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0不是系统，1是系统',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-角色管理' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_admin_role
-- ----------------------------
INSERT INTO `php_system_admin_role` VALUES (1, '2022-10-28 07:11:51', '2022-11-16 11:30:39', 0, '系统管理员', '[\"Index\\/index\",\"SystemConfigGroup\\/table\",\"Plugin\\/create\"]', '1');
INSERT INTO `php_system_admin_role` VALUES (3, '2023-03-07 14:22:39', '2023-03-10 20:34:06', 1, '客服管理员', '[\"Index\\/tabs\",\"Index\\/index\",\"Publics\\/login\",\"Publics\\/site\",\"Index\\/user\",\"Index\\/menus\",\"Uploadify\\/tabs\",\"SystemUpload\\/index\",\"SystemUpload\\/upload\",\"SystemUpload\\/del\",\"SystemUpload\\/table\",\"SystemUploadCate\\/index\",\"SystemUploadCate\\/add\",\"SystemUploadCate\\/edit\",\"SystemUploadCate\\/del\",\"SystemUploadCate\\/table\",\"Publics\\/loginout\",\"Index\\/index\",\"SystemConfigGroup\\/table\",\"Plugin\\/create\"]', '0');
INSERT INTO `php_system_admin_role` VALUES (4, '2023-03-10 20:33:31', '2023-03-10 20:33:31', 1, '业务部门', '[\"Index\\/tabs\",\"Index\\/index\",\"Publics\\/login\",\"Publics\\/site\",\"Index\\/user\",\"Index\\/menus\",\"Uploadify\\/tabs\",\"SystemUpload\\/index\",\"SystemUpload\\/upload\",\"SystemUpload\\/del\",\"SystemUpload\\/table\",\"SystemUploadCate\\/index\",\"SystemUploadCate\\/add\",\"SystemUploadCate\\/edit\",\"SystemUploadCate\\/del\",\"SystemUploadCate\\/table\",\"Publics\\/loginout\"]', '0');

-- ----------------------------
-- Table structure for php_system_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `php_system_auth_rule`;
CREATE TABLE `php_system_auth_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin' COMMENT '模块名称',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求地址：控制器/操作方法',
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '命名空间',
  `pid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '父级菜单地址',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `sort` int(11) NULL DEFAULT 0 COMMENT '菜单排序，值越大越靠后',
  `method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求类型：GET,POST,PUT,DELETE',
  `is_api` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否接口：0否，1是',
  `component` enum('layouts/index','','form/index','table/index','remote/index') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'layouts/index' COMMENT '组件类型',
  `auth_params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附带参数：remote/index，填写远程组件路径名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `show` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '是否显示：0隐藏，1显示（仅针对1-2级菜单）',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  `is_default` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '默认权限：0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `path`(`path`) USING BTREE COMMENT '唯一索引'
) ENGINE = InnoDB AUTO_INCREMENT = 85 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-权限规则' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_auth_rule
-- ----------------------------
INSERT INTO `php_system_auth_rule` VALUES (1, '2022-10-27 17:22:51', '2023-03-07 21:36:28', 'admin', 'Index/tabs', '\\app\\admin\\controller\\', '', '首页', 0, '[\"GET\"]', '0', 'layouts/index', '', 'fa fa-folder-open', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (2, '2022-10-27 17:22:51', '2023-03-10 20:02:54', 'admin', 'Index/index', '\\app\\admin\\controller\\', 'Index/tabs', '控制台', 0, '[\"GET\"]', '0', 'remote/index', 'remote/welcome', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (3, '2022-10-27 17:22:51', '2023-03-07 21:00:03', 'admin', 'Publics/login', '\\app\\admin\\controller\\', 'Index/tabs', '系统登录', 0, '[\"POST\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (4, '2022-10-27 17:22:51', '2023-03-07 21:00:22', 'admin', 'Publics/site', '\\app\\admin\\controller\\', 'Index/tabs', '获取应用信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (5, '2022-10-27 17:22:51', '2023-03-07 21:00:32', 'admin', 'Publics/user', '\\app\\admin\\controller\\', 'Index/tabs', '获取管理员信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (6, '2022-10-27 17:22:51', '2023-03-07 21:00:39', 'admin', 'Publics/menus', '\\app\\admin\\controller\\', 'Index/tabs', '获取菜单信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (7, '2022-10-27 17:22:51', '2023-03-07 21:36:32', 'admin', 'System/tabs', '\\app\\admin\\controller\\', '', '系统', 0, '[\"GET\"]', '0', 'layouts/index', '', 'fa fa-folder-open', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (8, '2022-10-27 17:22:51', '2023-03-08 05:41:20', 'admin', 'Webconfig/tabs', '\\app\\admin\\controller\\', 'System/tabs', '配置项', 0, '[\"GET\"]', '0', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (9, '2022-10-27 17:22:51', '2023-03-08 05:41:45', 'admin', 'Auth/tabs', '\\app\\admin\\controller\\', 'System/tabs', '权限管理', 0, '[\"GET\"]', '0', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (10, '2022-10-27 17:22:51', '2023-03-11 16:21:44', 'admin', 'SystemConfig/form', '\\app\\admin\\controller\\', 'Webconfig/tabs', '保存配置', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (11, '2022-10-27 17:22:51', '2022-11-16 15:31:41', 'admin', 'SystemConfigGroup/index', '\\app\\admin\\controller\\', 'Webconfig/tabs', '配置分组', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (12, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'admin', 'SystemAuthRule/index', '\\app\\admin\\controller\\', 'Auth/tabs', '菜单管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (13, '2022-10-27 17:22:51', '2022-11-15 14:10:10', 'admin', 'SystemAdminRole/index', '\\app\\admin\\controller\\', 'Auth/tabs', '部门管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (14, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'admin', 'SystemAdmin/index', '\\app\\admin\\controller\\', 'Auth/tabs', '账户管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (15, '2022-10-27 17:22:51', '2023-03-07 21:36:36', 'admin', 'Plugin/tabs', '\\app\\admin\\controller\\', '', '应用', 0, '[\"GET\"]', '0', 'layouts/index', '', 'fa fa-folder-open', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (16, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'admin', 'PluginApp/tabs', '\\app\\admin\\controller\\', 'Plugin/tabs', '应用插件', 0, '[\"GET\"]', '0', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (17, '2022-10-27 17:22:51', '2023-03-07 21:13:27', 'admin', 'Modules/tabs', '\\app\\admin\\controller\\', 'Plugin/tabs', '功能模块', 0, '[\"GET\"]', '0', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (18, '2022-10-27 17:22:51', '2023-03-07 21:11:41', 'admin', 'Plugin/index', '\\app\\admin\\controller\\', 'PluginApp/tabs', '应用中心', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (20, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'admin', 'Modules/index', '\\app\\admin\\controller\\', 'Modules/tabs', '一键功能', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (21, '2022-10-27 17:22:51', '2022-11-16 15:34:52', 'admin', 'SystemConfigGroup/add', '\\app\\admin\\controller\\', 'SystemConfigGroup/index', '添加配置分组', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (22, '2022-10-27 17:22:51', '2022-11-16 15:35:00', 'admin', 'SystemConfigGroup/edit', '\\app\\admin\\controller\\', 'SystemConfigGroup/index', '修改配置分组', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (23, '2022-10-27 17:22:51', '2022-11-16 15:35:07', 'admin', 'SystemConfigGroup/del', '\\app\\admin\\controller\\', 'SystemConfigGroup/index', '删除配置分组', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (24, '2022-10-27 17:22:51', '2022-11-16 15:21:49', 'admin', 'SystemConfig/index', '\\app\\admin\\controller\\', 'SystemConfig/form', '配置项列表', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (28, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'admin', 'SystemAuthRule/add', '\\app\\admin\\controller\\', 'SystemAuthRule/index', '添加权限菜单', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (29, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'admin', 'SystemAuthRule/edit', '\\app\\admin\\controller\\', 'SystemAuthRule/index', '修改权限菜单', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (30, '2022-10-27 17:22:51', '2022-10-27 17:22:51', 'admin', 'SystemAuthRule/del', '\\app\\admin\\controller\\', 'SystemAuthRule/index', '删除权限菜单', 0, '[\"GET\",\"DELETE\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (32, '2022-11-15 02:42:33', '2022-11-15 14:10:00', 'admin', 'SystemAdminRole/add', '\\app\\admin\\controller\\', 'SystemAdminRole/index', '添加部门', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (33, '2022-11-15 02:44:31', '2022-11-15 14:09:56', 'admin', 'SystemAdminRole/edit', '\\app\\admin\\controller\\', 'SystemAdminRole/index', '修改部门', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (34, '2022-11-15 02:46:04', '2022-11-15 14:09:50', 'admin', 'SystemAdminRole/del', '\\app\\admin\\controller\\', 'SystemAdminRole/index', '删除部门', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (35, '2022-11-15 02:48:09', '2022-11-15 02:48:09', 'admin', 'SystemAdmin/add', '\\app\\admin\\controller\\', 'SystemAdmin/index', '添加账户', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (36, '2022-11-15 02:49:00', '2022-11-15 02:49:00', 'admin', 'SystemAdmin/edit', '\\app\\admin\\controller\\', 'SystemAdmin/index', '修改账户', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (37, '2022-11-15 02:49:46', '2022-11-15 02:49:46', 'admin', 'SystemAdmin/del', '\\app\\admin\\controller\\', 'SystemAdmin/index', '删除账户', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (38, '2022-11-15 09:23:53', '2022-11-15 14:09:44', 'admin', 'SystemAdminRole/auth', '\\app\\admin\\controller\\', 'SystemAdminRole/index', '设置权限', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (39, '2022-11-16 15:19:24', '2023-03-10 19:38:02', 'admin', 'Plugin/install', '\\app\\admin\\controller\\', 'Plugin/index', '安装插件', 0, '[\"GET\",\"POST\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (40, '2022-11-16 15:36:42', '2023-03-11 16:02:03', 'admin', 'SystemConfigGroup/indexGetTable', '\\app\\admin\\controller\\', 'SystemConfigGroup/index', '配置分组列表', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (41, '2022-11-16 19:33:49', '2023-03-10 19:35:35', 'admin', 'Uploadify/tabs', '\\app\\admin\\controller\\', 'Index/tabs', '附件模块', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (42, '2022-11-16 19:34:37', '2022-11-16 19:57:12', 'admin', 'SystemUpload/index', '\\app\\admin\\controller\\', 'Uploadify/tabs', '附件管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (43, '2022-11-16 19:35:31', '2022-11-16 19:57:06', 'admin', 'SystemUpload/upload', '\\app\\admin\\controller\\', 'SystemUpload/index', '上传附件', 0, '[\"GET\",\"POST\"]', '1', '', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (44, '2022-11-16 19:36:17', '2022-11-16 19:57:00', 'admin', 'SystemUpload/del', '\\app\\admin\\controller\\', 'SystemUpload/index', '删除附件', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (45, '2022-11-16 19:38:19', '2022-11-16 19:56:54', 'admin', 'SystemUpload/table', '\\app\\admin\\controller\\', 'SystemUpload/index', '附件列表', 0, '[\"GET\"]', '0', '', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (46, '2022-11-16 19:41:08', '2022-11-16 19:55:32', 'admin', 'SystemUploadCate/index', '\\app\\admin\\controller\\', 'Uploadify/tabs', '附件分类', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (47, '2022-11-16 19:41:58', '2022-11-16 19:55:26', 'admin', 'SystemUploadCate/add', '\\app\\admin\\controller\\', 'SystemUploadCate/index', '添加附件分类', 0, '[\"GET\"]', '1', 'form/index', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (48, '2022-11-16 19:42:37', '2022-11-16 19:55:21', 'admin', 'SystemUploadCate/edit', '\\app\\admin\\controller\\', 'SystemUploadCate/index', '修改附件分类', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (49, '2022-11-16 19:43:35', '2022-11-16 19:55:15', 'admin', 'SystemUploadCate/del', '\\app\\admin\\controller\\', 'SystemUploadCate/index', '删除附件分类', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (50, '2022-11-16 19:45:01', '2022-11-16 19:55:10', 'admin', 'SystemUploadCate/table', '\\app\\admin\\controller\\', 'SystemUploadCate/index', '附件分类列表', 0, '[\"GET\"]', '0', '', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (51, '2022-11-16 23:04:14', '2023-03-07 21:00:50', 'admin', 'Publics/loginout', '\\app\\admin\\controller\\', 'Index/tabs', '退出登录', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (64, '2022-11-18 16:47:26', '2022-11-18 16:47:26', 'admin', 'Modules/add', '\\app\\admin\\controller\\', 'Modules/index', '创建数据表', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (65, '2022-11-18 16:48:09', '2022-11-18 16:48:09', 'admin', 'Modules/edit', '\\app\\admin\\controller\\', 'Modules/index', '修改数据表', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (66, '2022-11-18 16:48:42', '2022-11-18 16:48:42', 'admin', 'Modules/del', '\\app\\admin\\controller\\', 'Modules/index', '删除数据表', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (67, '2022-11-19 04:13:32', '2022-11-19 04:15:12', 'admin', 'Fields/index', '\\app\\admin\\controller\\', 'Modules/tabs', '字段管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (68, '2022-11-19 04:14:14', '2022-11-19 04:14:14', 'admin', 'Fields/add', '\\app\\admin\\controller\\', 'Fields/index', '添加字段', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (69, '2022-11-19 04:14:52', '2022-11-19 04:14:52', 'admin', 'Fields/edit', '\\app\\admin\\controller\\', 'Fields/index', '修改字段', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (70, '2022-11-19 04:15:52', '2022-11-19 04:15:52', 'admin', 'Fields/del', '\\app\\admin\\controller\\', 'Fields/index', '删除字段', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (71, '2023-03-06 19:33:21', '2023-03-06 19:33:21', 'admin', 'SystemConfig/add', '\\app\\admin\\controller\\', 'SystemConfig/index', '添加配置项', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (72, '2023-03-06 19:33:21', '2023-03-06 19:33:21', 'admin', 'SystemConfig/edit', '\\app\\admin\\controller\\', 'SystemConfig/index', '修改配置项', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (73, '2023-03-06 19:33:21', '2023-03-06 19:33:21', 'admin', 'SystemConfig/del', '\\app\\admin\\controller\\', 'SystemConfig/index', '删除配置项', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (75, '2023-03-10 19:37:45', '2023-03-10 19:37:45', 'admin', 'plugin/uninstall', '\\app\\admin\\controller\\', 'Plugin/index', '卸载插件', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (76, '2023-03-10 19:37:45', '2023-03-11 16:45:15', 'admin', 'SystemAuthRule/indexGetTable', '\\app\\admin\\controller\\', 'SystemAuthRule/index', '菜单列表', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (77, '2023-03-11 16:46:33', '2023-03-11 16:49:47', 'admin', 'SystemAdminRole/indexGetTable', '\\app\\admin\\controller\\', 'SystemAdminRole/index', '部门列表', 0, '[\"GET\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (78, '2023-03-11 16:52:34', '2023-03-11 16:52:34', 'admin', 'SystemAdmin/indexGetTable', '\\app\\admin\\controller\\', 'SystemAdmin/index', '部门列表', 0, '[\"GET\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (79, '2023-03-12 13:56:06', '2023-03-12 13:56:06', 'admin', 'SystemConfig/indexGetTable', '\\app\\admin\\controller\\', 'SystemConfig/index', '配置项表格列', 0, '[\"GET\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (80, '2023-03-12 17:21:12', '2023-03-12 17:21:54', 'admin', 'Plugin/indexGetTable', '\\app\\admin\\controller\\', 'Plugin/index', '应用列表', 0, '[\"GET\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (81, '2023-03-12 17:23:11', '2023-03-12 17:25:06', 'admin', 'Modules/indexGetTable', '\\app\\admin\\controller\\', 'Modules/index', '数据表列表', 0, '[\"GET\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (82, '2023-03-12 17:24:36', '2023-03-12 17:24:36', 'admin', 'Fields/indexGetTable', '\\app\\admin\\controller\\', 'Fields/index', '字段列表', 0, '[\"GET\"]', '1', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (83, '2023-03-12 20:02:14', '2023-03-12 20:26:32', 'admin', 'Index/clear', '\\app\\admin\\controller\\', 'Index/tabs', '清除缓存', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (84, '2023-03-12 20:11:14', '2023-03-12 20:26:39', 'admin', 'Index/lock', '\\app\\admin\\controller\\', 'Index/tabs', '解除锁定', 0, '[\"POST\"]', '1', '', '', '', '0', '0', '0');

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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置项' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_config
-- ----------------------------
INSERT INTO `php_system_config` VALUES (1, '2023-03-12 14:02:19', '2023-03-12 19:55:03', 1, '站点名称', 'web_name', 'KFAdmin', 'input', '', '请输入站点名称', 0);
INSERT INTO `php_system_config` VALUES (2, '2023-03-12 14:02:44', '2023-03-12 18:57:33', 1, '站点域名', 'web_url', 'http://localhost:5173', 'input', '', '请输入站点域名', 0);
INSERT INTO `php_system_config` VALUES (3, '2023-03-12 14:06:09', '2023-03-12 18:55:58', 1, '后台图标', 'admin_logo', '', 'uploadify', '', '请上传后台图标', 0);

-- ----------------------------
-- Table structure for php_system_config_group
-- ----------------------------
DROP TABLE IF EXISTS `php_system_config_group`;
CREATE TABLE `php_system_config_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分组名称',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '分组标识',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `layout_col` enum('10','20','30') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '10' COMMENT '表单布局 10单列，20二列，30四列',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置分组' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_config_group
-- ----------------------------
INSERT INTO `php_system_config_group` VALUES (1, '2023-03-12 13:59:18', '2023-03-12 14:49:30', '系统设置', 'system', '', 0, '10', '0');

-- ----------------------------
-- Table structure for php_system_plugin
-- ----------------------------
DROP TABLE IF EXISTS `php_system_plugin`;
CREATE TABLE `php_system_plugin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-插件安装' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of php_system_plugin
-- ----------------------------

-- ----------------------------
-- Table structure for php_system_upload
-- ----------------------------
DROP TABLE IF EXISTS `php_system_upload`;
CREATE TABLE `php_system_upload`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '上传时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `cid` int(11) NULL DEFAULT NULL COMMENT '所属分类',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '附件名称',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件名称',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件地址',
  `format` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件格式',
  `size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件大小',
  `adapter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '选定器：oss阿里云，qiniu七牛云等等',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件管理器' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_upload
-- ----------------------------
INSERT INTO `php_system_upload` VALUES (21, '2023-03-09 15:05:29', '2023-03-09 15:05:29', 2, 'login-bg.png', '5ee000910b6790e1e56fda079e9720c2.png', 'upload/system/5ee000910b6790e1e56fda079e9720c2.png', 'png', '11934', 'public');
INSERT INTO `php_system_upload` VALUES (22, '2023-03-09 15:05:29', '2023-03-09 15:05:29', 1, 'login-icon.png', '1b40974457ad19565aaddc92ba66574e.png', 'upload/system/1b40974457ad19565aaddc92ba66574e.png', 'png', '62703', 'public');
INSERT INTO `php_system_upload` VALUES (23, '2023-03-09 15:05:30', '2023-03-09 15:05:30', 1, 'background.png', '22f607ac001ea12c2a9b4b54222442c2.png', 'upload/system/22f607ac001ea12c2a9b4b54222442c2.png', 'png', '206416', 'public');

-- ----------------------------
-- Table structure for php_system_upload_cate
-- ----------------------------
DROP TABLE IF EXISTS `php_system_upload_cate`;
CREATE TABLE `php_system_upload_cate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  `dir_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类目录',
  `sort` int(11) NULL DEFAULT 0 COMMENT '分类排序',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件分类' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_upload_cate
-- ----------------------------
INSERT INTO `php_system_upload_cate` VALUES (1, '2022-11-16 20:06:19', '2023-03-06 12:00:36', '系统附件', 'system', 0, '1');
INSERT INTO `php_system_upload_cate` VALUES (2, '2023-03-06 11:49:34', '2023-03-06 12:07:53', '用户上传', 'user', 0, '0');

SET FOREIGN_KEY_CHECKS = 1;
