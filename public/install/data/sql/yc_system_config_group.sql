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
  `show` enum('10','20') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '20' COMMENT '是否显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置分组' ROW_FORMAT = DYNAMIC;

INSERT INTO `yc_system_config_group` VALUES (1, '2023-03-12 13:59:18', '2023-04-21 16:54:45', '系统设置', 'system_config', 'AntDesignOutlined', 0, '10', '10', NULL, NULL, '20');
INSERT INTO `yc_system_config_group` VALUES (2, '2023-03-12 13:59:18', '2023-04-21 16:54:45', '版权设置', 'copyright_config', 'AntDesignOutlined', 0, '10', '10', NULL, NULL, '20');

