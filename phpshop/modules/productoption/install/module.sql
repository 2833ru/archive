
ALTER TABLE `phpshop_products` ADD `option1` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_products` ADD `option2` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_products` ADD `option3` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_products` ADD `option4` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_products` ADD `option5` VARCHAR(255) NOT NULL;

--
-- Структура таблицы `phpshop_modules_iconcat_system`
--

DROP TABLE IF EXISTS `phpshop_modules_productoption_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_productoption_system` (
  `id` int(11) NOT NULL auto_increment,
  `option` blob NOT NULL,
  `version` float(2) NOT NULL default '1.0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_productoption_system` VALUES (1,'','1.0');