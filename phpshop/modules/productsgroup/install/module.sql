ALTER TABLE `phpshop_products` ADD `productsgroup_check` enum('0','1') NOT NULL DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `productsgroup_products` text NOT NULL;