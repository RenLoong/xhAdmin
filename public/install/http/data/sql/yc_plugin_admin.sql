DROP TABLE IF EXISTS `__PREFIX__plugin_admin`;

CREATE TABLE `__PREFIX__plugin_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `create_at` datetime DEFAULT NULL COMMENT '创建时间',
  `update_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `saas_appid` int(11) DEFAULT NULL COMMENT '所属项目ID',
  `role_id` int(11) DEFAULT NULL COMMENT '所属部门',
  `pid` int(11) DEFAULT '0' COMMENT '上级管理员ID',
  `username` varchar(15) NOT NULL DEFAULT '' COMMENT '登录账户',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '用户密码',
  `status` enum('10','20') DEFAULT '10' COMMENT '用户状态：10禁用，20启用',
  `nickname` varchar(20) DEFAULT '' COMMENT '用户昵称',
  `last_login_ip` varchar(50) DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间，删除则有数据',
  `headimg` varchar(255) DEFAULT '' COMMENT '用户头像',
  `is_system` enum('10','20') DEFAULT '10' COMMENT '是否系统：10否，20是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='应用插件-管理员';