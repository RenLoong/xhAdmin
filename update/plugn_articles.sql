DROP TABLE IF EXISTS `yc_plugin_articles`;

CREATE TABLE `yc_plugin_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `saas_appid` int(11) DEFAULT NULL COMMENT '项目ID',
  `cid` int(11) DEFAULT NULL COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `status` enum('10','20') DEFAULT '10' COMMENT '状态：10禁用，20启用',
  `content` text COMMENT '内容',
  `view` int(11) DEFAULT '0' COMMENT '热度',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='应用插件-文章内容';