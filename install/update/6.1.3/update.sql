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
