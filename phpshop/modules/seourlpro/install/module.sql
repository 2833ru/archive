ALTER TABLE `phpshop_categories` ADD `cat_seo_name` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_products` ADD `prod_seo_name` VARCHAR(255) NOT NULL;

DROP TABLE IF EXISTS `phpshop_modules_seourlpro_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_seourlpro_system` (
  `id` int(11) NOT NULL auto_increment,
  `paginator` enum('1','2') NOT NULL default '1',
  `serial` varchar(64) NOT NULL default '',
  `version` FLOAT(2) DEFAULT '1.5' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_seourlpro_system` VALUES (1,'1','','1.5');
