
CREATE TABLE `phpshop_modules_key` (
  `path` varchar(64) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `key` text NOT NULL,
  `verification` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_products` CHANGE `uid` `uid` VARCHAR( 64 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL; 
