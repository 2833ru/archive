ALTER TABLE `phpshop_products` ADD `cpa` enum('1','2') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `fee` int(11) DEFAULT '100';
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `status_cancelled_ucm` int(11);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `status_cancelled_urd` int(11);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `status_cancelled_urp` int(11);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `status_cancelled_urq` int(11);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `status_cancelled_uu` int(11);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `region_data` text;
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
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `password` varchar(64);
ALTER TABLE `phpshop_products` ADD `yandex_condition` enum('1','2','3') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `yandex_condition_reason` text;
ALTER TABLE `phpshop_delivery` ADD `yandex_mail_instock` text;
ALTER TABLE `phpshop_delivery` ADD `yandex_mail_outstock` text;
ALTER TABLE `phpshop_products` ADD `barcode` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `model` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `options` BLOB;

ALTER TABLE `phpshop_products` ADD `market_sku` varchar(255) DEFAULT '';

ALTER TABLE `phpshop_modules_yandexcart_system` ADD `description_template` varchar(255);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `auth_token` varchar(64);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `client_id` varchar(255);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `client_token` varchar(255);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `model` varchar(64) default 'ADV';
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `delivery_id` varchar(64);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `campaign_id` varchar(64);
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `import_from` int(11) default 0;
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `use_params` enum('0','1') DEFAULT '0';
ALTER TABLE `phpshop_orders` ADD `yandex_order_id` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `phpshop_products` ADD `cpa` enum('0','1','2') DEFAULT '1';

CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexcart_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `date` int(11) NOT NULL,
    `message` text CHARACTER SET utf8 NOT NULL,
    `order_id` varchar(64) NOT NULL DEFAULT '',
    `status` enum('1','2') NOT NULL DEFAULT '1',
    `path` varchar(64) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_delivery` ADD `yandex_region_id` int(11) DEFAULT '0';
ALTER TABLE `phpshop_delivery` ADD `yandex_delivery_points` text;

ALTER TABLE `phpshop_products` ADD `price_yandex_dbs` float DEFAULT '0';