

DROP TABLE IF EXISTS `phpshop_modules_sale_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_sale_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1') NOT NULL default '1',
  `serial` varchar(64) NOT NULL default '',
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_sale_system` VALUES (1,'1','','1.4');

ALTER TABLE `phpshop_products` ADD `old_price_sale`  float NOT NULL DEFAULT '0';

UPDATE `phpshop_products` SET `old_price_sale`=`price`;
