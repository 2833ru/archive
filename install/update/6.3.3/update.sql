/*634*/
ALTER TABLE `phpshop_payment_systems` ADD `status` INT(11) DEFAULT '0';

/*636*/
ALTER TABLE `phpshop_products` ADD `external_code` varchar(64) DEFAULT '';
