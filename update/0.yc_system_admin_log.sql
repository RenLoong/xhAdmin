DROP TABLE IF EXISTS `yc_system_admin_log`;

CREATE TABLE `yc_system_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_at` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL COMMENT '管理员',
  `role_id` int(11) DEFAULT NULL COMMENT '管理员角色',
  `action_name` varchar(50) DEFAULT NULL COMMENT '本次操作菜单名称',
  `action_ip` varchar(50) DEFAULT NULL COMMENT '登录IP',
  `city_name` varchar(50) DEFAULT NULL COMMENT '城市名',
  `isp_name` varchar(30) DEFAULT NULL COMMENT '网络运营商',
  `action_type` enum('10','20','30','40') DEFAULT NULL COMMENT '操作类型：10登录，20新增，30修改，40删除',
  `path` text COMMENT '操作路由',
  `params` text COMMENT '操作日志JSON格式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='系统-操作日志';