DROP TABLE IF EXISTS `phpshop_modules_cdekwidget_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_cdekwidget_system` (
  `id` int(11) NOT NULL auto_increment,
  `password` varchar(255) default '',
  `account` varchar(255) default '',
  `status` int(11),
  `delivery_id` varchar(64) default '',
  `city_from` varchar(64) default '',
  `city_from_code` varchar(64) default '',
  `index_from` varchar(64) default '',
  `default_city` varchar(64) default '',
  `length` varchar(64) default '',
  `weight` varchar(64) default '',
  `width` varchar(64) default '',
  `height` varchar(64) default '',
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_cdekwidget_system` VALUES (1, '', '', '0', '', '', '', '', '', '','', '', '', '1.0');

CREATE TABLE IF NOT EXISTS `phpshop_modules_cdekwidget_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_orders` ADD `cdek_order_data` varchar(255) default '';
ALTER TABLE `phpshop_delivery` ADD `is_mod` enum('1','2') DEFAULT '1';