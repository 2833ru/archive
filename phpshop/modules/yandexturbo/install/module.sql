CREATE TABLE `phpshop_modules_yandexturbo_system` (
  `id` int(11) NOT NULL auto_increment,
  `auth_token` varchar(64),
  `delivery_id` varchar(64),
  `version` varchar(64) default '1.0',
  `options` BLOB,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;

INSERT INTO `phpshop_modules_yandexturbo_system` VALUES (1,'',0,'1.0','');

CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexturbo_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `date` int(11) NOT NULL,
    `message` text CHARACTER SET utf8 NOT NULL,
    `order_id` varchar(64) NOT NULL DEFAULT '',
    `status` enum('1','2') NOT NULL DEFAULT '1',
    `path` varchar(64) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_orders` ADD `yandex_order_id` varchar(255) NOT NULL DEFAULT '';
