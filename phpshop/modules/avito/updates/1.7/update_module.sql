ALTER TABLE `phpshop_categories` ADD `condition_cat_avito` varchar(64) DEFAULT 'Новое';
ALTER TABLE `phpshop_categories` ADD `export_cat_avito` enum('0','1') DEFAULT '0';