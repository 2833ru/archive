

DROP TABLE IF EXISTS `phpshop_modules_yandexorder_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexorder_system` (
  `id` int(11) NOT NULL auto_increment,
  `img` varchar(64) NOT NULL default '',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_yandexorder_system` VALUES (1,'https://help.yandex.ru/help.yandex.ru/partnermarket/image/4.png','');
