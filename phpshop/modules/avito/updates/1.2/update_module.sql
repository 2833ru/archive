ALTER TABLE `phpshop_modules_avito_system` ADD `use_params` enum('0','1') NOT NULL default '0';
ALTER TABLE `phpshop_products` ADD `ad_type_avito` varchar(64) DEFAULT 'Товар приобретен на продажу';