ALTER TABLE `phpshop_promotions` ADD `action` ENUM('1', '2') DEFAULT '1';
ALTER TABLE `phpshop_shopusers` ADD `token` INT(11);
ALTER TABLE `phpshop_shopusers` ADD `token_time` INT(11);
ALTER TABLE `phpshop_servers` ADD `icon` VARCHAR(255);
ALTER TABLE `phpshop_notes` ADD `name` VARCHAR(64), ADD `tel` VARCHAR(64), ADD `mail` VARCHAR(64), ADD `content` TEXT;
