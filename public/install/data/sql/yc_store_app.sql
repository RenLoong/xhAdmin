DROP TABLE IF EXISTS `yc_store_app`;

CREATE TABLE `yc_store_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL COMMENT '所属租户',
  `platform_id` int(11) DEFAULT NULL COMMENT '所属平台',
  `title` varchar(50) DEFAULT NULL COMMENT '应用名称',
  `name` varchar(50) DEFAULT NULL COMMENT '应用标识',
  `url` varchar(255) DEFAULT NULL COMMENT '项目网址，不带结尾斜杠',
  `logo` varchar(255) DEFAULT NULL COMMENT '应用LOGO',
  `status` enum('10','20') DEFAULT NULL COMMENT '应用状态',
  `platform` enum('wechat','mini_wechat','douyin','h5','app','other') DEFAULT 'h5' COMMENT '应用平台类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='租户-平台应用';