ALTER TABLE `phpshop_payment_systems` ADD `company` INT(11) DEFAULT '0';
ALTER TABLE `phpshop_orders` ADD `company` INT(11) DEFAULT '0';
CREATE TABLE `phpshop_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `bank` blob,
  `enabled` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `phpshop_servers` ADD `company_id` INT(11) DEFAULT '0';
ALTER TABLE `phpshop_discount` ADD `block_old_price` enum('0','1') DEFAULT '0';
ALTER TABLE `phpshop_discount` ADD `block_categories` text DEFAULT '';
ALTER TABLE `phpshop_sort` ADD `meta_description` varchar(255) DEFAULT '';

ALTER TABLE `phpshop_baners` CHANGE `count_all` `type` ENUM('0','1') DEFAULT '0';
ALTER TABLE `phpshop_baners` CHANGE `count_today` `display` ENUM('0','1') DEFAULT '0';
ALTER TABLE `phpshop_baners` CHANGE `limit_all` `size` ENUM('0','1','2') DEFAULT '0';
ALTER TABLE `phpshop_shopusers` ADD `bot` VARCHAR(64) DEFAULT '';
UPDATE `phpshop_shopusers` SET `bot` = MD5(CONCAT(`id`,`login`));
ALTER TABLE `phpshop_order_status` ADD `bot_action` ENUM('0','1') DEFAULT '0';

CREATE TABLE `phpshop_dialog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `chat_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `bot` varchar(64) NOT NULL,
  `staffid` enum('0','1') DEFAULT '1',
  `isview` enum('0','1') DEFAULT '1',
  `order_id` INT(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_system` ADD `sort_title_shablon` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_system` ADD `sort_description_shablon` varchar(255) DEFAULT '';

ALTER TABLE `phpshop_dialog` ADD `attachments` VARCHAR(255);
ALTER TABLE `phpshop_delivery` ADD `weight_max` int(11) DEFAULT '0';
ALTER TABLE `phpshop_delivery` ADD `weight_min` int(11) DEFAULT '0';
ALTER TABLE `phpshop_dialog` ADD `isview_user` enum('0','1') DEFAULT '1';

CREATE TABLE `phpshop_dialog_answer` (
  `id` int(11) NOT NULL,
  `name` varchar(64),
  `message` text,
  `enabled` enum('0','1') DEFAULT '1',
  `num` int(11),
  `servers` varchar(255),
  `view` enum('1','2') DEFAULT '1'
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

/*625*/
ALTER TABLE `phpshop_baners` CHANGE `type` `type` ENUM('0','1','2','3') DEFAULT '0';
