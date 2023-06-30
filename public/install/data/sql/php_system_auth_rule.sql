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

 Date: 18/05/2023 18:06:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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
  `pid` int(11) NULL DEFAULT 0 COMMENT '父级菜单地址',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `sort` int(11) NULL DEFAULT 0 COMMENT '菜单排序，值越大越靠后',
  `method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求类型：GET,POST,PUT,DELETE',
  `is_api` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否接口：0否，1是',
  `component` enum('','form/index','table/index','remote/index') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '组件类型',
  `auth_params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附带参数：remote/index，填写远程组件路径名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '图标类名',
  `show` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '是否显示：0隐藏，1显示（仅针对1-2级菜单）',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  `is_default` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '默认权限：0否，1是',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `path`(`path`) USING BTREE COMMENT '唯一索引'
) ENGINE = InnoDB AUTO_INCREMENT = 185 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-权限规则' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_auth_rule
-- ----------------------------
INSERT INTO `php_system_auth_rule` VALUES (1, '2022-10-27 17:22:51', '2023-03-07 21:36:28', 'admin', '', '\\app\\admin\\controller\\', 0, '首页', 0, '[\"GET\"]', '0', '', '', 'HomeOutlined', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (2, '2022-10-27 17:22:51', '2023-05-10 14:47:24', 'admin', 'Index/index', '\\app\\admin\\controller\\', 1, '控制台', 0, '[\"GET\"]', '0', 'remote/index', '/remote/welcome', 'FolderOpenOutlined', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (3, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Publics/login', '\\app\\admin\\controller\\', 1, '系统登录', 0, '[\"POST\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (4, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Publics/site', '\\app\\admin\\controller\\', 1, '获取应用信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (5, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Publics/user', '\\app\\admin\\controller\\', 1, '获取管理员信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (6, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Publics/menus', '\\app\\admin\\controller\\', 1, '获取菜单信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (7, '2022-10-27 17:22:51', '2023-03-07 21:36:32', 'admin', '', '\\app\\admin\\controller\\', 0, '系统', 0, '[\"GET\"]', '0', '', '', 'HomeOutlined', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (8, '2022-10-27 17:22:51', '2023-05-10 18:24:10', 'admin', 'Webconfig/tabs', '\\app\\admin\\controller\\', 7, '配置项', 0, '[\"GET\"]', '0', '', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (9, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Auth/tabs', '\\app\\admin\\controller\\', 7, '权限管理', 0, '[\"GET\"]', '0', '', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (10, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfig/form', '\\app\\admin\\controller\\', 8, '保存配置', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (11, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/index', '\\app\\admin\\controller\\', 8, '配置分组', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (12, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemAuthRule/index', '\\app\\admin\\controller\\', 9, '菜单管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (13, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemAdminRole/index', '\\app\\admin\\controller\\', 9, '部门管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (14, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemAdmin/index', '\\app\\admin\\controller\\', 9, '账户管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (17, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Modules/tabs', '\\app\\admin\\controller\\', 0, '功能模块', 7, '[\"GET\"]', '0', '', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (20, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Modules/index', '\\app\\admin\\controller\\', 17, '一键功能', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (21, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/add', '\\app\\admin\\controller\\', 11, '添加配置分组', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (22, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/edit', '\\app\\admin\\controller\\', 11, '修改配置分组', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (23, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/del', '\\app\\admin\\controller\\', 11, '删除配置分组', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (24, '2022-10-27 17:22:51', '2023-04-21 18:07:07', 'admin', 'SystemConfig/index', '\\app\\admin\\controller\\', 10, '配置项列表', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (28, '2022-10-27 17:22:51', '2023-04-21 11:53:26', 'admin', 'SystemAuthRule/add', '\\app\\admin\\controller\\', 12, '添加权限菜单', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (29, '2022-10-27 17:22:51', '2023-04-21 11:53:35', 'admin', 'SystemAuthRule/edit', '\\app\\admin\\controller\\', 12, '修改权限菜单', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (30, '2022-10-27 17:22:51', '2023-04-21 11:53:55', 'admin', 'SystemAuthRule/del', '\\app\\admin\\controller\\', 12, '删除权限菜单', 0, '[\"GET\",\"DELETE\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (32, '2022-11-15 02:42:33', '2023-04-21 11:58:32', 'admin', 'SystemAdminRole/add', '\\app\\admin\\controller\\', 13, '添加部门', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (33, '2022-11-15 02:44:31', '2023-04-21 11:59:16', 'admin', 'SystemAdminRole/edit', '\\app\\admin\\controller\\', 13, '修改部门', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (34, '2022-11-15 02:46:04', '2023-04-21 11:59:24', 'admin', 'SystemAdminRole/del', '\\app\\admin\\controller\\', 13, '删除部门', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (35, '2022-11-15 02:48:09', '2023-04-21 11:59:54', 'admin', 'SystemAdmin/add', '\\app\\admin\\controller\\', 14, '添加账户', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (36, '2022-11-15 02:49:00', '2023-04-21 12:00:01', 'admin', 'SystemAdmin/edit', '\\app\\admin\\controller\\', 14, '修改账户', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (37, '2022-11-15 02:49:46', '2023-04-21 12:00:09', 'admin', 'SystemAdmin/del', '\\app\\admin\\controller\\', 14, '删除账户', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (38, '2022-11-15 09:23:53', '2023-04-21 11:59:32', 'admin', 'SystemAdminRole/auth', '\\app\\admin\\controller\\', 13, '设置权限', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (40, '2022-11-16 15:36:42', '2023-04-16 17:16:04', 'admin', 'SystemConfigGroup/indexGetTable', '\\app\\admin\\controller\\', 11, '配置分组列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (41, '2022-11-16 19:33:49', '2023-04-16 17:16:04', 'admin', 'Uploadify/tabs', '\\app\\admin\\controller\\', 7, '附件模块', 0, '[\"GET\"]', '0', '', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (42, '2022-11-16 19:34:37', '2023-04-16 17:16:04', 'admin', 'SystemUpload/index', '\\app\\admin\\controller\\', 41, '附件管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (43, '2022-11-16 19:35:31', '2023-04-21 11:51:04', 'admin', 'SystemUpload/upload', '\\app\\admin\\controller\\', 42, '上传附件', 0, '[\"GET\",\"POST\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (44, '2022-11-16 19:36:17', '2023-04-21 11:51:13', 'admin', 'SystemUpload/del', '\\app\\admin\\controller\\', 42, '删除附件', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (45, '2022-11-16 19:38:19', '2023-04-21 11:51:23', 'admin', 'SystemUpload/table', '\\app\\admin\\controller\\', 42, '附件列表', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (46, '2022-11-16 19:41:08', '2023-04-16 17:16:04', 'admin', 'SystemUploadCate/index', '\\app\\admin\\controller\\', 41, '附件分类', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (47, '2022-11-16 19:41:58', '2023-04-23 19:25:14', 'admin', 'SystemUploadCate/add', '\\app\\admin\\controller\\', 46, '添加附件分类', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (48, '2022-11-16 19:42:37', '2023-04-21 11:51:40', 'admin', 'SystemUploadCate/edit', '\\app\\admin\\controller\\', 46, '修改附件分类', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (49, '2022-11-16 19:43:35', '2023-04-21 11:51:48', 'admin', 'SystemUploadCate/del', '\\app\\admin\\controller\\', 46, '删除附件分类', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (50, '2022-11-16 19:45:01', '2023-04-21 11:51:56', 'admin', 'SystemUploadCate/table', '\\app\\admin\\controller\\', 46, '附件分类表格', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (51, '2022-11-16 23:04:14', '2023-04-16 17:16:04', 'admin', 'Publics/loginout', '\\app\\admin\\controller\\', 1, '退出登录', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (64, '2022-11-18 16:47:26', '2023-04-16 17:16:04', 'admin', 'Modules/add', '\\app\\admin\\controller\\', 20, '创建数据表', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (65, '2022-11-18 16:48:09', '2023-04-16 17:16:04', 'admin', 'Modules/edit', '\\app\\admin\\controller\\', 20, '修改数据表', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (66, '2022-11-18 16:48:42', '2023-04-16 17:16:04', 'admin', 'Modules/del', '\\app\\admin\\controller\\', 20, '删除数据表', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (67, '2022-11-19 04:13:32', '2023-04-16 17:16:04', 'admin', 'Fields/index', '\\app\\admin\\controller\\', 20, '字段管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (68, '2022-11-19 04:14:14', '2023-04-16 17:16:04', 'admin', 'Fields/add', '\\app\\admin\\controller\\', 67, '添加字段', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (69, '2022-11-19 04:14:52', '2023-04-16 17:16:04', 'admin', 'Fields/edit', '\\app\\admin\\controller\\', 67, '修改字段', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (70, '2022-11-19 04:15:52', '2023-04-16 17:16:04', 'admin', 'Fields/del', '\\app\\admin\\controller\\', 67, '删除字段', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (71, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/add', '\\app\\admin\\controller\\', 24, '添加配置项', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (72, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/edit', '\\app\\admin\\controller\\', 24, '修改配置项', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (73, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/del', '\\app\\admin\\controller\\', 24, '删除配置项', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (76, '2023-03-10 19:37:45', '2023-04-16 17:16:04', 'admin', 'SystemAuthRule/indexGetTable', '\\app\\admin\\controller\\', 12, '菜单列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (77, '2023-03-11 16:46:33', '2023-04-21 11:59:40', 'admin', 'SystemAdminRole/indexGetTable', '\\app\\admin\\controller\\', 13, '部门列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (78, '2023-03-11 16:52:34', '2023-04-21 12:00:25', 'admin', 'SystemAdmin/indexGetTable', '\\app\\admin\\controller\\', 14, '部门列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (79, '2023-03-12 13:56:06', '2023-04-21 11:52:13', 'admin', 'SystemConfig/indexGetTable', '\\app\\admin\\controller\\', 24, '配置项表格', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (81, '2023-03-12 17:23:11', '2023-04-16 17:16:04', 'admin', 'Modules/indexGetTable', '\\app\\admin\\controller\\', 20, '数据表列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (82, '2023-03-12 17:24:36', '2023-04-16 17:16:04', 'admin', 'Fields/indexGetTable', '\\app\\admin\\controller\\', 67, '字段列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (83, '2023-03-12 20:02:14', '2023-04-16 17:16:04', 'admin', 'Index/clear', '\\app\\admin\\controller\\', 1, '清除缓存', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (84, '2023-03-12 20:11:14', '2023-04-16 17:16:04', 'admin', 'Index/lock', '\\app\\admin\\controller\\', 1, '解除锁定', 0, '[\"POST\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (137, '2023-04-30 18:48:45', '2023-04-30 19:06:45', 'admin', '', '', 0, '应用', 0, '[\"GET\"]', '0', '', '', 'AppstoreOutlined', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (138, '2023-04-30 18:54:39', '2023-04-30 18:54:39', 'admin', '', '', 137, '应用插件', 0, '[\"GET\"]', '0', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (139, '2023-04-30 19:05:31', '2023-04-30 19:05:31', 'admin', 'Plugin/index', '\\app\\admin\\controller\\', 138, '应用中心', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (140, '2023-04-30 19:23:50', '2023-04-30 19:33:18', 'admin', 'Plugin/buy', '\\app\\admin\\controller\\', 139, '购买应用', 0, '[\"GET\",\"POST\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (141, '2023-04-30 19:24:28', '2023-04-30 19:33:06', 'admin', 'Plugin/install', '\\app\\admin\\controller\\', 139, '安装应用', 0, '[\"GET\",\"POST\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (142, '2023-04-30 19:32:16', '2023-04-30 19:32:56', 'admin', 'Plugin/update', '\\app\\admin\\controller\\', 139, '更新应用', 0, '[\"GET\",\"PUT\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (143, '2023-04-30 19:32:44', '2023-04-30 19:32:44', 'admin', 'Plugin/uninstall', '\\app\\admin\\controller\\', 139, '卸载应用', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (144, '2023-04-30 19:34:48', '2023-04-30 19:52:20', 'admin', '', '\\app\\admin\\controller\\', 0, '租户', 0, '[\"GET\"]', '0', '', '', 'InboxOutlined', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (145, '2023-04-30 21:11:43', '2023-04-30 21:13:12', 'admin', 'Plugin/indexGetTable', '\\app\\admin\\controller\\', 139, '表格列表', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (146, '2023-04-30 21:52:41', '2023-04-30 21:52:41', 'admin', '', '\\app\\admin\\controller\\', 144, '租户模块', 0, '[\"GET\"]', '0', '', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (147, '2023-04-30 21:53:43', '2023-04-30 21:53:43', 'admin', 'Store/index', '\\app\\admin\\controller\\', 146, '租户管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (148, '2023-04-30 21:54:09', '2023-04-30 22:18:26', 'admin', 'Store/add', '\\app\\admin\\controller\\', 147, '开通租户', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (149, '2023-04-30 21:54:40', '2023-04-30 21:55:55', 'admin', 'Store/edit', '\\app\\admin\\controller\\', 147, '修改租户', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (150, '2023-04-30 21:55:22', '2023-04-30 21:55:22', 'admin', 'Store/del', '\\app\\admin\\controller\\', 147, '删除租户', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (151, '2023-04-30 21:57:39', '2023-04-30 21:57:39', 'admin', 'Store/indexGetTable', '\\app\\admin\\controller\\', 147, '租户表格', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (157, '2023-05-01 02:23:35', '2023-05-01 02:23:35', 'admin', 'StorePlatform/index', '\\app\\admin\\controller\\', 146, '平台管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (158, '2023-05-01 02:39:51', '2023-05-01 02:39:51', 'admin', 'StorePlatform/add', '\\app\\admin\\controller\\', 157, '创建平台', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (159, '2023-05-01 02:56:21', '2023-05-01 02:56:21', 'admin', 'StorePlatform/edit', '\\app\\admin\\controller\\', 157, '修改平台', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (160, '2023-05-01 03:03:01', '2023-05-01 03:03:01', 'admin', 'StorePlatform/del', '\\app\\admin\\controller\\', 157, '删除平台', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (161, '2023-05-01 03:04:09', '2023-05-01 03:04:09', 'admin', 'StorePlatform/indexGetTable', '\\app\\admin\\controller\\', 157, '平台列表', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (162, '2023-05-03 12:18:46', '2023-05-03 12:18:46', 'admin', 'Store/login', '\\app\\admin\\controller\\', 147, '管理租户', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (163, '2023-05-03 14:45:48', '2023-05-03 14:45:48', 'admin', 'StoreMenus/index', '\\app\\admin\\controller\\', 146, '租户菜单', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (164, '2023-05-03 14:46:25', '2023-05-03 14:48:02', 'admin', 'StoreMenus/add', '\\app\\admin\\controller\\', 163, '添加租户菜单', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (165, '2023-05-03 14:46:53', '2023-05-03 16:11:18', 'admin', 'StoreMenus/edit', '\\app\\admin\\controller\\', 163, '修改租户菜单', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (166, '2023-05-03 14:47:20', '2023-05-03 14:47:20', 'admin', 'StoreMenus/del', '\\app\\admin\\controller\\', 163, '删除租户菜单', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (167, '2023-05-03 14:47:48', '2023-05-03 14:47:48', 'admin', 'StoreMenus/indexGetTable', '\\app\\admin\\controller\\', 163, '租户菜单表格', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (174, '2023-05-05 18:19:23', '2023-05-05 18:19:23', 'admin', 'PluginCloud/index', '\\app\\admin\\controller\\', 138, '云服务服务信息', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (175, '2023-05-06 14:53:49', '2023-05-06 15:02:38', 'admin', 'PluginCloud/login', '\\app\\admin\\controller\\', 174, '云服务登录', 0, '[\"GET\",\"POST\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (176, '2023-05-06 14:54:35', '2023-05-06 14:54:35', 'admin', 'PluginCloud/captcha', '\\app\\admin\\controller\\', 174, '云服务验证码', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (177, '2023-05-06 18:18:39', '2023-05-06 18:18:39', 'admin', 'Plugin/detail', '\\app\\admin\\controller\\', 139, '插件详情', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (178, '2023-05-06 18:20:14', '2023-05-12 16:26:59', 'admin', 'Index/consoleCount', '\\app\\admin\\controller\\', 2, '控制台数据统计', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (179, '2023-05-06 20:21:59', '2023-05-06 20:21:59', 'admin', 'Plugin/getDoc', '\\app\\admin\\controller\\', 139, '获取文档地址', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (180, '2023-05-09 16:14:10', '2023-05-09 16:14:10', 'admin', 'StoreApp/index', '\\app\\admin\\controller\\', 147, '授权应用', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (181, '2023-05-15 13:04:59', '2023-05-15 16:03:01', 'admin', 'Index/updateCheck', '\\app\\admin\\controller\\', 2, '版本更新', 0, '[\"GET\",\"POST\"]', '1', 'remote/index', 'remote/update/index', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (182, '2023-06-12 16:07:52', '2023-06-12 16:07:52', 'admin', 'SystemUpload/config', '\\app\\admin\\controller\\', 41, '附件库设置', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (183, '2023-06-17 11:33:56', '2023-06-17 11:33:56', 'admin', 'Store/copyrightSet', '\\app\\admin\\controller\\', 147, '租户版权设置', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (184, '2023-06-17 12:12:00', '2023-06-17 12:12:23', 'admin', 'StorePlatform/restore', '\\app\\admin\\controller\\', 157, '恢复删除平台', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
SET FOREIGN_KEY_CHECKS = 1;
