ALTER TABLE `phpshop_servers` ADD `code` varchar(64) default '';
ALTER TABLE `phpshop_servers` ADD `skin` varchar(64) default '';
ALTER TABLE `phpshop_page` CHANGE `flag` `servers` VARCHAR(64);
ALTER TABLE `phpshop_menu` ADD `servers` varchar(64) default '';