ALTER TABLE `phpshop_modules_ozonseller_system` ADD  `warehouse` varchar(64) default 'Основной';
ALTER TABLE `phpshop_products` ADD `barcode_ozon` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_modules_ozonseller_system` ADD `type` enum('1','2') NOT NULL default '1';
ALTER TABLE `phpshop_modules_ozonseller_system` ADD `warehouse_id` VARCHAR(255) NOT NULL;
