ALTER TABLE `phpshop_products` ADD `cpa` enum('1','2') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `fee` int(11) DEFAULT '100';
ALTER TABLE `phpshop_delivery` ADD `yandex_enabled` enum('1','2') DEFAULT '1';
ALTER TABLE `phpshop_delivery` ADD `yandex_day` int(11) DEFAULT '2';
ALTER TABLE `phpshop_delivery` ADD `yandex_type` enum('1','2','3') DEFAULT '1';
ALTER TABLE `phpshop_delivery` ADD `yandex_payment` enum('1','2','3') DEFAULT '1';
ALTER TABLE `phpshop_delivery` ADD `yandex_outlet` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_delivery` ADD `yandex_check` enum('1','2') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `manufacturer_warranty` enum('1','2') DEFAULT '2';
ALTER TABLE `phpshop_products` ADD `sales_notes` varchar(50) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `country_of_origin` varchar(50) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `adult` enum('1','2') DEFAULT '2';
ALTER TABLE `phpshop_products` ADD `delivery` enum('1','2') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `pickup` enum('1','2') DEFAULT '2';
ALTER TABLE `phpshop_products` ADD `store` enum('1','2') DEFAULT '2';
ALTER TABLE `phpshop_sort_categories` ADD `yandex_param` enum('1','2') DEFAULT '1';
ALTER TABLE `phpshop_sort_categories` ADD `yandex_param_unit` varchar(64) DEFAULT '';
ALTER TABLE `phpshop_delivery` ADD `yandex_day_min` int(11) DEFAULT '1';
ALTER TABLE `phpshop_delivery` ADD `yandex_order_before` int(11) DEFAULT '16';

CREATE TABLE `phpshop_modules_yandexcart_system` (
  `id` int(11) NOT NULL auto_increment,
  `password` varchar(64) default '',
  `token` varchar(64) default '',
  `campaign` varchar(64) default '',
  `status_cancelled` int(11),
  `status_processing` int(11),
  `status_delivery` int(11),
  `status_delivered` int(11),
  `status_cancelled_ucm` int(11),
  `status_cancelled_urd` int(11),
  `status_cancelled_urp` int(11),
  `status_cancelled_urq` int(11),
  `status_cancelled_uu` int(11),
  `region_data` text,
  `version` float(2) default '1.4',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;

-- 
-- Дамп данных таблицы `phpshop_modules_yandexcart_system`
-- 

INSERT INTO `phpshop_modules_yandexcart_system` VALUES (1,'','','','','','','','','','','','','','1.4');

CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexcart_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `order_id` varchar(64) NOT NULL DEFAULT '',
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `path` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;
      