ALTER TABLE `phpshop_modules_ozonseller_system` CHANGE `warehouse` `warehouse` TEXT NOT NULL;
ALTER TABLE `phpshop_modules_ozonseller_system` ADD `link` enum('0','1') NOT NULL default '0';
