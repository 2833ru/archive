
CREATE TABLE `phpshop_modules_yandexcart_system` (
  `id` int(11) NOT NULL auto_increment,
  `password` varchar(64) default '',
  `token` varchar(64) default '',
  `campaign` varchar(64) default '',
  `status_cancelled` int(11) NOT NULL,
  `status_processing` int(11) NOT NULL,
  `status_delivery` int(11) NOT NULL,
  `payment_delivery` enum('1','2') NOT NULL DEFAULT '1',
  `version` float(2) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;

-- 
-- Дамп данных таблицы `phpshop_modules_yandexcart_system`
-- 

INSERT INTO `phpshop_modules_yandexcart_system` VALUES (1,'C5000001E854F1FA','AQAAAAAWwMREAAM9abPHp2rPcUVyjfTkXKpbbDU','21054994','','','','1','1.0');

CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexcart_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `order_id` varchar(64) NOT NULL DEFAULT '',
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `path` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;
      