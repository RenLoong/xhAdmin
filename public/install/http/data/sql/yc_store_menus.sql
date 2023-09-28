DROP TABLE IF EXISTS `__PREFIX__store_menus`;

CREATE TABLE `__PREFIX__store_menus`  (
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
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '渠道-菜单' ROW_FORMAT = DYNAMIC;

INSERT INTO `__PREFIX__store_menus` VALUES (1, '2022-10-27 17:22:51', '2023-07-24 14:15:06', 'store', 'Index/index', '\\app\\store\\controller\\', 0, '首页', 0, '[\"GET\"]', '10', 'remote/index', '/remote/store/index', 'HomeOutlined', '20', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (2, '2022-10-27 17:22:51', '2023-07-24 14:14:16', 'store', 'Index/welcome', '\\app\\store\\controller\\', 1, '控制台', 0, '[\"GET\"]', '10', 'remote/index', '/remote/store/index', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (3, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/login', '\\app\\store\\controller\\', 1, '系统登录', 0, '[\"POST\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (4, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/site', '\\app\\store\\controller\\', 1, '获取应用信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (5, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/user', '\\app\\store\\controller\\', 1, '获取管理员信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (6, '2022-10-27 17:22:51', '2023-04-16 17:16:04', 'store', 'Publics/menus', '\\app\\store\\controller\\', 1, '获取菜单信息', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (7, '2022-11-16 19:33:49', '2023-04-16 17:16:04', 'store', 'Uploadify/tabs', '\\app\\admin\\controller\\', 1, '附件模块', 0, '[\"GET\"]', '10', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (8, '2022-11-16 19:34:37', '2023-05-03 16:12:30', 'store', 'SystemUpload/index', '\\app\\admin\\controller\\', 7, '附件管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (9, '2022-11-16 19:35:31', '2023-05-03 16:12:42', 'store', 'SystemUpload/upload', '\\app\\admin\\controller\\', 8, '上传附件', 0, '[\"GET\",\"POST\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (10, '2022-11-16 19:36:17', '2023-05-03 16:12:57', 'store', 'SystemUpload/del', '\\app\\admin\\controller\\', 8, '删除附件', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (11, '2022-11-16 19:38:19', '2023-05-03 16:13:24', 'store', 'SystemUpload/table', '\\app\\admin\\controller\\', 8, '附件列表', 0, '[\"GET\"]', '10', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (12, '2022-11-16 19:41:08', '2023-04-16 17:16:04', 'store', 'SystemUploadCate/index', '\\app\\admin\\controller\\', 7, '附件分类', 0, '[\"GET\"]', '20', 'table/index', '', '', '20', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (13, '2022-11-16 19:41:58', '2023-04-23 19:25:14', 'store', 'SystemUploadCate/add', '\\app\\admin\\controller\\', 12, '添加附件分类', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (14, '2022-11-16 19:42:37', '2023-04-21 11:51:40', 'store', 'SystemUploadCate/edit', '\\app\\admin\\controller\\', 12, '修改附件分类', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (15, '2022-11-16 19:43:35', '2023-04-21 11:51:48', 'store', 'SystemUploadCate/del', '\\app\\admin\\controller\\', 12, '删除附件分类', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (16, '2022-11-16 19:45:01', '2023-04-21 11:51:56', 'store', 'SystemUploadCate/table', '\\app\\admin\\controller\\', 12, '附件分类表格', 0, '[\"GET\"]', '10', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (17, '2022-11-16 23:04:14', '2023-04-16 17:16:04', 'store', 'Publics/loginout', '\\app\\admin\\controller\\', 1, '退出登录', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '20', '20');
INSERT INTO `__PREFIX__store_menus` VALUES (18, '2023-05-03 16:57:25', '2023-05-03 17:06:25', 'store', '', '\\app\\store\\controller\\', 0, '平台', 0, '[\"GET\"]', '10', 'none/index', '', 'ShopOutlined', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (19, '2023-05-03 17:05:10', '2023-07-24 23:59:43', 'store', 'Index/test1', '\\app\\store\\controller\\', 0, '测试方法', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', 'UserOutlined', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (20, '2023-05-03 17:08:07', '2023-07-24 14:45:43', 'store', 'StoreApp/applet', '\\app\\store\\controller\\', 21, '小程序配置', 0, '[\"GET\",\"PUT\",\"POST\"]', '20', 'remote/index', 'remote/store/applet', 'SettingTwotone', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (21, '2023-05-03 17:11:21', '2023-07-23 15:19:34', 'store', 'StoreApp/index', '\\app\\store\\controller\\', 18, '项目管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (22, '2023-05-03 17:11:55', '2023-07-23 15:24:18', 'store', 'StoreApp/create', '\\app\\store\\controller\\', 21, '创建项目', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (23, '2023-05-03 17:13:06', '2023-07-22 20:26:22', 'store', 'Users/index', '\\app\\store\\controller\\', 0, '用户管理', 0, '[\"GET\"]', '20', 'table/index', '', 'UserOutlined', '20', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (24, '2023-05-03 17:13:59', '2023-07-23 15:25:13', 'store', 'StoreApp/config', '\\app\\store\\controller\\', 21, '项目设置', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (25, '2023-05-03 17:38:41', '2023-05-03 17:38:41', 'store', 'Users/add', '\\app\\store\\controller\\', 23, '添加用户', 0, '[\"GET\",\"POST\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (26, '2023-05-03 17:39:14', '2023-07-23 15:25:36', 'store', 'Users/edit', '\\app\\store\\controller\\', 23, '修改用户', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (27, '2023-05-03 17:39:56', '2023-05-03 17:39:56', 'store', 'Users/del', '\\app\\store\\controller\\', 23, '删除用户', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (28, '2023-05-03 17:40:24', '2023-05-03 17:40:34', 'store', 'Users/indexGetTable', '\\app\\store\\controller\\', 23, '用户表格', 0, '[\"GET\"]', '20', 'none/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (29, '2023-05-03 17:42:57', '2023-05-03 17:42:57', 'store', 'UserFinance/index', '\\app\\store\\controller\\', 23, '财务管理', 0, '[\"GET\"]', '20', 'table/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (30, '2023-05-03 17:44:02', '2023-05-03 17:44:36', 'store', 'UserFinance/actionFinance', '\\app\\store\\controller\\', 29, '操作财务', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (31, '2023-05-03 19:53:40', '2023-07-23 16:11:28', 'store', 'StoreApp/edit', '\\app\\store\\controller\\', 21, '修改项目', 0, '[\"GET\",\"PUT\"]', '20', 'form/index', '', '', '10', '10', '10');
INSERT INTO `__PREFIX__store_menus` VALUES (32, '2023-06-16 11:44:08', '2023-07-23 15:27:31', 'store', 'StoreApp/del', '\\app\\store\\controller\\', 21, '删除项目', 0, '[\"GET\",\"DELETE\"]', '20', 'none/index', '', '', '10', '10', '10');
