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

 Date: 12/05/2023 18:47:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for php_store
-- ----------------------------
DROP TABLE IF EXISTS `php_store`;
CREATE TABLE `php_store`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `grade_id` int(11) NULL DEFAULT NULL COMMENT '商户等级',
  `expire_time` datetime NULL DEFAULT NULL COMMENT '过期时间',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户名称',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录密码',
  `contact` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系人姓名',
  `mobile` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系电话',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '使用状态：0冻结，1正常',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '商户图标',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '平台备注',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '登录时间',
  `plugins_name` varbinary(500) NULL DEFAULT '' COMMENT '已授权租户',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商户-商户列表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_store
-- ----------------------------
INSERT INTO `php_store` VALUES (2, '2023-05-01 05:57:55', '2023-05-10 18:25:04', 3, '2023-05-08 00:00:00', '测试租户', 'cyu100235', '$2y$10$TBLdQajdHp2pyAVhz3LV/OlKe1OY43nF4a6lcCqmQnN3vSrv.Nywu', 'sasa', '123', '1', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '', '117.188.18.174', '2023-05-04 11:39:49', 0x5B2279635F74657374225D);
INSERT INTO `php_store` VALUES (3, '2023-05-03 16:16:01', '2023-05-10 18:24:58', 1, '2023-05-03 00:00:00', '开通一个租户', 'cyu1002355', '$2y$10$dI9u/vkvqjlbQNAPkIHwO.M2CZg4i1XtYfTXnwFL14m.Eoukj4/4e', '123456', '123456', '1', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '', '117.188.18.174', '2023-05-03 17:47:01', 0x5B2279635F74657374225D);
INSERT INTO `php_store` VALUES (4, '2023-05-03 16:16:38', '2023-05-10 13:44:59', 2, '2023-05-03 00:00:00', '贵州猿创科技有限公司', 'cyu100235555', '', '1111', '111', '1', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '', '117.188.18.133', '2023-05-06 11:54:50', 0x5B2279635F74657374225D);

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-平台应用' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of php_store_app
-- ----------------------------
INSERT INTO `php_store_app` VALUES (1, '2023-05-11 19:01:18', '2023-05-12 03:12:54', 4, 1, '测试', 'yc_test', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '1');

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

-- ----------------------------
-- Table structure for php_store_menus
-- ----------------------------
DROP TABLE IF EXISTS `php_store_menus`;
CREATE TABLE `php_store_menus`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-权限菜单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_store_menus
-- ----------------------------
INSERT INTO `php_store_menus` VALUES (1, '2022-10-27 17:22:51', '2023-03-07 21:36:28', 'store', '', '\\app\\store\\controller\\', 0, '首页', 0, '[\"GET\"]', '0', '', '', 'HomeOutlined', '1', '1', '1');
INSERT INTO `php_store_menus` VALUES (2, '2022-10-27 17:22:51', '2023-05-11 18:59:20', 'store', 'Index/index', '\\app\\store\\controller\\', 1, '控制台', 0, '[\"GET\"]', '0', 'remote/index', '/remote/store', '', '1', '1', '1');
INSERT INTO `php_store_menus` VALUES (3, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/login', '\\app\\store\\controller\\', 1, '系统登录', 0, '[\"POST\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (4, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/site', '\\app\\store\\controller\\', 1, '获取应用信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (5, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/user', '\\app\\store\\controller\\', 1, '获取管理员信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (6, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/menus', '\\app\\store\\controller\\', 1, '获取菜单信息', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (7, '2022-11-16 19:33:49', '2023-04-16 17:16:04', 'admin', 'Uploadify/tabs', '\\app\\admin\\controller\\', 1, '附件模块', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (8, '2022-11-16 19:34:37', '2023-05-03 16:12:30', 'admin', 'SystemUpload/index', '\\app\\admin\\controller\\', 7, '附件管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '1');
INSERT INTO `php_store_menus` VALUES (9, '2022-11-16 19:35:31', '2023-05-03 16:12:42', 'admin', 'SystemUpload/upload', '\\app\\admin\\controller\\', 8, '上传附件', 0, '[\"GET\",\"POST\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (10, '2022-11-16 19:36:17', '2023-05-03 16:12:57', 'admin', 'SystemUpload/del', '\\app\\admin\\controller\\', 8, '删除附件', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (11, '2022-11-16 19:38:19', '2023-05-03 16:13:24', 'admin', 'SystemUpload/table', '\\app\\admin\\controller\\', 8, '附件列表', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (12, '2022-11-16 19:41:08', '2023-04-16 17:16:04', 'admin', 'SystemUploadCate/index', '\\app\\admin\\controller\\', 7, '附件分类', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '1');
INSERT INTO `php_store_menus` VALUES (13, '2022-11-16 19:41:58', '2023-04-23 19:25:14', 'admin', 'SystemUploadCate/add', '\\app\\admin\\controller\\', 12, '添加附件分类', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (14, '2022-11-16 19:42:37', '2023-04-21 11:51:40', 'admin', 'SystemUploadCate/edit', '\\app\\admin\\controller\\', 12, '修改附件分类', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (15, '2022-11-16 19:43:35', '2023-04-21 11:51:48', 'admin', 'SystemUploadCate/del', '\\app\\admin\\controller\\', 12, '删除附件分类', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (16, '2022-11-16 19:45:01', '2023-04-21 11:51:56', 'admin', 'SystemUploadCate/table', '\\app\\admin\\controller\\', 12, '附件分类表格', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (17, '2022-11-16 23:04:14', '2023-04-16 17:16:04', 'admin', 'Publics/loginout', '\\app\\admin\\controller\\', 1, '退出登录', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_store_menus` VALUES (18, '2023-05-03 16:57:25', '2023-05-03 17:06:25', 'store', '', '\\app\\store\\controller\\', 0, '平台', 0, '[\"GET\"]', '0', '', '', 'ShopOutlined', '1', '0', '0');
INSERT INTO `php_store_menus` VALUES (19, '2023-05-03 17:05:10', '2023-05-03 17:06:56', 'store', '', '\\app\\store\\controller\\', 0, '用户', 0, '[\"GET\"]', '0', '', '', 'UserOutlined', '1', '0', '0');
INSERT INTO `php_store_menus` VALUES (20, '2023-05-03 17:08:07', '2023-05-03 19:54:47', 'store', 'PlatformApp/index', '\\app\\store\\controller\\', 21, '应用管理', 0, '[\"GET\"]', '1', 'remote/index', 'remote/app/index', 'SettingTwotone', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (21, '2023-05-03 17:11:21', '2023-05-03 17:11:21', 'store', 'Platform/index', '\\app\\store\\controller\\', 18, '平台管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_store_menus` VALUES (22, '2023-05-03 17:11:55', '2023-05-03 17:11:55', 'store', 'Platform/add', '\\app\\store\\controller\\', 21, '开通平台', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (23, '2023-05-03 17:13:06', '2023-05-03 17:13:28', 'store', 'Users/index', '\\app\\store\\controller\\', 19, '用户管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_store_menus` VALUES (24, '2023-05-03 17:13:59', '2023-05-03 19:25:03', 'store', 'Platform/config', '\\app\\store\\controller\\', 21, '平台设置', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (25, '2023-05-03 17:38:41', '2023-05-03 17:38:41', 'store', 'Users/add', '\\app\\store\\controller\\', 23, '添加用户', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (26, '2023-05-03 17:39:14', '2023-05-03 17:39:14', 'store', 'Store/edit', '\\app\\store\\controller\\', 23, '修改用户', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (27, '2023-05-03 17:39:56', '2023-05-03 17:39:56', 'store', 'Users/del', '\\app\\store\\controller\\', 23, '删除用户', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (28, '2023-05-03 17:40:24', '2023-05-03 17:40:34', 'store', 'Users/indexGetTable', '\\app\\store\\controller\\', 23, '用户表格', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (29, '2023-05-03 17:42:57', '2023-05-03 17:42:57', 'store', 'UserFinance/index', '\\app\\store\\controller\\', 23, '财务管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (30, '2023-05-03 17:44:02', '2023-05-03 17:44:36', 'store', 'UserFinance/actionFinance', '\\app\\store\\controller\\', 29, '操作财务', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_store_menus` VALUES (31, '2023-05-03 19:53:40', '2023-05-03 19:53:40', 'store', '', '', 20, '分配应用', 0, '[\"GET\"]', '0', 'remote/index', 'remote/app/index', '', '0', '0', '0');

-- ----------------------------
-- Table structure for php_store_platform
-- ----------------------------
DROP TABLE IF EXISTS `php_store_platform`;
CREATE TABLE `php_store_platform`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `store_id` int(11) NULL DEFAULT NULL COMMENT '所属租户',
  `platform_type` enum('wechat','mini_wechat','douyin','h5','app','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'other' COMMENT '平台类型',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '平台状态',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商户-平台数据' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_store_platform
-- ----------------------------
INSERT INTO `php_store_platform` VALUES (1, '2023-05-11 18:44:41', '2023-05-11 18:44:41', 4, 'wechat', '1', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户-平台配置' ROW_FORMAT = Dynamic;

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
INSERT INTO `php_system_admin` VALUES (1, '2023-04-30 04:09:13', '2023-05-10 15:17:14', 1, 0, 'admin', '$2y$10$K.0DWIBPyTXnINYX1M9M5O73MpneZTtAWkYGa7OMpU2Gzsgi8rKsq', '1', '楚羽幽', '117.188.18.133', '2023-05-10 15:17:13', NULL, 'upload/user/e696862766470b7442d179168f9d732d.jpeg', '0');

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
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-角色管理' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_admin_role
-- ----------------------------
INSERT INTO `php_system_admin_role` VALUES (1, '2022-10-28 07:11:51', '2022-11-16 11:30:39', 0, '系统管理员', '[\"Index\\/index\",\"SystemConfigGroup\\/table\",\"Plugin\\/create\"]', '1');
INSERT INTO `php_system_admin_role` VALUES (5, '2023-04-30 04:15:22', '2023-04-30 04:15:22', 1, '测试', '[1,2,3,4,5,6,41,42,43,44,45,46,47,48,49,50,51,83,84]', '0');

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
) ENGINE = InnoDB AUTO_INCREMENT = 181 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-权限规则' ROW_FORMAT = DYNAMIC;

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
INSERT INTO `php_system_auth_rule` VALUES (17, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'admin', 'Modules/tabs', '\\app\\admin\\controller\\', 15, '功能模块', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '0');
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
INSERT INTO `php_system_auth_rule` VALUES (41, '2022-11-16 19:33:49', '2023-04-16 17:16:04', 'admin', 'Uploadify/tabs', '\\app\\admin\\controller\\', 1, '附件模块', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (42, '2022-11-16 19:34:37', '2023-04-16 17:16:04', 'admin', 'SystemUpload/index', '\\app\\admin\\controller\\', 41, '附件管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (43, '2022-11-16 19:35:31', '2023-04-21 11:51:04', 'admin', 'SystemUpload/upload', '\\app\\admin\\controller\\', 42, '上传附件', 0, '[\"GET\",\"POST\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (44, '2022-11-16 19:36:17', '2023-04-21 11:51:13', 'admin', 'SystemUpload/del', '\\app\\admin\\controller\\', 42, '删除附件', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (45, '2022-11-16 19:38:19', '2023-04-21 11:51:23', 'admin', 'SystemUpload/table', '\\app\\admin\\controller\\', 42, '附件列表', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (46, '2022-11-16 19:41:08', '2023-04-16 17:16:04', 'admin', 'SystemUploadCate/index', '\\app\\admin\\controller\\', 41, '附件分类', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (47, '2022-11-16 19:41:58', '2023-04-23 19:25:14', 'admin', 'SystemUploadCate/add', '\\app\\admin\\controller\\', 46, '添加附件分类', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (48, '2022-11-16 19:42:37', '2023-04-21 11:51:40', 'admin', 'SystemUploadCate/edit', '\\app\\admin\\controller\\', 46, '修改附件分类', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (49, '2022-11-16 19:43:35', '2023-04-21 11:51:48', 'admin', 'SystemUploadCate/del', '\\app\\admin\\controller\\', 46, '删除附件分类', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (50, '2022-11-16 19:45:01', '2023-04-21 11:51:56', 'admin', 'SystemUploadCate/table', '\\app\\admin\\controller\\', 46, '附件分类表格', 0, '[\"GET\"]', '0', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (51, '2022-11-16 23:04:14', '2023-04-16 17:16:04', 'admin', 'Publics/loginout', '\\app\\admin\\controller\\', 1, '退出登录', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '1');
INSERT INTO `php_system_auth_rule` VALUES (64, '2022-11-18 16:47:26', '2023-04-16 17:16:04', 'admin', 'Modules/add', '\\app\\admin\\controller\\', 20, '创建数据表', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (65, '2022-11-18 16:48:09', '2023-04-16 17:16:04', 'admin', 'Modules/edit', '\\app\\admin\\controller\\', 20, '修改数据表', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (66, '2022-11-18 16:48:42', '2023-04-16 17:16:04', 'admin', 'Modules/del', '\\app\\admin\\controller\\', 20, '删除数据表', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (67, '2022-11-19 04:13:32', '2023-04-16 17:16:04', 'admin', 'Fields/index', '\\app\\admin\\controller\\', 17, '字段管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (68, '2022-11-19 04:14:14', '2023-04-16 17:16:04', 'admin', 'Fields/add', '\\app\\admin\\controller\\', 67, '添加字段', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (69, '2022-11-19 04:14:52', '2023-04-16 17:16:04', 'admin', 'Fields/edit', '\\app\\admin\\controller\\', 67, '修改字段', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (70, '2022-11-19 04:15:52', '2023-04-16 17:16:04', 'admin', 'Fields/del', '\\app\\admin\\controller\\', 67, '删除字段', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (71, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/add', '\\app\\admin\\controller\\', 24, '添加配置项', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (72, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/edit', '\\app\\admin\\controller\\', 24, '修改配置项', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (73, '2023-03-06 19:33:21', '2023-04-16 17:16:04', 'admin', 'SystemConfig/del', '\\app\\admin\\controller\\', 24, '删除配置项', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (76, '2023-03-10 19:37:45', '2023-04-16 17:16:04', 'admin', 'SystemAuthRule/indexGetTable', '\\app\\admin\\controller\\', 12, '菜单列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (77, '2023-03-11 16:46:33', '2023-04-21 11:59:40', 'admin', 'SystemAdminRole/indexGetTable', '\\app\\admin\\controller\\', 13, '部门列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (78, '2023-03-11 16:52:34', '2023-04-21 12:00:25', 'admin', 'SystemAdmin/indexGetTable', '\\app\\admin\\controller\\', 14, '部门列表', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (79, '2023-03-12 13:56:06', '2023-04-21 11:52:13', 'admin', 'SystemConfig/indexGetTable', '\\app\\admin\\controller\\', 24, '配置项表格', 0, '[\"GET\"]', '1', '', '', '', '0', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (81, '2023-03-12 17:23:11', '2023-04-16 17:16:04', 'admin', 'Modules/indexGetTable', '\\app\\admin\\controller\\', 20, '数据表列表', 0, '[\"GET\"]', '1', '', '', '', '1', '1', '0');
INSERT INTO `php_system_auth_rule` VALUES (82, '2023-03-12 17:24:36', '2023-04-16 17:16:04', 'admin', 'Fields/indexGetTable', '\\app\\admin\\controller\\', 67, '字段列表', 0, '[\"GET\"]', '1', '', '', '', '1', '1', '0');
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
INSERT INTO `php_system_auth_rule` VALUES (152, '2023-04-30 23:21:17', '2023-04-30 23:21:17', 'admin', 'StoreGrade/index', '\\app\\admin\\controller\\', 146, '租户等级', 0, '[\"GET\"]', '1', 'table/index', '', '', '1', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (153, '2023-04-30 23:40:28', '2023-04-30 23:44:01', 'admin', 'StoreGrade/add', '\\app\\admin\\controller\\', 152, '添加租户等级', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (154, '2023-04-30 23:41:26', '2023-04-30 23:41:55', 'admin', 'StoreGrade/edit', '\\app\\admin\\controller\\', 152, '修改租户等级', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (155, '2023-04-30 23:42:46', '2023-04-30 23:42:46', 'admin', 'StoreGrade/del', '\\app\\admin\\controller\\', 152, '删除租户等级', 0, '[\"GET\",\"DELETE\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (156, '2023-04-30 23:43:39', '2023-04-30 23:43:39', 'admin', 'StoreGrade/indexGetTable', '\\app\\admin\\controller\\', 152, '租户等级表格', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
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
INSERT INTO `php_system_auth_rule` VALUES (178, '2023-05-06 18:20:14', '2023-05-12 16:26:59', 'admin', 'Index/consoleCount', '\\app\\admin\\controller\\', 2, '控制台数据统计', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (179, '2023-05-06 20:21:59', '2023-05-06 20:21:59', 'admin', 'Plugin/getDoc', '\\app\\admin\\controller\\', 139, '获取文档地址', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `php_system_auth_rule` VALUES (180, '2023-05-09 16:14:10', '2023-05-09 16:14:10', 'admin', 'StoreApp/index', '\\app\\admin\\controller\\', 147, '授权应用', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置项' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_config
-- ----------------------------
INSERT INTO `php_system_config` VALUES (1, '2023-03-12 14:02:19', '2023-03-12 19:55:03', 1, '站点名称', 'web_name', 'KFAdmin', 'input', '', '请输入站点名称', 0);
INSERT INTO `php_system_config` VALUES (2, '2023-03-12 14:02:44', '2023-03-12 18:57:33', 1, '站点域名', 'web_url', 'http://localhost:5173', 'input', '', '请输入站点域名', 0);
INSERT INTO `php_system_config` VALUES (3, '2023-03-12 14:06:09', '2023-05-03 16:44:15', 1, '后台图标', 'admin_logo', 'upload/user/e696862766470b7442d179168f9d732d.jpeg', 'uploadify', '', '请上传后台图标', 0);
INSERT INTO `php_system_config` VALUES (4, '2023-04-21 18:42:14', '2023-04-21 18:42:14', 2, 'app_id', 'wx_app_id', '', 'input', '', '请输入微信APP_ID', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置分组' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_config_group
-- ----------------------------
INSERT INTO `php_system_config_group` VALUES (1, '2023-03-12 13:59:18', '2023-04-21 16:54:45', '系统设置', 'system', 'dasdsa', 0, '10', '0');
INSERT INTO `php_system_config_group` VALUES (2, '2023-04-21 18:02:04', '2023-04-21 18:02:04', '微信支付', 'wechat_pay', '', 0, '10', '0');

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

-- ----------------------------
-- Table structure for php_system_upload_cate
-- ----------------------------
DROP TABLE IF EXISTS `php_system_upload_cate`;
CREATE TABLE `php_system_upload_cate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `appid` int(11) NULL DEFAULT NULL COMMENT '所属应用',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  `dir_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类目录',
  `sort` int(11) NULL DEFAULT 0 COMMENT '分类排序',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件分类' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of php_system_upload_cate
-- ----------------------------
INSERT INTO `php_system_upload_cate` VALUES (1, '2022-11-16 20:06:19', '2023-03-06 12:00:36', NULL, '系统附件', 'system', 0, '1');
INSERT INTO `php_system_upload_cate` VALUES (2, '2023-03-06 11:49:34', '2023-03-06 12:07:53', NULL, '用户上传', 'user', 0, '0');

-- ----------------------------
-- Table structure for php_users
-- ----------------------------
DROP TABLE IF EXISTS `php_users`;
CREATE TABLE `php_users`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `create_at` datetime NULL DEFAULT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  `store_id` int(11) NULL DEFAULT NULL COMMENT '所属租户',
  `platform_id` int(11) NULL DEFAULT NULL COMMENT '所属平台',
  `appid` int(11) NULL DEFAULT NULL COMMENT '所属应用',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录密码',
  `headimg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户头像',
  `money` int(11) NULL DEFAULT NULL COMMENT '用户余额',
  `integral` int(11) NULL DEFAULT NULL COMMENT '用户积分',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '用户状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户-记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of php_users
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
