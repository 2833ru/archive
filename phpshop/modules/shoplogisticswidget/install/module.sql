DROP TABLE IF EXISTS `phpshop_modules_shoplogisticswidget_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_shoplogisticswidget_system` (
  `id` int(11) NOT NULL auto_increment,
  `api_id` varchar(255) default '',
  `key` varchar(255) default '',
  `status` int(11),
  `delivery_id` varchar(64) default '',
  `dev_mode` enum('0','1') NOT NULL default '0',
  `length` varchar(64) default '',
  `weight` varchar(64) default '',
  `width` varchar(64) default '',
  `height` varchar(64) default '',
  `version` varchar(64) DEFAULT '1.0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_shoplogisticswidget_system` VALUES (1, '', '', '0', '', '0', '', '', '', '', '1.0');

CREATE TABLE IF NOT EXISTS `phpshop_modules_shoplogisticswidget_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `status_code` varchar(64) default 'success',
  `tracking` varchar(64) default '',
  `type` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_orders` ADD `shoplogistics_order_data` varchar(255) default '';
ALTER TABLE `phpshop_delivery` ADD `is_mod` enum('1','2') DEFAULT '1';