

DROP TABLE IF EXISTS `phpshop_modules_mobile_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_mobile_system` (
  `id` int(11) NOT NULL auto_increment,
  `message` varchar(255) NOT NULL default '',
  `logo` varchar(255) NOT NULL default '',
  `returncall` enum('1','2') NOT NULL default '1',
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_mobile_system` VALUES (1,'ƒоступна мобильна€ верси€ сайта, перейти?','','1','1.3');