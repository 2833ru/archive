ALTER TABLE `phpshop_modules_yandexcart_system` ADD `stop` enum('0','1') DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `yandex_service_life_days` VARCHAR(64) DEFAULT '';
ALTER TABLE `phpshop_products` CHANGE `yandex_condition` `yandex_condition` ENUM('1','2','3','4') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `yandex_quality` enum('1','2','3','4') DEFAULT '1';

