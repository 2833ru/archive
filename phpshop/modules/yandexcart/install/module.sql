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
ALTER TABLE `phpshop_delivery` ADD `yandex_day_min` int(11) DEFAULT '1';
ALTER TABLE `phpshop_delivery` ADD `yandex_order_before` int(11) DEFAULT '16';
ALTER TABLE `phpshop_products` ADD `yandex_min_quantity` int(11) DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `yandex_step_quantity` int(11) DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `vendor_code` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `vendor_name` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `yandex_condition` enum('1','2','3') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `yandex_condition_reason` text;

CREATE TABLE `phpshop_modules_yandexcart_system` (
  `id` int(11) NOT NULL auto_increment,
  `password` varchar(64),
  `version` varchar(64) default '2.1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;

-- 
-- Дамп данных таблицы `phpshop_modules_yandexcart_system`
-- 

INSERT INTO `phpshop_modules_yandexcart_system` VALUES (1,'','2.4');
  