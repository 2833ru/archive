ALTER TABLE `phpshop_promotions` CHANGE `free_delivery` `num_check` int(11) DEFAULT '0';
ALTER TABLE `phpshop_payment_systems` ADD `servers` varchar(64) default '';
ALTER TABLE `phpshop_shopusers` ADD `servers` int(11) default 0;
ALTER TABLE `phpshop_orders` ADD `paid` TINYINT(1) DEFAULT NULL;

CREATE TABLE `phpshop_bonus` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `comment` varchar(255) default '',
  `user_id` int(11),
  `order_id` int(11),
  `bonus_operation` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_shopusers` ADD `bonus` int(11) DEFAULT '0';
ALTER TABLE `phpshop_orders` ADD `bonus_minus` int(11) DEFAULT '0';
ALTER TABLE `phpshop_orders` ADD `bonus_plus` int(11) DEFAULT '0';

ALTER TABLE `phpshop_categories`  ADD `tile` ENUM('0','1') DEFAULT '0';
ALTER TABLE `phpshop_sort_categories`  ADD `show_preview` ENUM('0','1') DEFAULT '0';
update `phpshop_categories`  set `tile`='1' where parent_to='0';

ALTER TABLE `phpshop_delivery` ADD `comment` TEXT;
CREATE TABLE IF NOT EXISTS `phpshop_exchanges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `option` blob,
  `type` varchar(64),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;