ALTER TABLE `phpshop_1c_docs` ADD `year` int(11) default 2018;
ALTER TABLE `phpshop_orders` ADD `files` text;
ALTER TABLE `phpshop_categories` ADD `sort_cache` blob;
ALTER TABLE `phpshop_categories` ADD `sort_cache_created_at` int(11);
ALTER TABLE `phpshop_servers` ADD `currency` int(11);
ALTER TABLE `phpshop_servers` ADD `lang` varchar(32);