UPDATE `yc_system_auth_rule` SET `pid` = 7 WHERE `path` = 'Modules/tabs'
UPDATE `yc_system_auth_rule` SET `show` = '1' WHERE `id` = 'Modules/tabs'
UPDATE `yc_system_auth_rule` SET `show` = '1' WHERE `id` = 'Modules/index'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Modules/add'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Modules/edit'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Modules/del'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Modules/indexGetTable'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Fields/index'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Fields/add'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Fields/edit'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Fields/del'
UPDATE `yc_system_auth_rule` SET `show` = '0' WHERE `id` = 'Fields/indexGetTable'
INSERT INTO `yc_system_auth_rule` VALUES (185, '2023-07-01 14:03:18', '2023-07-01 14:03:18', 'admin', 'Curd/index', '\\app\\admin\\controller\\', 20, 'CURD管理', 0, '[\"GET\"]', '1', 'table/index', '', '', '0', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (186, '2023-07-01 14:04:12', '2023-07-01 14:04:12', 'admin', 'Curd/add', '\\app\\admin\\controller\\', 185, '新建CURD', 0, '[\"GET\",\"POST\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (187, '2023-07-01 14:04:52', '2023-07-01 14:05:53', 'admin', 'Curd/edit', '\\app\\admin\\controller\\', 185, '修改CURD', 0, '[\"GET\",\"PUT\"]', '1', 'form/index', '', '', '0', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (188, '2023-07-01 14:05:29', '2023-07-03 10:14:49', 'admin', 'Curd/detail', '\\app\\admin\\controller\\', 185, 'CURD详情', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
INSERT INTO `yc_system_auth_rule` VALUES (189, '2023-07-01 14:08:42', '2023-07-01 14:09:16', 'admin', 'Curd/indexGetTable', '\\app\\admin\\controller\\', 185, 'CURD数据列表', 0, '[\"GET\"]', '1', '', '', '', '0', '0', '0');
