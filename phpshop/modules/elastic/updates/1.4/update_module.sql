ALTER TABLE `phpshop_modules_elastic_system` ADD `use_additional_categories` enum('0','1') NOT NULL default '0';
ALTER TABLE `phpshop_modules_elastic_system` ADD `misprints_from_cnt` int(11) DEFAULT 4;
ALTER TABLE `phpshop_modules_elastic_system` ADD `max_categories` int(11) DEFAULT 10;
