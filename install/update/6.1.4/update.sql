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
