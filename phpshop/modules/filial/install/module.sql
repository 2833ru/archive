
ALTER TABLE  `phpshop_page` ADD  `target_url_page` VARCHAR(255);
ALTER TABLE  `phpshop_menu` ADD  `target_url_menu` VARCHAR(255);

DROP TABLE IF EXISTS `phpshop_modules_filial_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_filial_system` (
  `id` int(11) NOT NULL auto_increment,
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_filial_system` VALUES (1,'1.1');
