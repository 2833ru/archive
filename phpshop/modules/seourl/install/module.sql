

DROP TABLE IF EXISTS `phpshop_modules_seourl_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_seourl_system` (
  `id` int(11) NOT NULL auto_increment,
  `paginator` enum('1','2') NOT NULL default '1',
  `serial` varchar(64) NOT NULL default '',
  `version` FLOAT(2) DEFAULT '1.5' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_seourl_system` VALUES (1,'1','','1.5');
