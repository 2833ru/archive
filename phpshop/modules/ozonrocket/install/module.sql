DROP TABLE IF EXISTS `phpshop_modules_ozonrocket_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_ozonrocket_system` (
`id` int(11) NOT NULL auto_increment,
`token` varchar(64) default '',
`client_id` varchar(64) default '',
`client_secret` varchar(64) default '',
`dev_mode` enum('0','1') DEFAULT '0',
`btn_text` varchar(64) NOT NULL,
`default_city` varchar(64) default '',
`type_transfer` varchar(64) default 'DropOff',
`from_place_id` varchar(64) default '9042178932000',
`hide_pvz` enum('0','1') DEFAULT '0',
`hide_postamat` enum('0','1') DEFAULT '0',
`show_delivery_time` enum('0','1') DEFAULT '1',
`show_delivery_price` enum('0','1') DEFAULT '1',
`delivery_id` int(11) default 0,
`status` int(11) NOT NULL,
`length` varchar(64) default '',
`weight` varchar(64) default '',
`width` varchar(64) default '',
`height` varchar(64) default '',
`fee` int(11) default 0,
`fee_type` enum('1','2') DEFAULT '1',
`version` varchar(64) DEFAULT '1.0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_ozonrocket_system` VALUES (1, '', '', '', '0', '', '', 'DropOff', '9042178932000', '0', '0', '1', '1', '0', '', '', '', '', '', 0 , 1, '1.0');

CREATE TABLE IF NOT EXISTS `phpshop_modules_ozonrocket_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`date` int(11) NOT NULL,
`message` text NOT NULL,
`order_id` int(11) NOT NULL,
`status` varchar(255) NOT NULL,
`status_code` varchar(64) default 'success',
`type` varchar(64) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_orders` ADD `ozonrocket_order_data` text default '';
ALTER TABLE `phpshop_delivery` ADD `is_mod` enum('1','2') DEFAULT '1';