ALTER TABLE  `phpshop_shopusers_status` ADD  `cumulative_discount_check` INT NOT NULL AFTER  `enabled` ,
ADD  `cumulative_discount` BLOB NOT NULL AFTER  `cumulative_discount_check` ;

ALTER TABLE  `phpshop_shopusers` ADD  `cumulative_discount` INT NOT NULL AFTER  `data_adres` ;
ALTER TABLE  `phpshop_order_status` ADD  `cumulative_action` ENUM(  '0',  '1' ) NOT NULL AFTER  `sklad_action` ;

ALTER TABLE  `phpshop_orders` ADD  `sum` FLOAT ;

ALTER TABLE  `phpshop_sort_categories` ADD  `product` enum('0','1') NOT NULL DEFAULT '0' ;

CREATE TABLE IF NOT EXISTS `phpshop_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `template` int(11) DEFAULT '0',
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
