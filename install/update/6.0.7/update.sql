ALTER TABLE `phpshop_products` ADD `length` varchar(64) default '';
ALTER TABLE `phpshop_products` ADD `width` varchar(64) default '';
ALTER TABLE `phpshop_products` ADD `height` varchar(64) default '';

ALTER TABLE `phpshop_page_categories` ADD `icon` VARCHAR(255) DEFAULT '';
ALTER TABLE `phpshop_page_categories` ADD `title` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_page_categories` ADD `description` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_page_categories` ADD `keywords` text;
ALTER TABLE `phpshop_photo_categories` ADD `count` tinyint(11) default 2;

ALTER TABLE `phpshop_users` ADD `token` VARCHAR(64);
ALTER TABLE `phpshop_slider` ADD `mobile` enum('0','1') default '0';
ALTER TABLE `phpshop_search_jurnal` ADD `ip` VARCHAR(64);

ALTER TABLE `phpshop_order_status` ADD `num` INT(11) DEFAULT '0';
ALTER TABLE `phpshop_orders` ADD `date` INT(11) DEFAULT '0';

CREATE TABLE `phpshop_notes` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `message` text ,
  `status` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;