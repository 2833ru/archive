ALTER TABLE `phpshop_products` CHANGE `yandex_condition` `yandex_condition` ENUM('1','2','3','4') DEFAULT '1';
ALTER TABLE `phpshop_products` ADD `yandex_quality` enum('1','2','3','4') DEFAULT '1';
