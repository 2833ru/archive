CREATE TABLE IF NOT EXISTS `phpshop_modules_ozonseller_export` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`date` int(11) NOT NULL,
`message` text NOT NULL,
`product_id` int(11) NOT NULL,
`product_name` VARCHAR(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_modules_ozonseller_system` ADD `status_import` varchar(64) default '';
ALTER TABLE `phpshop_modules_ozonseller_system` ADD  `delivery` INT(11) NOT NULL default '0';
