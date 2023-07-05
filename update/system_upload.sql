DROP TABLE IF EXISTS `yc_system_upload_cate`;
CREATE TABLE `yc_system_upload_cate`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  `store_id` int NULL DEFAULT NULL COMMENT '租户ID',
  `platform_id` int NULL DEFAULT NULL COMMENT '平台ID',
  `appid` int NULL DEFAULT NULL COMMENT '应用ID',
  `uid` int NULL DEFAULT NULL COMMENT '用户ID',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  `dir_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类目录',
  `sort` int NULL DEFAULT 0 COMMENT '分类排序',
  `is_system` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '是否系统：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-附件分类' ROW_FORMAT = DYNAMIC;
INSERT INTO `yc_system_upload_cate` VALUES (1, '2022-11-16 20:06:19', '2023-03-06 12:00:36', NULL, NULL, NULL, NULL, NULL, '系统附件', 'system_upload', 0, '1');