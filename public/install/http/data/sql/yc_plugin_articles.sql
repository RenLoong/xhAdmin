DROP TABLE IF EXISTS `__PREFIX__plugin_articles`;

CREATE TABLE `__PREFIX__plugin_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `saas_appid` int(11) DEFAULT NULL COMMENT '项目ID',
  `cid` int(11) DEFAULT NULL COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `desc` varchar(255) DEFAULT NULL COMMENT '简短描述',
  `thumb` varchar(255) DEFAULT NULL COMMENT '文章封面',
  `status` enum('10','20') DEFAULT '10' COMMENT '状态：10禁用，20启用',
  `content` text COMMENT '内容',
  `view` int(11) DEFAULT '0' COMMENT '热度',
  `virtually_view` int(11) DEFAULT '0' COMMENT '虚拟热度',
  `is_alert` tinyint NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='应用插件-文章内容';
