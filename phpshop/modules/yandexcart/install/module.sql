ALTER TABLE `phpshop_delivery` ADD `yandex_mail_instock` text;
ALTER TABLE `phpshop_delivery` ADD `yandex_mail_outstock` text;
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
ALTER TABLE `phpshop_delivery` ADD `yandex_delivery_points` text;
ALTER TABLE `phpshop_delivery` ADD `yandex_region_id` int(11) DEFAULT '0';
ALTER TABLE `phpshop_delivery` ADD `yandex_day_min` int(11) DEFAULT '1';
ALTER TABLE `phpshop_delivery` ADD `yandex_order_before` int(11) DEFAULT '16';
ALTER TABLE `phpshop_products` ADD `yandex_min_quantity` int(11) DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `yandex_step_quantity` int(11) DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `vendor_code` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `vendor_name` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `manufacturer` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `yandex_condition` enum('1','2','3') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `yandex_condition_reason` text;
ALTER TABLE `phpshop_products` ADD `barcode` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `model` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `market_sku` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `cpa` enum('0','1','2') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `price_yandex_dbs` float DEFAULT '0';

CREATE TABLE `phpshop_modules_yandexcart_system` (
  `id` int(11) NOT NULL auto_increment,
  `password` varchar(64),
  `auth_token` varchar(64),
  `client_id` varchar(255),
  `client_token` varchar(255),
  `campaign_id` varchar(64),
  `description_template` varchar(255),
  `delivery_id` varchar(64),
  `model` varchar(64),
  `import_from` int(11) default 0,
  `use_params` enum('0','1') DEFAULT '0',
  `version` varchar(64) default '2.0',
  `options` BLOB,
  `stop` enum('0','1') DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;

-- 
-- Дамп данных таблицы `phpshop_modules_yandexcart_system`
--
INSERT INTO `phpshop_modules_yandexcart_system` VALUES (1,'', '', '', '', '', '', 0, 'ADV', 0, 0, '3.5','','0');

CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexcart_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `date` int(11) NOT NULL,
    `message` text CHARACTER SET utf8 NOT NULL,
    `order_id` varchar(64) NOT NULL DEFAULT '',
    `status` enum('1','2') NOT NULL DEFAULT '1',
    `path` varchar(64) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_orders` ADD `yandex_order_id` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `phpshop_products` ADD `yandex_service_life_days` VARCHAR(64) DEFAULT '';
