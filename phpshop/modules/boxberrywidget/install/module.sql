DROP TABLE IF EXISTS `phpshop_modules_boxberrywidget_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_boxberrywidget_system` (
  `id` int(11) NOT NULL auto_increment,
  `api_key` varchar(255) default '',
  `token` varchar(255) default '',
  `status` int(11),
  `delivery_id` varchar(64) default '',
  `express_delivery_id` varchar(64) default '',
  `pvz_id` varchar(64) default '',
  `weight` varchar(64) default '',
  `width` varchar(64) default '',
  `height` varchar(64) default '',
  `depth` varchar(64) default '',
  `city` varchar(255) default '',
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_boxberrywidget_system` VALUES (1, '', '', '0', '', '', '', '500', '50', '50', '50', 'Москва', '1.0');

ALTER TABLE `phpshop_delivery` ADD `is_mod` enum('1','2') DEFAULT '1';
ALTER TABLE `phpshop_orders` ADD `boxberry_pvz_id` varchar(64) default '';

CREATE TABLE IF NOT EXISTS `phpshop_modules_boxberrywidget_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;