ALTER TABLE `phpshop_products` CHANGE `yandex_condition` `yandex_condition` ENUM('1','2','3','4') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `yandex_quality` enum('1','2','3','4') DEFAULT '1';
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `type` enum('1','2') NOT NULL default '1';
ALTER TABLE `phpshop_modules_yandexcart_system` ADD `link` enum('0','1') NOT NULL default '0';
ALTER TABLE `phpshop_products` ADD `yandex_link` varchar(255) DEFAULT '';
