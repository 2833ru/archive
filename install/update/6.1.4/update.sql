ALTER TABLE `phpshop_dialog` ADD `attachments` VARCHAR(255);
ALTER TABLE `phpshop_dialog` ADD `isview_user` enum('0','1') DEFAULT '1';

ALTER TABLE `phpshop_delivery` ADD `weight_max` int(11) DEFAULT '0';
ALTER TABLE `phpshop_delivery` ADD `weight_min` int(11) DEFAULT '0';

CREATE TABLE `phpshop_dialog_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64),
  `message` text,
  `enabled` enum('0','1') DEFAULT '1',
  `num` int(11),
  `servers` varchar(255),
  `view` enum('1','2') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_products` ADD `price_purch` FLOAT DEFAULT '0';
ALTER TABLE `phpshop_shopusers` ADD `dialog_ban` ENUM('0','1') DEFAULT '0';

/*620*/
ALTER TABLE `phpshop_promotions` ADD `disable_categories` ENUM('0','1') DEFAULT '0';
ALTER TABLE `phpshop_baners` ADD `image` VARCHAR(255), ADD `description` TEXT;
ALTER TABLE `phpshop_baners` CHANGE `type` `type` ENUM('0','1','2') DEFAULT '0';
ALTER TABLE `phpshop_baners` ADD `link` VARCHAR(255);
ALTER TABLE `phpshop_baners` ADD `mobile` ENUM('0','1') DEFAULT '0';
ALTER TABLE `phpshop_slider` ADD `name` VARCHAR(255);
ALTER TABLE `phpshop_slider` ADD `link_text` VARCHAR(255);
ALTER TABLE `phpshop_shopusers_status` ADD `warehouse` enum('0','1') DEFAULT '1';

/*621*/
ALTER TABLE `phpshop_delivery` ADD `categories_check` ENUM('0','1') DEFAULT '0', ADD `categories` VARCHAR(255);

/*622*/
DROP TABLE IF EXISTS `phpshop_exchanges_log`;
CREATE TABLE IF NOT EXISTS `phpshop_exchanges_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  `option` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

/*623*/
ALTER TABLE `phpshop_newsletter` ADD `servers` INT(11) DEFAULT '0';
