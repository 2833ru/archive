ALTER TABLE `phpshop_products` ADD `yandex_min_quantity` int(11) DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `yandex_step_quantity` int(11) DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `vendor_code` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `vendor_name` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` DROP `cpa`;
ALTER TABLE `phpshop_products` DROP `fee`;
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `password` varchar(64);
ALTER TABLE `phpshop_products` ADD `yandex_condition` enum('1','2','3') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `yandex_condition_reason` text;
ALTER TABLE `phpshop_delivery` ADD `yandex_mail_instock` text;
ALTER TABLE `phpshop_delivery` ADD `yandex_mail_outstock` text;
ALTER TABLE `phpshop_products` ADD `manufacturer` varchar(255) DEFAULT '';
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