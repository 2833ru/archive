/*636*/
ALTER TABLE `phpshop_products` ADD `external_code` varchar(64) DEFAULT '';
ALTER TABLE `phpshop_products` ADD INDEX(`external_code`);