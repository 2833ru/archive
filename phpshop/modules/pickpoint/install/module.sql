DROP TABLE IF EXISTS `phpshop_modules_pickpoint_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_pickpoint_system` (
  `id` int(11) NOT NULL auto_increment,
  `ikn` varchar(64) NOT NULL default '',
  `login` varchar(64) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `delivery_id` varchar(64) default '',
  `city_from` varchar(64) NOT NULL default '',
  `region_from` varchar(64) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `type_service` varchar(64) NOT NULL default '',
  `type_reception` varchar(64) NOT NULL default '',
  `serial` varchar(64) NOT NULL default '',
  `status` int(11) default 0,
  `length` varchar(64) default '',
  `weight` varchar(64) default '',
  `width` varchar(64) default '',
  `height` varchar(64) default '',
  `fee` int(11) default 0,
  `fee_type` enum('1','2') DEFAULT '1',
  `session_id` varchar(255) default '',
  `session_expire` int(11) default NULL,
  `version` varchar(64) DEFAULT '1.5',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_pickpoint_system` VALUES (1, '', '', '', '', '', '', 'Выбрать ближайший пункт выдачи', 10001, 101, '', 0, '', '', '', '', 0, '1', '', null, '1.5');

ALTER TABLE `phpshop_orders` ADD `pickpoint_data` text default '';