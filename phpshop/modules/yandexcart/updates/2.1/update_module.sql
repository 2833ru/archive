ALTER TABLE `phpshop_modules_yandexcart_system` ADD `password` varchar(64);
ALTER TABLE `phpshop_products` ADD `yandex_condition` enum('1','2','3') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `yandex_condition_reason` text;
ALTER TABLE `phpshop_delivery` ADD `yandex_mail_instock` text;
ALTER TABLE `phpshop_delivery` ADD `yandex_mail_outstock` text;