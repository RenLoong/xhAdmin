DROP TABLE IF EXISTS `yc_plugin_roles`;

CREATE TABLE `yc_plugin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime DEFAULT NULL COMMENT '更新时间',
  `saas_appid` int(11) DEFAULT NULL COMMENT '所属项目ID',
  `pid` int(11) DEFAULT '0' COMMENT '上级管理员，0顶级',
  `title` varchar(50) DEFAULT NULL COMMENT '部门名称',
  `rule` text COMMENT '部门权限',
  `is_system` enum('10','20') DEFAULT '10' COMMENT '是否系统：10不是系统，20是系统',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='应用插件-角色管理';