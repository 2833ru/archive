ALTER TABLE `phpshop_products` ADD `export_ozon_id` int(11) DEFAULT 0;
ALTER TABLE `phpshop_modules_ozonseller_system` CHANGE `warehouse` `warehouse` TEXT NOT NULL;
ALTER TABLE `phpshop_modules_ozonseller_system` ADD `link` enum('0','1') NOT NULL default '0';