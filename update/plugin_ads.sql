CREATE TABLE `yc_plugin_ads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `saas_appid` int(11) DEFAULT NULL COMMENT '项目ID',
  `name` varchar(50) DEFAULT NULL COMMENT '位置标识',
  `category` varchar(255) DEFAULT NULL COMMENT '位置名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '广告标题',
  `status` enum('10','20') DEFAULT '10' COMMENT '状态：10禁用，20启用',
  `image_url` text COMMENT '图片链接',
  `link_url` text COMMENT '跳转链接',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='应用插件-图片广告';