

DROP TABLE IF EXISTS `phpshop_modules_moysklad_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_moysklad_system` (
  `id` int(11) NOT NULL auto_increment,
  `merchant_user` varchar(64) NOT NULL DEFAULT '',
  `merchant_pwd` varchar(64) NOT NULL DEFAULT '',
  `org_code` varchar(64) NOT NULL DEFAULT '',
  `nds` varchar(64) NOT NULL DEFAULT '18',
  `stock_option` varchar(64) NOT NULL,
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_moysklad_system` VALUES (1,'','','orgname','18','','1.2');

CREATE TABLE IF NOT EXISTS `phpshop_modules_moysklad_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `order_id` varchar(64) NOT NULL DEFAULT '',
  `status` enum('1','2') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;
