

DROP TABLE IF EXISTS `phpshop_modules_russianpostcalc_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_russianpostcalc_system` (
  `id` int(11) NOT NULL auto_increment,
  `key` varchar(64) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `delivery_id` int(11),
  `delivery_index` int(11),
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `cennost` float NOT NULL,
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_russianpostcalc_system` VALUES (1,'','','','','2','30.0','1.1');

ALTER TABLE `phpshop_delivery` ADD `is_mod` enum('1','2') DEFAULT '1';
