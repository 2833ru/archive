DROP TABLE IF EXISTS `phpshop_modules_ozonseller_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_ozonseller_system` (
`id` int(11) NOT NULL auto_increment,
`token` varchar(64) default '',
`client_id` varchar(64) default '',
`status` int(11) NOT NULL,
`price` int(11) NOT NULL,
`fee` int(11) NOT NULL,
`password` varchar(64),
`fee_type` enum('1','2') NOT NULL default '1',
`warehouse` varchar(64) default 'Основной',
`warehouse_id`  varchar(255) NOT NULL,
`type` enum('1','2') NOT NULL default '1',
`version` varchar(64) DEFAULT '1.0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_ozonseller_system` VALUES (1, '', '', '',1,0,'','1','Основной','','1','1.4');

CREATE TABLE IF NOT EXISTS `phpshop_modules_ozonseller_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`date` int(11) NOT NULL,
`message` text NOT NULL,
`order_id` int(11) NOT NULL,
`status` varchar(255) NOT NULL,
`status_code` varchar(64) default 'success',
`type` varchar(64) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `phpshop_modules_ozonseller_categories` (
`id` int(11) NOT NULL,
`name` varchar(255) NOT NULL,
`parent_to` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_orders` ADD `ozonseller_order_data` text default '';
ALTER TABLE `phpshop_categories` ADD `category_ozonseller` int(11) DEFAULT 0;
ALTER TABLE `phpshop_sort_categories` ADD `attribute_ozonseller` int(11) DEFAULT 0;
ALTER TABLE `phpshop_products` ADD `export_ozon` enum('0','1') DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `export_ozon_task_id` int(11) DEFAULT 0;
ALTER TABLE `phpshop_products` ADD `price_ozon` float DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `export_ozon_task_status` varchar(64) default '';
ALTER TABLE `phpshop_products` ADD `barcode_ozon` varchar(255) DEFAULT '';
