ALTER TABLE `phpshop_products` ADD `length` varchar(64) default '';
ALTER TABLE `phpshop_products` ADD `width` varchar(64) default '';
ALTER TABLE `phpshop_products` ADD `height` varchar(64) default '';

ALTER TABLE `phpshop_page_categories` ADD `icon` VARCHAR(255) DEFAULT '';
ALTER TABLE `phpshop_page_categories` ADD `title` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_page_categories` ADD `description` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_page_categories` ADD `keywords` text;
ALTER TABLE `phpshop_photo_categories` ADD `count` tinyint(11) default 2;