ALTER TABLE `yc_users` ADD COLUMN `nickname` varchar(50) NULL DEFAULT '' COMMENT '用户昵称' AFTER `password`;
ALTER TABLE `yc_users` MODIFY COLUMN `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '用户余额' AFTER `headimg`;
ALTER TABLE `yc_users` MODIFY COLUMN `integral` int NULL DEFAULT 0 COMMENT '用户积分' AFTER `money`;