ALTER TABLE `yc_store_app` 
MODIFY COLUMN `platform` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '应用平台类型（JSON格式）' AFTER `status`;
