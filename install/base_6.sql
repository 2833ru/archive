DROP TABLE IF EXISTS phpshop_1c_docs;
CREATE TABLE phpshop_1c_docs (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `cid` varchar(64) DEFAULT '',
  `datas` int(11) DEFAULT '0',
  `datas_f` int(11) DEFAULT '0',
  `year` int(11) DEFAULT '2018',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_1c_jurnal;
CREATE TABLE phpshop_1c_jurnal (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datas` varchar(64) DEFAULT '0',
  `p_name` varchar(64) DEFAULT '',
  `f_name` varchar(64) DEFAULT '',
  `time` float DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_baners;
CREATE TABLE phpshop_baners (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `content` text,
  `type` ENUM('0','1') DEFAULT '0',
  `display` ENUM('0','1') DEFAULT '0',
  `flag` enum('0','1') DEFAULT '0',
  `datas` varchar(32) DEFAULT '',
  `size` ENUM('0','1','2') DEFAULT '0',
  `dir` varchar(255) DEFAULT '',
  `dop_cat` varchar(255) DEFAULT '',
  `servers` varchar(64) DEFAULT '',
  `skin` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_baners VALUES ('4', 'Баннер в колонке', '<p><img src="/UserFiles/Image/Trial/banner_vert.jpg" width="414" height="648" /></p>', '0', '0', '1', '', '0', '', '', '', '');
DROP TABLE IF EXISTS phpshop_black_list;
CREATE TABLE phpshop_black_list (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT '',
  `datas` varchar(32) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_categories;
CREATE TABLE phpshop_categories (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `num` int(11) DEFAULT '0',
  `parent_to` int(11) NOT NULL DEFAULT '0',
  `yml` enum('0','1') DEFAULT '1',
  `num_row` enum('1','2','3','4','5') DEFAULT '3',
  `num_cow` tinyint(11) DEFAULT '0',
  `sort` blob,
  `content` text,
  `vid` enum('0','1') DEFAULT '0',
  `name_rambler` varchar(255) DEFAULT '',
  `servers` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `title_enabled` enum('0','1','2') DEFAULT '0',
  `title_shablon` varchar(255) DEFAULT '',
  `descrip` varchar(255) DEFAULT '',
  `descrip_enabled` enum('0','1','2') DEFAULT '0',
  `descrip_shablon` varchar(255) DEFAULT '',
  `keywords` varchar(255) DEFAULT '',
  `keywords_enabled` enum('0','1','2') DEFAULT '0',
  `keywords_shablon` varchar(255) DEFAULT '',
  `skin` varchar(64) DEFAULT '',
  `skin_enabled` enum('0','1') DEFAULT '0',
  `order_by` enum('1','2','3') DEFAULT '3',
  `order_to` enum('1','2') DEFAULT '1',
  `secure_groups` varchar(255) DEFAULT '',
  `icon` varchar(255) DEFAULT '',
  `icon_description` varchar(255) DEFAULT '',
  `dop_cat` varchar(255) DEFAULT '',
  `parent_title` int(11) DEFAULT '0',
  `sort_cache` blob,
  `sort_cache_created_at` int(11) DEFAULT NULL,
  `menu` enum('0','1') DEFAULT '0',
  `option6` text,
  `option7` text,
  `option8` text,
  `option9` text,
  `option10` text,
  `cat_seo_name` varchar(255) DEFAULT '',
  `cat_seo_name_old` varchar(255) DEFAULT '',
  `tile` ENUM('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_to` (`parent_to`),
  KEY `servers` (`servers`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_categories VALUES ('1', 'Одежда, обувь и аксессуары', '1', '0', '1', '4', '0', 'a:4:{i:0;s:1:"5";i:1;s:1:"3";i:2;s:1:"4";i:3;s:1:"2";}', '<p>Это каталог товаров, описания для каталога можно добавлять в меню Товары - Каталоги. В закладке Характеристики вы выбираете, какие фильтры будут выводиться в этом каталоге. </p>', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs4.jpg', '', '', '0',  NULL,  NULL, '1',  NULL,  NULL,  NULL,  NULL,  NULL, 'odezhda-obuv-i-aksessuary', '','0');
INSERT INTO phpshop_categories VALUES ('2', 'Красота и здоровье', '2', '0', '1', '3', '0', 'a:3:{i:0;s:1:"8";i:1;s:1:"7";i:2;s:1:"9";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs5.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('3', 'Книги', '3', '0', '1', '2', '0', 'N;', '<p>Этот каталог выводится дополнительно в каталоге Одежда, обувь и аксессуары - Детям, настроить вывод каталога можно в меню Товары - Каталоги - Дополнительные каталоги.</p>', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs13.jpg', '', '#10#10#', '0',  NULL,  NULL, '1',  NULL,  NULL,  NULL,  NULL,  NULL, 'knigi', '','0');
INSERT INTO phpshop_categories VALUES ('4', 'Строительство и ремонт', '4', '0', '1', '3', '0', 'a:3:{i:0;s:2:"11";i:1;s:2:"12";i:2;s:2:"13";}', '<p>Это Каталог товаров в разделе Товары - Каталоги. Вы можете заводить любое количество каталогов, менять уровни вложенности. Вы можете указать, сколько товаров в длину вы хотите видеть на витрине магазина. Вы можете добавлять описания к каталогам товаров.&nbsp;</p>\r\n<p>Можно добавить этот каталог в другой каталог, по кнопке Дополнительные каталоги.</p>', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs10.jpg', '', '', '0',  NULL,  NULL, '1',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('5', 'Электроника', '5', '0', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '1', '3', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'elektronika', '','0');
INSERT INTO phpshop_categories VALUES ('6', 'Продукты питания', '6', '0', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '1', '3', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('7', 'Автотовары', '7', '0', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '1', '3', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('8', 'Женщинам', '1', '1', '1', '4', '0', 'a:4:{i:0;s:1:"5";i:1;s:1:"3";i:2;s:1:"4";i:3;s:1:"2";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs4.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'zhenschinam', '','0');
INSERT INTO phpshop_categories VALUES ('9', 'Мужчинам', '3', '1', '1', '4', '0', 'a:3:{i:0;s:1:"5";i:1;s:1:"3";i:2;s:1:"4";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs1.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'muzhchinam', '','0');
INSERT INTO phpshop_categories VALUES ('10', 'Детям', '2', '1', '1', '3', '0', 'a:2:{i:0;s:1:"4";i:1;s:1:"2";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs3.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'detyam', '','0');
INSERT INTO phpshop_categories VALUES ('11', 'Макияж', '1', '2', '1', '3', '0', 'a:3:{i:0;s:1:"8";i:1;s:1:"7";i:2;s:1:"9";}', '<p>Вы можете вывести каталог в главное меню сайта, поставив галку в меню Товары - Каталоги - Опции вывода - Главное меню.</p>', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs6.jpg', '', '', '0',  NULL,  NULL, '1',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('12', 'Уход за лицом', '2', '2', '1', '3', '0', 'a:3:{i:0;s:1:"8";i:1;s:1:"7";i:2;s:1:"9";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs7.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'uhod-za-licom', '','0');
INSERT INTO phpshop_categories VALUES ('13', 'Уход за волосами', '3', '2', '1', '3', '0', 'a:3:{i:0;s:1:"8";i:1;s:1:"7";i:2;s:1:"9";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs5.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'uhod-za-volosami', '','0');
INSERT INTO phpshop_categories VALUES ('14', 'Инструменты', '1', '4', '1', '3', '0', 'a:3:{i:0;s:2:"11";i:1;s:2:"12";i:2;s:2:"13";}', '<p>Описание каталога задается в меню Товары - Каталоги - Описание.</p>', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs12.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'instrumenty', '','0');
INSERT INTO phpshop_categories VALUES ('15', 'Сантехника', '2', '4', '1', '1', '0', 'a:3:{i:0;s:2:"11";i:1;s:2:"12";i:2;s:2:"13";}', '<p>Вы можете часть товаров одного каталога транслировать в другой каталог. Меню Дополнительные каталоги в карточке редактирования каталога, закладка Основное.</p>', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs10.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'santehnika', '','0');
INSERT INTO phpshop_categories VALUES ('16', 'Водоснабжение', '3', '4', '1', '3', '0', 'a:3:{i:0;s:2:"11";i:1;s:2:"12";i:2;s:2:"13";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs11.jpg', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'vodosnabzhenie', '','0');
INSERT INTO phpshop_categories VALUES ('17', 'Смартфоны', '1', '5', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('18', 'Ноутбуки', '2', '5', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('19', 'Умные часы', '3', '5', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('20', 'Чай, кофе, какао', '1', '6', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('21', 'Мясо и птица', '2', '6', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('22', 'Соки, воды и напитки', '3', '6', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('23', 'Шины и диски', '1', '7', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('24', 'Масла и автохимия', '2', '7', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('25', 'Уход за автомобилем', '1', '7', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('26', 'Одежда', '1', '8', '1', '4', '0', 'a:4:{i:0;s:1:"5";i:1;s:1:"3";i:2;s:1:"4";i:3;s:1:"2";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs4.jpg', '', '', '4',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'odezhda-zenshinam', '','0');
INSERT INTO phpshop_categories VALUES ('27', 'Одежда', '1', '9', '1', '3', '0', 'a:4:{i:0;s:1:"5";i:1;s:1:"3";i:2;s:1:"4";i:3;s:1:"2";}', '<p>В меню Товары - Каталоги - Основное вы можете выбрать сетку товаров, сколько колонок будет у вашего каталога.&nbsp;&nbsp;</p>', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs1.jpg', '', '', '4',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'odezhda-muzchinam', '','0');
INSERT INTO phpshop_categories VALUES ('28', 'Обувь, аксессуары', '2', '8', '1', '4', '0', 'a:4:{i:0;s:1:"5";i:1;s:1:"3";i:2;s:1:"4";i:3;s:1:"2";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs3.jpg', '', '', '6',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'obuv-aksessuary', '','0');
INSERT INTO phpshop_categories VALUES ('29', 'Нижнее белье', '3', '8', '1', '4', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs2.jpg', '', '', '4',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'nizhnee-bele', '','0');
INSERT INTO phpshop_categories VALUES ('30', 'Обувь', '2', '9', '1', '2', '0', 'a:4:{i:0;s:1:"5";i:1;s:1:"3";i:2;s:1:"4";i:3;s:1:"2";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs8.jpg', '', '', '4',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'obuv', '','0');
INSERT INTO phpshop_categories VALUES ('31', 'Аксессуары', '3', '9', '1', '2', '0', 'a:4:{i:0;s:1:"5";i:1;s:1:"3";i:2;s:1:"4";i:3;s:1:"2";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/phpshop6_catalogs2.jpg', '', '', '6',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, 'aksessuary', '','0');
INSERT INTO phpshop_categories VALUES ('32', 'Школьная форма', '1', '10', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '1', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('33', 'Рюкзаки', '2', '10', '1', '3', '0', 'a:1:{i:0;s:1:"4";}', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
INSERT INTO phpshop_categories VALUES ('36', 'Бестселлеры', '8', '0', '1', '3', '0', 'N;', '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '1', '1', '1', '', '', '', '', '0',  NULL,  NULL, '0',  NULL,  NULL,  NULL,  NULL,  NULL, '', '','0');
DROP TABLE IF EXISTS phpshop_citylist_city;
CREATE TABLE phpshop_citylist_city (
  `city_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(11) unsigned DEFAULT '0',
  `region_id` int(10) unsigned DEFAULT '0',
  `name` varchar(128) DEFAULT '',
  PRIMARY KEY (`city_id`),
  KEY `country_id` (`country_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_citylist_country;
CREATE TABLE phpshop_citylist_country (
  `country_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT '0',
  `name` varchar(128) DEFAULT '',
  PRIMARY KEY (`country_id`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_citylist_region;
CREATE TABLE phpshop_citylist_region (
  `region_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10) unsigned DEFAULT '0',
  `city_id` int(10) unsigned DEFAULT '0',
  `name` varchar(64) DEFAULT '',
  PRIMARY KEY (`region_id`),
  KEY `country_id` (`country_id`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_comment;
CREATE TABLE phpshop_comment (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `datas` varchar(32) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `content` text,
  `user_id` int(11) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '0',
  `rate` smallint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_delivery;
CREATE TABLE phpshop_delivery (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(255) DEFAULT '',
  `price` float DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '1',
  `flag` enum('0','1') DEFAULT '0',
  `price_null` float DEFAULT '0',
  `price_null_enabled` enum('0','1') DEFAULT '0',
  `PID` int(11) DEFAULT '0',
  `taxa` int(11) DEFAULT '0',
  `is_folder` enum('0','1') DEFAULT '0',
  `city_select` enum('0','1','2') DEFAULT '0',
  `data_fields` blob,
  `num` smallint(3) DEFAULT '0',
  `icon` varchar(255) DEFAULT '',
  `payment` varchar(255) DEFAULT '',
  `ofd_nds` varchar(64) DEFAULT '',
  `sum_max` float DEFAULT '0',
  `sum_min` float DEFAULT '0',
  `weight_max` int(11) DEFAULT '0',
  `weight_min` int(11) DEFAULT '0',
  `servers` varchar(64) DEFAULT '',
  `is_mod` enum('1','2') DEFAULT '1',
  `warehouse` int(11) DEFAULT '0',
  `yandex_mail_instock` text,
  `yandex_mail_outstock` text,
  `yandex_enabled` enum('1','2') DEFAULT '1',
  `yandex_day` int(11) DEFAULT '2',
  `yandex_type` enum('1','2','3') DEFAULT '1',
  `yandex_payment` enum('1','2','3') DEFAULT '1',
  `yandex_outlet` varchar(255) DEFAULT '',
  `yandex_check` enum('1','2') DEFAULT '1',
  `yandex_day_min` int(11) DEFAULT '1',
  `yandex_order_before` int(11) DEFAULT '16',
  `comment` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_delivery VALUES ('1', 'Санкт-Петербург', '0', '1', '1', '0', '0', '0', '0', '1', '0', '', '0', '/UserFiles/Image/Payments/city.png', '', '', '0', '0', '0', '0', '', '1', '0',  NULL,  NULL, '1', '2', '1', '1', '', '1', '1', '16','');
INSERT INTO phpshop_delivery VALUES ('3', 'Доставка курьером в пределах МКАД', '180', '1', '1', '2000', '1', '1', '1', '0', '0', 'a:2:{s:7:"enabled";a:12:{s:7:"country";a:1:{s:4:"name";s:6:"Страна";}s:5:"state";a:1:{s:4:"name";s:11:"Регион/штат";}s:4:"city";a:1:{s:4:"name";s:5:"Город";}s:5:"index";a:1:{s:4:"name";s:6:"Индекс";}s:3:"fio";a:1:{s:4:"name";s:3:"ФИО";}s:3:"tel";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:7:"Телефон";s:3:"req";s:1:"1";}s:6:"street";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:5:"Улица";}s:5:"house";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:3:"Дом";}s:5:"porch";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:7:"Подъезд";}s:10:"door_phone";a:1:{s:4:"name";s:12:"Код домофона";}s:4:"flat";a:1:{s:4:"name";s:8:"Квартира";}s:9:"delivtime";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:16:"Когда доставить?";s:3:"req";s:1:"1";}}s:3:"num";a:12:{s:7:"country";s:1:"1";s:5:"state";s:1:"2";s:4:"city";s:1:"3";s:5:"index";s:1:"4";s:3:"fio";s:1:"1";s:3:"tel";s:1:"2";s:6:"street";s:1:"3";s:5:"house";s:1:"4";s:5:"porch";s:1:"5";s:10:"door_phone";s:1:"6";s:4:"flat";s:1:"7";s:9:"delivtime";s:1:"8";}}', '0', '/UserFiles/Image/Payments/courier.png', 'null', '18', '0', '0', '0', '0', '', '2', '1',  NULL,  NULL, '1', '2', '1', '1', '', '1', '1', '16','');
INSERT INTO phpshop_delivery VALUES ('4', 'Доставка курьером за пределами МКАД', '300', '0', '0', '0', '0', '1', '0', '0', '0', 'a:2:{s:7:"enabled";a:12:{s:7:"country";a:1:{s:4:"name";s:6:"Страна";}s:5:"state";a:1:{s:4:"name";s:11:"Регион/штат";}s:4:"city";a:1:{s:4:"name";s:5:"Город";}s:5:"index";a:1:{s:4:"name";s:6:"Индекс";}s:3:"fio";a:1:{s:4:"name";s:3:"ФИО";}s:3:"tel";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:7:"Телефон";s:3:"req";s:1:"1";}s:6:"street";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:5:"Улица";s:3:"req";s:1:"1";}s:5:"house";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:3:"Дом";s:3:"req";s:1:"1";}s:5:"porch";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:7:"Подъезд";s:3:"req";s:1:"1";}s:10:"door_phone";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:12:"Код домофона";}s:4:"flat";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:8:"Квартира";s:3:"req";s:1:"1";}s:9:"delivtime";a:1:{s:4:"name";s:14:"Время доставки";}}s:3:"num";a:12:{s:7:"country";s:1:"1";s:5:"state";s:1:"2";s:4:"city";s:1:"3";s:5:"index";s:1:"4";s:3:"fio";s:1:"1";s:3:"tel";s:1:"2";s:6:"street";s:1:"3";s:5:"house";s:1:"4";s:5:"porch";s:1:"5";s:10:"door_phone";s:1:"6";s:4:"flat";s:1:"7";s:9:"delivtime";s:2:"12";}}', '0', '/UserFiles/Image/Payments/courier-za-mkad.png', 'null', '18', '0', '0', '0', '0', '', '1', '1', '', '', '1', '2', '1', '1', '', '1', '1', '16','');
INSERT INTO phpshop_delivery VALUES ('7', 'Новосибирск', '0', '1', '0', '0', '0', '0', '0', '1', '0', '', '1', '/UserFiles/Image/Payments/russia.png', '', '', '0', '0', '0', '0', '', '1', '0',  NULL,  NULL, '1', '2', '1', '1', '', '1', '1', '16','');
INSERT INTO phpshop_delivery VALUES ('8', 'EMS', '500', '1', '0', '5000', '1', '7', '50', '0', '1', 'a:2:{s:7:"enabled";a:12:{s:7:"country";a:1:{s:4:"name";s:6:"Страна";}s:5:"state";a:1:{s:4:"name";s:11:"Регион/штат";}s:4:"city";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:5:"Город";s:3:"req";s:1:"1";}s:5:"index";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:6:"Индекс";s:3:"req";s:1:"1";}s:3:"fio";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:14:"ФИО получателя";s:3:"req";s:1:"1";}s:3:"tel";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:18:"Телефон получателя";s:3:"req";s:1:"1";}s:6:"street";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:5:"Улица";s:3:"req";s:1:"1";}s:5:"house";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:3:"Дом";s:3:"req";s:1:"1";}s:5:"porch";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:7:"Подъезд";}s:10:"door_phone";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:12:"Код домофона";}s:4:"flat";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:8:"Квартира";s:3:"req";s:1:"1";}s:9:"delivtime";a:1:{s:4:"name";s:14:"Время доставки";}}s:3:"num";a:12:{s:7:"country";s:1:"1";s:5:"state";s:1:"2";s:4:"city";s:1:"1";s:5:"index";s:1:"2";s:3:"fio";s:1:"3";s:3:"tel";s:1:"4";s:6:"street";s:1:"5";s:5:"house";s:1:"6";s:5:"porch";s:1:"7";s:10:"door_phone";s:1:"8";s:4:"flat";s:1:"9";s:9:"delivtime";s:2:"12";}}', '1', '/UserFiles/Image/Payments/ems.png', '', '18', '0', '0', '0', '0', '', '1', '2',  NULL,  NULL, '1', '2', '1', '1', '', '1', '1', '16','');
INSERT INTO phpshop_delivery VALUES ('9', 'Почта России', '900', '1', '0', '5000', '1', '7', '60', '0', '1', 'a:2:{s:7:"enabled";a:12:{s:7:"country";a:1:{s:4:"name";s:6:"Страна";}s:5:"state";a:1:{s:4:"name";s:11:"Регион/штат";}s:4:"city";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:5:"Город";s:3:"req";s:1:"1";}s:5:"index";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:6:"Индекс";s:3:"req";s:1:"1";}s:3:"fio";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:14:"ФИО получателя";s:3:"req";s:1:"1";}s:3:"tel";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:18:"Телефон получателя";s:3:"req";s:1:"1";}s:6:"street";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:5:"Улица";s:3:"req";s:1:"1";}s:5:"house";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:3:"Дом";s:3:"req";s:1:"1";}s:5:"porch";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:7:"Подъезд";}s:10:"door_phone";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:12:"Код домофона";}s:4:"flat";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:8:"Квартира";s:3:"req";s:1:"1";}s:9:"delivtime";a:1:{s:4:"name";s:14:"Время доставки";}}s:3:"num";a:12:{s:7:"country";s:0:"";s:5:"state";s:1:"2";s:4:"city";s:1:"1";s:5:"index";s:1:"2";s:3:"fio";s:1:"3";s:3:"tel";s:1:"4";s:6:"street";s:1:"5";s:5:"house";s:1:"6";s:5:"porch";s:1:"7";s:10:"door_phone";s:1:"8";s:4:"flat";s:1:"9";s:9:"delivtime";s:2:"12";}}', '2', '/UserFiles/Image/Payments/pochta-rf.png', 'null', '18', '0', '0', '0', '0', '', '1', '2',  NULL,  NULL, '1', '2', '1', '1', '', '1', '1', '16','');
INSERT INTO phpshop_delivery VALUES ('12', 'За пределы России', '0', '1', '0', '0', '', '0', '0', '1', '0', '', '3', '/UserFiles/Image/Payments/world.png', '', '', '0', '0', '0', '0', '', '1', '0',  NULL,  NULL, '1', '2', '1', '1', '', '1', '1', '16','');
INSERT INTO phpshop_delivery VALUES ('13', 'DHL', '0', '1', '0', '0', '0', '12', '0', '0', '2', 'a:2:{s:7:"enabled";a:12:{s:7:"country";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:6:"Страна";s:3:"req";s:1:"1";}s:5:"state";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:6:"Регион";s:3:"req";s:1:"1";}s:4:"city";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:5:"Город";s:3:"req";s:1:"1";}s:5:"index";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:6:"Индекс";s:3:"req";s:1:"1";}s:3:"fio";a:2:{s:4:"name";s:14:"ФИО получателя";s:3:"req";s:1:"1";}s:3:"tel";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:18:"Телефон получателя";s:3:"req";s:1:"1";}s:6:"street";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:5:"Улица";s:3:"req";s:1:"1";}s:5:"house";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:3:"Дом";s:3:"req";s:1:"1";}s:5:"porch";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:7:"Подъезд";s:3:"req";s:1:"1";}s:10:"door_phone";a:2:{s:7:"enabled";s:1:"1";s:4:"name";s:12:"Код домофона";}s:4:"flat";a:3:{s:7:"enabled";s:1:"1";s:4:"name";s:8:"Квартира";s:3:"req";s:1:"1";}s:9:"delivtime";a:1:{s:4:"name";s:14:"Время доставки";}}s:3:"num";a:12:{s:7:"country";s:1:"1";s:5:"state";s:1:"2";s:4:"city";s:1:"3";s:5:"index";s:1:"4";s:3:"fio";s:1:"5";s:3:"tel";s:1:"6";s:6:"street";s:1:"7";s:5:"house";s:1:"8";s:5:"porch";s:1:"9";s:10:"door_phone";s:2:"10";s:4:"flat";s:2:"11";s:9:"delivtime";s:2:"12";}}', '0', '/UserFiles/Image/Payments/dhl.png', 'null', '18', '0', '0', '0', '0', '', '1', '0',  NULL,  NULL, '1', '2', '1', '1', '', '1', '1', '16','');
DROP TABLE IF EXISTS phpshop_discount;
CREATE TABLE phpshop_discount (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `sum` int(255) DEFAULT '0',
  `discount` float DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '0',
  `action` ENUM('1', '2') DEFAULT '1',
  `block_old_price` enum('0','1') DEFAULT '0',
  `block_categories` text DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS phpshop_foto;
CREATE TABLE phpshop_foto (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `num` tinyint(11) DEFAULT '0',
  `info` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_foto VALUES ('1', '1', '/UserFiles/Image/Trial/img1_69884.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('2', '1', '/UserFiles/Image/Trial/img1_76986.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('3', '1', '/UserFiles/Image/Trial/img1_67897.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('4', '1', '/UserFiles/Image/Trial/img1_78656.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('5', '2', '/UserFiles/Image/Trial/img2_44763.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('6', '2', '/UserFiles/Image/Trial/img2_27973.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('7', '2', '/UserFiles/Image/Trial/img2_95509.jpg', '5', '');
INSERT INTO phpshop_foto VALUES ('8', '2', '/UserFiles/Image/Trial/img2_64554.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('9', '2', '/UserFiles/Image/Trial/img2_19797.jpg', '6', '');
INSERT INTO phpshop_foto VALUES ('10', '2', '/UserFiles/Image/Trial/img2_96038.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('11', '3', '/UserFiles/Image/Trial/img3_37243.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('12', '3', '/UserFiles/Image/Trial/img3_29892.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('13', '3', '/UserFiles/Image/Trial/img3_83164.jpg', '5', '');
INSERT INTO phpshop_foto VALUES ('14', '3', '/UserFiles/Image/Trial/img3_24603.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('15', '3', '/UserFiles/Image/Trial/img3_12202.jpg', '6', '');
INSERT INTO phpshop_foto VALUES ('16', '3', '/UserFiles/Image/Trial/img3_56614.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('17', '3', '/UserFiles/Image/Trial/img3_93384.jpg', '7', '');
INSERT INTO phpshop_foto VALUES ('18', '4', '/UserFiles/Image/Trial/img4_31272.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('19', '4', '/UserFiles/Image/Trial/img4_24078.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('20', '4', '/UserFiles/Image/Trial/img4_88120.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('21', '5', '/UserFiles/Image/Trial/img5_43616.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('22', '5', '/UserFiles/Image/Trial/img5_23962.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('23', '5', '/UserFiles/Image/Trial/img5_32982.jpg', '5', '');
INSERT INTO phpshop_foto VALUES ('24', '5', '/UserFiles/Image/Trial/img5_47325.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('25', '5', '/UserFiles/Image/Trial/img5_68432.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('26', '6', '/UserFiles/Image/Trial/img6_51944.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('27', '6', '/UserFiles/Image/Trial/img6_42150.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('28', '7', '/UserFiles/Image/Trial/img7_34107.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('29', '7', '/UserFiles/Image/Trial/img7_78584.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('30', '7', '/UserFiles/Image/Trial/img7_84000.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('35', '9', '/UserFiles/Image/Trial/img9_63883.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('32', '8', '/UserFiles/Image/Trial/img8_54208.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('33', '8', '/UserFiles/Image/Trial/img8_41181.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('34', '8', '/UserFiles/Image/Trial/img8_49539.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('36', '9', '/UserFiles/Image/Trial/img9_97932.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('37', '9', '/UserFiles/Image/Trial/img9_38664.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('38', '9', '/UserFiles/Image/Trial/img9_22416.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('39', '10', '/UserFiles/Image/Trial/img10_93804.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('40', '10', '/UserFiles/Image/Trial/img10_75775.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('41', '10', '/UserFiles/Image/Trial/img10_88702.jpg', '5', '');
INSERT INTO phpshop_foto VALUES ('42', '10', '/UserFiles/Image/Trial/img10_90450.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('43', '10', '/UserFiles/Image/Trial/img10_21323.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('44', '11', '/UserFiles/Image/Trial/img11_25850.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('45', '11', '/UserFiles/Image/Trial/img11_84557.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('46', '11', '/UserFiles/Image/Trial/img11_78833.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('47', '12', '/UserFiles/Image/Trial/img12_79562.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('48', '12', '/UserFiles/Image/Trial/img12_60499.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('49', '12', '/UserFiles/Image/Trial/img12_92072.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('50', '12', '/UserFiles/Image/Trial/img12_26952.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('51', '13', '/UserFiles/Image/Trial/img13_34252.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('52', '13', '/UserFiles/Image/Trial/img13_57737.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('53', '13', '/UserFiles/Image/Trial/img13_67547.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('54', '13', '/UserFiles/Image/Trial/img13_59800.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('55', '13', '/UserFiles/Image/Trial/img13_86638.jpg', '5', '');
INSERT INTO phpshop_foto VALUES ('56', '14', '/UserFiles/Image/Trial/img14_31318.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('57', '14', '/UserFiles/Image/Trial/img14_84532.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('58', '14', '/UserFiles/Image/Trial/img14_77798.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('59', '14', '/UserFiles/Image/Trial/img14_67916.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('60', '15', '/UserFiles/Image/Trial/img15_92639.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('61', '15', '/UserFiles/Image/Trial/img15_41848.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('62', '15', '/UserFiles/Image/Trial/img15_21930.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('63', '16', '/UserFiles/Image/Trial/img16_92849.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('64', '16', '/UserFiles/Image/Trial/img16_53768.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('65', '16', '/UserFiles/Image/Trial/img16_56207.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('66', '17', '/UserFiles/Image/Trial/img17_22660.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('67', '17', '/UserFiles/Image/Trial/img17_95275.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('68', '17', '/UserFiles/Image/Trial/img17_48063.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('69', '18', '/UserFiles/Image/Trial/img18_68450.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('70', '18', '/UserFiles/Image/Trial/img18_49453.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('71', '18', '/UserFiles/Image/Trial/img18_70369.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('72', '19', '/UserFiles/Image/Trial/img19_68698.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('73', '19', '/UserFiles/Image/Trial/img19_76156.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('74', '19', '/UserFiles/Image/Trial/img19_96588.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('75', '20', '/UserFiles/Image/Trial/img20_27253.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('76', '20', '/UserFiles/Image/Trial/img20_54474.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('77', '20', '/UserFiles/Image/Trial/img20_30838.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('78', '21', '/UserFiles/Image/Trial/img21_52213.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('79', '21', '/UserFiles/Image/Trial/img21_18874.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('80', '21', '/UserFiles/Image/Trial/img21_35310.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('81', '21', '/UserFiles/Image/Trial/img21_89669.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('82', '22', '/UserFiles/Image/Trial/img22_64146.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('83', '22', '/UserFiles/Image/Trial/img22_59635.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('84', '22', '/UserFiles/Image/Trial/img22_11677.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('87', '23', '/UserFiles/Image/Trial/img23_55300.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('86', '22', '/UserFiles/Image/Trial/img22_79686.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('88', '23', '/UserFiles/Image/Trial/img23_72771.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('89', '23', '/UserFiles/Image/Trial/img23_41025.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('90', '23', '/UserFiles/Image/Trial/img23_77945.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('91', '24', '/UserFiles/Image/Trial/img24_83524.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('92', '24', '/UserFiles/Image/Trial/img24_14357.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('93', '24', '/UserFiles/Image/Trial/img24_36368.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('94', '25', '/UserFiles/Image/Trial/img25_60693.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('95', '25', '/UserFiles/Image/Trial/img25_58146.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('96', '25', '/UserFiles/Image/Trial/img25_93765.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('97', '25', '/UserFiles/Image/Trial/img25_67891.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('98', '25', '/UserFiles/Image/Trial/img25_79335.jpg', '5', '');
INSERT INTO phpshop_foto VALUES ('99', '25', '/UserFiles/Image/Trial/img25_75374.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('100', '26', '/UserFiles/Image/Trial/img26_58467.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('101', '26', '/UserFiles/Image/Trial/img26_40876.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('102', '26', '/UserFiles/Image/Trial/img26_47790.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('103', '27', '/UserFiles/Image/Trial/img27_71254.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('104', '27', '/UserFiles/Image/Trial/img27_40980.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('105', '27', '/UserFiles/Image/Trial/img27_52450.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('106', '27', '/UserFiles/Image/Trial/img27_33104.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('107', '28', '/UserFiles/Image/Trial/img28_65696.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('108', '28', '/UserFiles/Image/Trial/img28_10558.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('109', '28', '/UserFiles/Image/Trial/img28_82780.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('110', '28', '/UserFiles/Image/Trial/img28_93753.jpg', '5', '');
INSERT INTO phpshop_foto VALUES ('111', '28', '/UserFiles/Image/Trial/img28_82509.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('112', '29', '/UserFiles/Image/Trial/img29_45239.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('113', '29', '/UserFiles/Image/Trial/img29_65387.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('114', '29', '/UserFiles/Image/Trial/img29_72070.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('115', '30', '/UserFiles/Image/Trial/img30_85931.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('116', '30', '/UserFiles/Image/Trial/img30_89787.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('117', '30', '/UserFiles/Image/Trial/img30_12280.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('118', '31', '/UserFiles/Image/Trial/img31_33680.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('119', '31', '/UserFiles/Image/Trial/img31_66601.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('120', '31', '/UserFiles/Image/Trial/img31_95215.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('121', '31', '/UserFiles/Image/Trial/img31_21337.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('122', '32', '/UserFiles/Image/Trial/img32_89451.jpg', '3', '');
INSERT INTO phpshop_foto VALUES ('123', '32', '/UserFiles/Image/Trial/img32_33694.jpg', '2', '');
INSERT INTO phpshop_foto VALUES ('124', '32', '/UserFiles/Image/Trial/img32_33653.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('125', '32', '/UserFiles/Image/Trial/img32_39066.jpg', '4', '');
INSERT INTO phpshop_foto VALUES ('126', '33', '/UserFiles/Image/Trial/img33_95820.png', '0', '');
INSERT INTO phpshop_foto VALUES ('127', '33', '/UserFiles/Image/Trial/img33_66960.png', '0', '');
INSERT INTO phpshop_foto VALUES ('128', '33', '/UserFiles/Image/Trial/img33_16553.png', '0', '');
INSERT INTO phpshop_foto VALUES ('129', '34', '/UserFiles/Image/Trial/img34_49655.png', '0', '');
INSERT INTO phpshop_foto VALUES ('130', '34', '/UserFiles/Image/Trial/img34_44685.png', '0', '');
INSERT INTO phpshop_foto VALUES ('131', '34', '/UserFiles/Image/Trial/img34_27336.png', '0', '');
INSERT INTO phpshop_foto VALUES ('132', '35', '/UserFiles/Image/Trial/img35_18528.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('133', '35', '/UserFiles/Image/Trial/img35_18631.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('134', '35', '/UserFiles/Image/Trial/img35_62205.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('135', '36', '/UserFiles/Image/Trial/img36_33799.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('136', '36', '/UserFiles/Image/Trial/img36_99871.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('137', '36', '/UserFiles/Image/Trial/img36_43273.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('138', '36', '/UserFiles/Image/Trial/img36_45851.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('139', '36', '/UserFiles/Image/Trial/img36_35651.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('140', '37', '/UserFiles/Image/Trial/img37_11711.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('141', '37', '/UserFiles/Image/Trial/img37_92963.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('142', '37', '/UserFiles/Image/Trial/img37_82187.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('143', '37', '/UserFiles/Image/Trial/img37_15384.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('144', '37', '/UserFiles/Image/Trial/img37_17123.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('145', '38', '/UserFiles/Image/Trial/img38_13891.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('146', '38', '/UserFiles/Image/Trial/img38_85223.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('147', '38', '/UserFiles/Image/Trial/img38_66848.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('148', '38', '/UserFiles/Image/Trial/img38_24229.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('149', '38', '/UserFiles/Image/Trial/img38_22403.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('150', '38', '/UserFiles/Image/Trial/img38_31623.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('151', '39', '/UserFiles/Image/Trial/img39_63648.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('152', '40', '/UserFiles/Image/Trial/img40_16081.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('153', '41', '/UserFiles/Image/Trial/img41_23080.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('154', '42', '/UserFiles/Image/Trial/img42_77115.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('155', '43', '/UserFiles/Image/Trial/img43_24630.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('157', '44', '/UserFiles/Image/Trial/img44_23523.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('158', '45', '/UserFiles/Image/Trial/img45_37544.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('159', '46', '/UserFiles/Image/Trial/img46_54151.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('160', '46', '/UserFiles/Image/Trial/img46_50033.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('161', '47', '/UserFiles/Image/Trial/img47_24068.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('162', '48', '/UserFiles/Image/Trial/img48_22317.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('163', '49', '/UserFiles/Image/Trial/img49_36956.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('164', '50', '/UserFiles/Image/Trial/img50_30692.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('165', '51', '/UserFiles/Image/Trial/img51_26484.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('166', '52', '/UserFiles/Image/Trial/img52_37358.jpg', '0', '');
INSERT INTO phpshop_foto VALUES ('167', '11', '/UserFiles/Image/Trial/img11_33069.jpg', '3', '');
DROP TABLE IF EXISTS phpshop_gbook;
CREATE TABLE phpshop_gbook (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `datas` int(11) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `mail` varchar(32) DEFAULT NULL,
  `tema` text,
  `otsiv` text,
  `otvet` text,
  `flag` enum('0','1') DEFAULT '0',
  `servers` varchar(64) default '',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_gbook VALUES ('1', '1409691600', 'Елена', 'test@test.ru', 'Приятно было работать с таким магазином! ', 'Приятно было работать с таким магазином! И акции со скидкой есть и сама продукция разнообразная) Сначала было немного трудно правда разобраться с формой заказа, но консультанты помогли. Сначала хотела забирать самовывозом, но мне сказали, что доставка будет бесплатно, так как сумма заказа больше 2000 рублей) это приятно порадовало.', '<p>Спасибо, Елена! Рады стараться!</p>', '1','');
INSERT INTO phpshop_gbook VALUES ('3', '1409691600', 'Ольга', 'mail@test.ru', 'Хороший магазин!', 'Хотелось бы тоже отписаться поповоду работы магазина. Ребята во-первых ответственные и порядочные.', '<p>Здравствуйте, Ольга.</p>\r\n<p>Благодарим Вас за положительную оценку!</p>', '1','');
INSERT INTO phpshop_gbook VALUES ('4', '1574805600', 'Олег', 'test@test.ru', 'Знаю уже 5 лет', 'Здесь удобный поиск товаров по характеристикам, сравнение товаров.! Это был ноутбук Sony Vaio, в то время даже понятия не было такого, только комменты оставляю, которые в последнее время стали исчезать непонятно куда))).', '', '1','');
INSERT INTO phpshop_gbook VALUES ('5', '1574860833', 'Вадим', 'test@test.ru', 'Спасибо', 'По рекомендации друга, заказал тут смарт часы себе. Давно хотел. Но цены на них кусаются.  С менеджером доставили быстро. Часы в рабочем состоянии. ', '', '1','');
DROP TABLE IF EXISTS phpshop_jurnal;
CREATE TABLE phpshop_jurnal (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(64) NOT NULL DEFAULT '',
  `datas` varchar(32) NOT NULL DEFAULT '',
  `flag` enum('0','1') NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS phpshop_links;
CREATE TABLE phpshop_links (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `image` text,
  `opis` text,
  `link` text,
  `num` int(11) DEFAULT NULL,
  `enabled` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_links VALUES ('1', 'PHPShop Software', '', 'Создание интернет-магазина, скрипт интернет-магазина PHPShop.', 'https://www.phpshop.ru', '5', '1');
INSERT INTO phpshop_links VALUES ('2', 'PHPShop CMS Free', '', 'Бесплатная сиcтема управления сайтом PHPShop CMS Free.', 'https://www.phpshopcms.ru', '3', '1');
INSERT INTO phpshop_links VALUES ('3', 'Аренда интернет-магазина', '', 'Shopbuilder - это новый SaaS сервис аренды интернет-магазина, позволяющий пользователям за считанные минуты создать полноценный сайт интернет-магазина за 599 руб.', 'https://www.shopbuilder.ru', '1', '1');
DROP TABLE IF EXISTS phpshop_menu;
CREATE TABLE phpshop_menu (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `content` text,
  `flag` enum('0','1') DEFAULT '1',
  `num` int(11) DEFAULT '0',
  `dir` varchar(64) DEFAULT NULL,
  `element` enum('0','1') DEFAULT '0',
  `servers` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `flag` (`flag`),
  KEY `element` (`element`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_menu VALUES ('1', 'Обновление', 'Постоянное обновление функционала - cкрипт PHPShop постоянно развивается, вы получаете все преимущества обновлений, находясь на активной техподдержке.', '0', '4', '/', '1', '');
INSERT INTO phpshop_menu VALUES ('2', 'Создание персонального дизайна', '<p>Вы можете заказать <a href="http://www.phpshop.ru/page/design.html" target="_blank" rel="noopener">создание индивидуального шаблона с нуля</a> на нашем сайте.</p>', '1', '2', '', '1', '');
INSERT INTO phpshop_menu VALUES ('4', 'Текстовый блок', '<p>Это текстовый блок. Меняется в меню Веб-сайт &rarr; Текстовые блоки.&nbsp;</p>', '1', '3', '', '0', '');

DROP TABLE IF EXISTS phpshop_modules;
CREATE TABLE phpshop_modules (
  `path` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `date` int(11) DEFAULT '0',
  `servers` varchar(64) DEFAULT '',
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules VALUES ('returncall', 'Return Call', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('visualcart', 'Visual Cart', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('productday', 'Товар дня', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('sticker', 'Sticker', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('productlastview', 'Product Last View', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('productoption', 'Product Option', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('hit', 'Хиты', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('oneclick', 'One Click', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('seourlpro', 'SeoUrl Pro', '1620997787', '');
INSERT INTO phpshop_modules VALUES ('yandexkassa', 'ЮKassa', '1620997787', '');

DROP TABLE IF EXISTS phpshop_modules_hit_system;
CREATE TABLE phpshop_modules_hit_system (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hit_main` int(11) NOT NULL DEFAULT '20',
  `hit_page` int(11) NOT NULL DEFAULT '3',
  `version` varchar(64) DEFAULT '1.0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules_hit_system VALUES ('1', '20', '3', '1.0');
DROP TABLE IF EXISTS phpshop_modules_key;
CREATE TABLE phpshop_modules_key (
  `path` varchar(64) NOT NULL DEFAULT '',
  `date` int(11) DEFAULT '0',
  `key` text,
  `verification` varchar(32) DEFAULT '',
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_modules_oneclick_jurnal;
CREATE TABLE phpshop_modules_oneclick_jurnal (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) DEFAULT '0',
  `name` varchar(64) DEFAULT '',
  `tel` varchar(64) DEFAULT '',
  `message` text,
  `product_name` varchar(64) DEFAULT '',
  `product_id` int(11) DEFAULT NULL,
  `product_price` varchar(64) DEFAULT '',
  `product_image` varchar(64) DEFAULT '',
  `ip` varchar(64) DEFAULT '',
  `status` enum('1','2','3','4') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_modules_oneclick_system;
CREATE TABLE phpshop_modules_oneclick_system (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` enum('0','1','2') DEFAULT '1',
  `title` text,
  `title_end` text,
  `serial` varchar(64) DEFAULT '',
  `windows` enum('0','1') DEFAULT '0',
  `display` enum('0','1') DEFAULT '0',
  `write_order` enum('0','1') DEFAULT '0',
  `captcha` enum('0','1') DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `version` varchar(64) DEFAULT '1.6',
  `only_available` enum('0','1') default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules_oneclick_system VALUES ('1', '0', 'Спасибо, Ваш заказ принят!', 'Наши менеджеры свяжутся с Вами для уточнения деталей.', '', '1', '0', '1', '1', '0', '1.6','0');
DROP TABLE IF EXISTS `phpshop_modules_productday_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_productday_system` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) default '0',
  `version` varchar(64) DEFAULT '1.1',
  `status` enum('1','2','3') default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
INSERT INTO `phpshop_modules_productday_system` VALUES (1,'24','1.3','1');


DROP TABLE IF EXISTS phpshop_modules_productlastview_memory;
CREATE TABLE phpshop_modules_productlastview_memory (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memory` varchar(64) NOT NULL DEFAULT '',
  `product` text NOT NULL,
  `date` int(11) NOT NULL DEFAULT '0',
  `user` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `memory` (`memory`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_modules_productlastview_system;
CREATE TABLE phpshop_modules_productlastview_system (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` enum('0','1','2') NOT NULL DEFAULT '1',
  `flag` enum('1','2') NOT NULL DEFAULT '1',
  `title` varchar(64) NOT NULL DEFAULT '',
  `pic_width` tinyint(100) NOT NULL DEFAULT '0',
  `memory` enum('0','1') NOT NULL DEFAULT '1',
  `num` tinyint(11) NOT NULL DEFAULT '0',
  `serial` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules_productlastview_system VALUES ('1', '0', '1', 'Просмотренные товары', '50', '1', '5', '');
DROP TABLE IF EXISTS phpshop_modules_productoption_system;
CREATE TABLE phpshop_modules_productoption_system (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option` blob NOT NULL,
  `version` varchar(64) NOT NULL DEFAULT '1.3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules_productoption_system VALUES ('1', 'a:20:{s:13:"option_1_name";s:16:"Таблица размеров";s:15:"option_1_format";s:6:"editor";s:13:"option_2_name";s:21:"Информация о доставке";s:15:"option_2_format";s:6:"editor";s:13:"option_3_name";s:0:"";s:15:"option_3_format";s:4:"text";s:13:"option_4_name";s:0:"";s:15:"option_4_format";s:4:"text";s:13:"option_5_name";s:0:"";s:15:"option_5_format";s:4:"text";s:13:"option_6_name";s:0:"";s:15:"option_6_format";s:4:"text";s:13:"option_7_name";s:0:"";s:15:"option_7_format";s:4:"text";s:13:"option_8_name";s:0:"";s:15:"option_8_format";s:4:"text";s:13:"option_9_name";s:0:"";s:15:"option_9_format";s:4:"text";s:14:"option_10_name";s:0:"";s:16:"option_11_format";N;}', '1.3');
DROP TABLE IF EXISTS `phpshop_modules_returncall_jurnal`;
CREATE TABLE `phpshop_modules_returncall_jurnal` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `time_start` varchar(64) default '',
  `time_end` varchar(64) default '',
  `name` varchar(64) default '',
  `tel` varchar(64) default '',
  `message` text ,
  `status` int(11) default '1',
  `ip` varchar(64) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS phpshop_modules_returncall_system;
CREATE TABLE phpshop_modules_returncall_system (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` enum('0','1','2') DEFAULT '1',
  `title` varchar(64) DEFAULT '',
  `title_end` text,
  `windows` enum('0','1') DEFAULT '1',
  `captcha_enabled` enum('1','2') DEFAULT '1',
  `status` int(11) default '0',
  `version` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules_returncall_system VALUES ('1', '0', 'Обратный звонок', 'Спасибо! Мы скоро свяжемся с Вами.', '1', '1','1', '1.7');


DROP TABLE IF EXISTS phpshop_modules_seourlpro_system;
CREATE TABLE phpshop_modules_seourlpro_system (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paginator` enum('1','2') DEFAULT '1',
  `seo_brands_enabled` enum('1','2') DEFAULT '1',
  `cat_content_enabled` enum('1','2') DEFAULT '1',
  `seo_news_enabled` enum('1','2') DEFAULT '1',
  `seo_page_enabled` enum('1','2') DEFAULT '1',
  `redirect_enabled` enum('1','2') DEFAULT '1',
  `version` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules_seourlpro_system VALUES ('1', '2', '2', '1', '2', '2', '1', '2.1');
DROP TABLE IF EXISTS phpshop_modules_sticker_forms;
CREATE TABLE phpshop_modules_sticker_forms (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `path` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `mail` varchar(64) NOT NULL DEFAULT '',
  `enabled` enum('0','1') NOT NULL DEFAULT '1',
  `dir` text NOT NULL,
  `skin` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules_sticker_forms VALUES ('1', 'Консультации', 'three', '<p class="icon">&nbsp;</p>\r\n<h6 class="no-margin text-uppercase">Помощь и консультации</h6>\r\n<p>поможем с выбором</p>', '', '1', '', 'hub');
INSERT INTO phpshop_modules_sticker_forms VALUES ('2', 'Гарантия возврата денег', 'two', '<p class="icon">&nbsp;</p>\r\n<h6 class="no-margin text-uppercase">Гарантия возврата денег</h6>\r\n<p>в течении 14 дней с момента покупки</p>', '', '1', '', 'hub');
INSERT INTO phpshop_modules_sticker_forms VALUES ('3', 'Бесплатная доставка', 'one', '<p class="icon">&nbsp;</p>\r\n<h6 class="no-margin text-uppercase">Бесплатная доставка</h6>\r\n<p>при заказе от 5000 руб.</p>', '', '1', '', 'hub');
INSERT INTO phpshop_modules_sticker_forms VALUES ('4', 'Рекламный стикер над шапкой', 'delivery', '<p>Бесплатная доставка при заказе от 3000 рублей. <a>Подробности</a></p>', '', '1', '', '');
INSERT INTO phpshop_modules_sticker_forms VALUES ('5', 'Карта в разделе контактов', 'map', '<p><iframe src="https://yandex.ru/map-widget/v1/?um=constructor:055c7ef4dbce4860769e42eb3b7eefb5e095a818465716830ffd258a408d8bb2&amp;source=constructor" width="100%" height="595" frameborder="0"></iframe></p>', '', '1', '', 'lego');
INSERT INTO phpshop_modules_sticker_forms VALUES ('6', 'Блок с информацией', 'info', '<div class="sticker-info">\r\n<ul>\r\n<li>\r\n<p>Бесплатная доставка</p>\r\nдля заказов от 3000 руб.</li>\r\n<li>\r\n<p>Консультации</p>\r\nпомощь с выбором товара</li>\r\n<li>\r\n<p>30 дней на возврат</p>\r\nвы можете вернуть деньги за товар</li>\r\n</ul>\r\n</div>', '', '1', '', 'lego');
INSERT INTO phpshop_modules_sticker_forms VALUES ('7', 'Таблица размеров в карточке товара', 'size', '<p><a class="size-table" href="#" data-toggle="modal" data-target="#sizeModal">Таблица размеров</a></p>', '', '1', '', '');
INSERT INTO phpshop_modules_sticker_forms VALUES ('8', 'Доставка в карточке товара', 'shipping', '<p><a class="delivery" href="#" data-toggle="modal" data-target="#shipModal">Доставка</a></p>', '', '1', '', '');
INSERT INTO phpshop_modules_sticker_forms VALUES ('9', 'Соцсети для мобильной версии сайта', 'social', '<ul class="social">\r\n<li class="skype"><a href="https://msng.link/sk/login">&nbsp;</a></li>\r\n<li class="whatsapp"><a href="https://msng.link/wa/7926000000">&nbsp;</a></li>\r\n<li class="viber"><a href="https://msng.link/vi/7926000000">&nbsp;</a></li>\r\n</ul>', '', '1', '', '');
INSERT INTO phpshop_modules_sticker_forms VALUES ('10', 'Картинка оплат в подвале', 'pay', '<p><img src="/UserFiles/Image/Trial/pay.png" width="250" height="25" alt="Payment methods" /></p>', '', '1', '', '');
INSERT INTO phpshop_modules_sticker_forms VALUES ('11', 'Иконки социальных сетей в подвале сайта', 'socfooter', '<ul class="social-menu list-inline">\r\n<li class="list-inline-item"><a class="social-button header-top-link" title="Facebook" href="#"><em class="fa fa-facebook" aria-hidden="true">.</em></a></li>\r\n<li class="list-inline-item"><a class="social-button  header-top-link" title="ВКонтакте" href="#"><em class="fa fa-vk" aria-hidden="true">.</em></a></li>\r\n<li class="list-inline-item"><a class="social-button  header-top-link" title="Одноклассники" href="#"><em class="fa fa-odnoklassniki" aria-hidden="true">.</em></a></li>\r\n</ul>', '', '1', '', '');
INSERT INTO phpshop_modules_sticker_forms VALUES ('13', 'Горизонтальный баннер', 'banner', '<img src="/UserFiles/Image/Trial/banner_hor.jpg" alt="" width="1830" height="134">', '', '1', '', '');
INSERT INTO phpshop_modules_sticker_forms VALUES ('14', 'Слайдер для мобильных (грузите картинку маленького размера)', 'mobile_slider', '<p><img src="/UserFiles/Image/Trial/banner_for_mobile.jpg" width="410" height="200" alt="Mobile banner"/></p>', '', '1', '', '');
DROP TABLE IF EXISTS phpshop_modules_sticker_system;
CREATE TABLE phpshop_modules_sticker_system (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial` varchar(64) NOT NULL DEFAULT '',
  `version` varchar(64) DEFAULT '1.0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_modules_sticker_system VALUES ('1', '', '1.2');

DROP TABLE IF EXISTS `phpshop_modules_visualcart_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_visualcart_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1','2')default '1',
  `flag` enum('1','2') default '1',
  `title` varchar(64) default '',
  `pic_width` tinyint(100) default '0',
  `memory` enum('0','1') default '1',
  `nowbuy` enum('0','1') default '1',
  `referal` enum('0','1') default '0',
  `version` varchar(64) DEFAULT '2.2',
  `sendmail` INT(11) DEFAULT '10',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_visualcart_system` VALUES (1, '0', '1', 'Корзина', 50,'1','1','0','2.4','10');

DROP TABLE IF EXISTS `phpshop_modules_visualcart_memory`;
CREATE TABLE `phpshop_modules_visualcart_memory` (
  `id` int(11) NOT NULL auto_increment,
  `memory` varchar(64) default '',
  `cart` text ,
  `date` int(11) default '0',
  `user` int(11) default '0',
  `ip` varchar(64) default '',
  `referal` text ,
  `tel` VARCHAR(64), 
  `mail` VARCHAR(64),
  `name` VARCHAR(64),
  `sum` FLOAT,
  `sendmail` ENUM('0','1') DEFAULT '0',
  `server` INT(11),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS `phpshop_modules_visualcart_log`;
CREATE TABLE `phpshop_modules_visualcart_log` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `user` varchar(255) default '',
  `ip` varchar(64) default '',
  `status` enum('1','2') DEFAULT '1',
  `content` varchar(64) default '',
  `num` TINYINT(11) default '0',
  `product_id` INT(11) default '0',
  `price` float(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_news;
CREATE TABLE phpshop_news (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datas` varchar(32) DEFAULT '',
  `zag` varchar(255) DEFAULT '',
  `kratko` text,
  `podrob` text,
  `datau` int(11) DEFAULT '0',
  `odnotip` text,
  `servers` varchar(64) DEFAULT '',
  `icon` varchar(255) DEFAULT '',
  `news_seo_name` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_newsletter;
CREATE TABLE phpshop_newsletter (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `content` text,
  `template` int(11) DEFAULT '0',
  `date` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_notice;
CREATE TABLE phpshop_notice (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `product_id` int(11) DEFAULT '0',
  `datas_start` varchar(64) DEFAULT '',
  `datas` varchar(64) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_opros;
CREATE TABLE phpshop_opros (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) unsigned DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `total` int(11) DEFAULT '0',
  `num` tinyint(32) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_opros_categories;
CREATE TABLE phpshop_opros_categories (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `dir` varchar(32) DEFAULT '',
  `flag` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_order_status;
CREATE TABLE phpshop_order_status (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `color` varchar(64) DEFAULT '',
  `sklad_action` enum('0','1') DEFAULT '0',
  `cumulative_action` enum('0','1') DEFAULT '0',
  `mail_action` enum('0','1') DEFAULT '1',
  `mail_message` text,
  `sms_action` enum('0','1') DEFAULT '0',
  `num` INT(11) DEFAULT '0',
  `bot_action` ENUM('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_order_status VALUES ('1', 'Аннулирован', 'red', '0', '0', '1', '', '0', '0', '0');
INSERT INTO phpshop_order_status VALUES ('2', 'Доставляется', '#DA881C', '0', '0', '1', '', '0', '0', '0');
INSERT INTO phpshop_order_status VALUES ('3', 'Выполнен', '#20ed41', '1', '1', '1', '', '0', '0', '0');
INSERT INTO phpshop_order_status VALUES ('101', 'Оплачено платежными системами', '#20a9ed', '1', '1', '1', '', '0', '0', '0');

DROP TABLE IF EXISTS phpshop_orders;
CREATE TABLE phpshop_orders (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datas` varchar(64) DEFAULT '',
  `uid` varchar(64) DEFAULT '0',
  `orders` blob,
  `status` text,
  `user` int(11) unsigned DEFAULT '0',
  `seller` enum('0','1') DEFAULT '0',
  `statusi` tinyint(11) DEFAULT '0',
  `country` varchar(255) DEFAULT '',
  `state` varchar(255) DEFAULT '',
  `city` varchar(255) DEFAULT '',
  `index` varchar(255) DEFAULT '',
  `fio` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `street` varchar(255) DEFAULT '',
  `house` varchar(255) DEFAULT '',
  `porch` varchar(255) DEFAULT '',
  `door_phone` varchar(255) DEFAULT '',
  `flat` varchar(255) DEFAULT '',
  `delivtime` varchar(255) DEFAULT '',
  `org_name` varchar(255) DEFAULT '',
  `org_inn` varchar(255) DEFAULT '',
  `org_kpp` varchar(255) DEFAULT '',
  `org_yur_adres` varchar(255) DEFAULT '',
  `org_fakt_adres` varchar(255) DEFAULT '',
  `org_ras` varchar(255) DEFAULT '',
  `org_bank` varchar(255) DEFAULT '',
  `org_kor` varchar(255) DEFAULT '',
  `org_bik` varchar(255) DEFAULT '',
  `org_city` varchar(255) DEFAULT '',
  `dop_info` text,
  `sum` float DEFAULT NULL,
  `files` text,
  `tracking` varchar(64) DEFAULT '',
  `admin` int(11) default 0,
  `servers` int(11) default 0,
  `paid` TINYINT(1) DEFAULT NULL,
  `bonus_minus` int(11) DEFAULT '0',
  `bonus_plus` int(11) DEFAULT '0',
  `date` INT(11) DEFAULT '0',
  `company` INT(11) DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_orders VALUES ('1', '1574888506', '1-68', 'a:2:{s:4:"Cart";a:5:{s:4:"cart";a:9:{i:58;a:11:{s:2:"id";s:2:"58";s:4:"name";s:23:"Брюки Mangoff 37 черный";s:5:"price";s:4:"9000";s:7:"price_n";s:1:"0";s:3:"uid";N;s:3:"num";i:1;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img11_84557s.jpg";s:6:"weight";N;s:6:"parent";i:11;s:10:"parent_uid";s:6:"098099";}i:86;a:11:{s:2:"id";s:2:"86";s:4:"name";s:28:"Футболка Mangoff 37 песочный";s:5:"price";s:3:"800";s:7:"price_n";s:1:"0";s:3:"uid";N;s:3:"num";i:3;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img10_90450s.jpg";s:6:"weight";N;s:6:"parent";i:10;s:10:"parent_uid";s:6:"455657";}i:141;a:11:{s:2:"id";s:3:"141";s:4:"name";s:24:"Очки Springfold пластик ";s:5:"price";s:4:"3590";s:7:"price_n";s:1:"0";s:3:"uid";N;s:3:"num";i:1;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img18_49453s.jpg";s:6:"weight";N;s:6:"parent";i:18;s:10:"parent_uid";s:6:"876876";}i:31;a:9:{s:2:"id";s:2:"31";s:4:"name";s:13:"Помада Zivage";s:5:"price";s:3:"300";s:7:"price_n";s:3:"600";s:3:"uid";s:9:"323255432";s:3:"num";i:2;s:6:"ed_izm";s:3:"шт.";s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img31_95215s.jpg";s:6:"weight";N;}i:115;a:11:{s:2:"id";s:3:"115";s:4:"name";s:23:"Пуловер Kustang L белый";s:5:"price";s:4:"6700";s:7:"price_n";s:1:"0";s:3:"uid";N;s:3:"num";i:1;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img21_18874s.jpg";s:6:"weight";N;s:6:"parent";i:21;s:10:"parent_uid";s:5:"34435";}i:131;a:11:{s:2:"id";s:3:"131";s:4:"name";s:15:"Кеды Abibas 39 ";s:5:"price";s:4:"4500";s:7:"price_n";s:1:"0";s:3:"uid";N;s:3:"num";i:1;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img23_77945s.jpg";s:6:"weight";N;s:6:"parent";i:23;s:10:"parent_uid";s:7:"8776987";}i:32;a:9:{s:2:"id";s:2:"32";s:4:"name";s:20:"Блеск для губ Zivage";s:5:"price";s:3:"115";s:7:"price_n";s:3:"230";s:3:"uid";s:6:"656754";s:3:"num";i:1;s:6:"ed_izm";s:3:"шт.";s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img32_33653s.jpg";s:6:"weight";N;}i:33;a:9:{s:2:"id";s:2:"33";s:4:"name";s:20:"Палетка теней Zivage";s:5:"price";s:3:"600";s:7:"price_n";s:4:"1200";s:3:"uid";s:7:"6546543";s:3:"num";i:1;s:6:"ed_izm";s:3:"шт.";s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img33_66960s.png";s:6:"weight";N;}i:34;a:9:{s:2:"id";s:2:"34";s:4:"name";s:16:"Пудра Saybelline";s:5:"price";s:4:"2500";s:7:"price_n";s:4:"5000";s:3:"uid";s:6:"123245";s:3:"num";i:1;s:6:"ed_izm";s:3:"шт.";s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img34_27336s.png";s:6:"weight";N;}}s:3:"num";i:12;s:3:"sum";s:5:"30005";s:6:"weight";i:0;s:8:"dostavka";i:0;}s:6:"Person";a:9:{s:4:"ouid";s:4:"1-68";s:4:"data";s:10:"1574888506";s:4:"time";s:8:"00:46 am";s:4:"mail";s:12:"test@mail.ru";s:11:"name_person";s:11:"Иван Иванов";s:14:"dostavka_metod";i:3;s:8:"discount";s:2:"15";s:7:"user_id";i:31;s:11:"order_metod";i:3;}}', 'a:2:{s:7:"maneger";N;s:4:"time";s:16:"28-11-2019 00:02";}', '31', '0', '2', '', '', '', '', '', '+7 (098) 709-86-09', '', '', '', '', '', '765765', '', '', '', '', '', '', '', '', '', '', '', '25504',  NULL, '',0,0,NULL,0,0, '1574888506','0');
INSERT INTO phpshop_orders VALUES ('2', '1575019429', '2-19', 'a:2:{s:4:"Cart";a:5:{s:4:"cart";a:10:{i:128;a:11:{s:2:"id";s:3:"128";s:4:"name";s:13:"Кеды Gans 39 ";s:5:"price";s:4:"8000";s:7:"price_n";s:1:"0";s:3:"uid";N;s:3:"num";i:2;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img25_67891s.jpg";s:6:"weight";N;s:6:"parent";i:25;s:10:"parent_uid";s:8:"98769876";}i:129;a:11:{s:2:"id";s:3:"129";s:4:"name";s:13:"Кеды Gans 40 ";s:5:"price";s:4:"8000";s:7:"price_n";s:1:"0";s:3:"uid";N;s:3:"num";i:2;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img25_67891s.jpg";s:6:"weight";N;s:6:"parent";i:25;s:10:"parent_uid";s:8:"98769876";}i:94;a:11:{s:2:"id";s:2:"94";s:4:"name";s:16:"Юбка Mangoff 37 ";s:5:"price";s:4:"5000";s:7:"price_n";s:4:"5600";s:3:"uid";N;s:3:"num";i:3;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img14_84532s.jpg";s:6:"weight";N;s:6:"parent";i:14;s:10:"parent_uid";s:9:"876876876";}i:16;a:9:{s:2:"id";s:2:"16";s:4:"name";s:13:"Ремень Oodjim";s:5:"price";s:4:"1290";s:7:"price_n";s:1:"0";s:3:"uid";s:6:"987987";s:3:"num";i:2;s:6:"ed_izm";s:3:"шт.";s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img16_92849s.jpg";s:6:"weight";N;}i:95;a:11:{s:2:"id";s:2:"95";s:4:"name";s:16:"Юбка Mangoff 38 ";s:5:"price";s:4:"5000";s:7:"price_n";s:4:"5700";s:3:"uid";N;s:3:"num";i:2;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img14_84532s.jpg";s:6:"weight";N;s:6:"parent";i:14;s:10:"parent_uid";s:9:"876876876";}i:96;a:11:{s:2:"id";s:2:"96";s:4:"name";s:16:"Юбка Mangoff 39 ";s:5:"price";s:4:"5000";s:7:"price_n";s:4:"5600";s:3:"uid";N;s:3:"num";i:1;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img14_84532s.jpg";s:6:"weight";N;s:6:"parent";i:14;s:10:"parent_uid";s:9:"876876876";}i:134;a:11:{s:2:"id";s:3:"134";s:4:"name";s:16:"Слипоны Gans 42 ";s:5:"price";s:4:"8000";s:7:"price_n";s:1:"0";s:3:"uid";N;s:3:"num";i:1;s:6:"ed_izm";N;s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img26_58467s.jpg";s:6:"weight";N;s:6:"parent";i:26;s:10:"parent_uid";s:8:"09878976";}i:62;a:11:{s:2:"id";s:2:"62";s:4:"name";s:28:"Джинсы 1001 Dressyy 37 синий";s:5:"price";s:4:"1200";s:7:"price_n";s:4:"1350";s:3:"uid";N;s:3:"num";i:4;s:6:"ed_izm";N;s:9:"pic_small";s:38:"/UserFiles/Image/Trial/img1_69884s.jpg";s:6:"weight";N;s:6:"parent";i:1;s:10:"parent_uid";s:7:"3343460";}i:44;a:9:{s:2:"id";s:2:"44";s:4:"name";s:44:"Набор инструментов для авто и дома TEKOV DMT";s:5:"price";s:4:"2380";s:7:"price_n";s:1:"0";s:3:"uid";s:8:"98769875";s:3:"num";i:1;s:6:"ed_izm";s:3:"шт.";s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img44_23523s.jpg";s:6:"weight";N;}i:40;a:9:{s:2:"id";s:2:"40";s:4:"name";s:58:"Презент маме. Вдохновляющее лидерство (комплект из 2 книг)";s:5:"price";s:4:"1450";s:7:"price_n";s:1:"0";s:3:"uid";s:7:"2452456";s:3:"num";i:1;s:6:"ed_izm";s:3:"шт.";s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img40_16081s.jpg";s:6:"weight";N;}}s:3:"num";i:19;s:3:"sum";i:69029;s:6:"weight";i:0;s:8:"dostavka";i:0;}s:6:"Person";a:9:{s:4:"ouid";s:4:"2-19";s:4:"data";s:10:"1575019429";s:4:"time";s:8:"12:49 pm";s:4:"mail";s:14:"test@gmail.com";s:11:"name_person";s:13:"Мария Волкова";s:14:"dostavka_metod";s:1:"3";s:8:"discount";s:2:"15";s:7:"user_id";i:32;s:11:"order_metod";s:5:"10032";}}', 'a:2:{s:7:"maneger";s:0:"";s:4:"time";s:16:"29-11-2019 14:55";}', '32', '0', '0', '', '', 'г Санкт-Петербург', '194044', 'Мария Волкова', '&#43;7 (098) 709-86-09', 'Лесной пр-кт', 'д 20 к 12 ', '', '', '', 'с 13', '', '', '', '', '', '', '', '', '', '', '', '69029',  NULL, '',0,0,NULL,0,0, '1575019429','0');
INSERT INTO phpshop_orders VALUES ('3', '1575025403', '3-49', 'a:2:{s:4:"Cart";a:5:{s:4:"cart";a:1:{s:0:"";a:10:{s:2:"id";s:2:"46";s:3:"uid";s:8:"34546456";s:4:"name";s:36:"Пила циркулярная RedVerg RD-CS130-55";s:5:"price";s:4:"7000";s:3:"num";i:1;s:6:"weight";s:0:"";s:6:"ed_izm";s:0:"";s:9:"pic_small";s:39:"/UserFiles/Image/Trial/img46_54151s.jpg";s:6:"parent";i:0;s:4:"user";i:0;}}s:3:"num";i:1;s:3:"sum";i:5950;s:6:"weight";N;s:8:"dostavka";s:1:"0";}s:6:"Person";a:17:{s:4:"ouid";s:7:"101-335";s:4:"data";i:1575025403;s:4:"time";s:0:"";s:4:"mail";s:13:"mail3@test.ru";s:11:"name_person";s:17:"Людмила Пирожкова";s:8:"org_name";s:0:"";s:7:"org_inn";s:0:"";s:7:"org_kpp";s:0:"";s:8:"tel_code";s:0:"";s:8:"tel_name";s:0:"";s:8:"adr_name";s:0:"";s:14:"dostavka_metod";s:1:"1";s:8:"discount";s:2:"15";s:7:"user_id";s:0:"";s:6:"dos_ot";s:0:"";s:6:"dos_do";s:0:"";s:11:"order_metod";s:5:"10032";}}', 'a:2:{s:7:"maneger";s:0:"";s:4:"time";s:16:"29-11-2019 14:21";}', '0', '0', '0', 'Россия', '', 'Москва', '', 'Людмила Пирожкова', '&#43;7 (768) 430-49-89', 'ул Лесная', 'д 9 ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '5950', 'N;', '',0,0,NULL,0,0, '1575025403','0');
DROP TABLE IF EXISTS phpshop_page;
CREATE TABLE phpshop_page (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `link` varchar(64) DEFAULT '',
  `category` int(11) DEFAULT '0',
  `keywords` text,
  `description` varchar(255) DEFAULT '',
  `content` text,
  `servers` varchar(64) DEFAULT '',
  `num` smallint(3) DEFAULT '0',
  `datas` int(11) DEFAULT '0',
  `odnotip` text,
  `title` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '0',
  `secure` enum('0','1') DEFAULT '0',
  `secure_groups` varchar(255) DEFAULT '',
  `icon` varchar(255) DEFAULT '',
  `preview` text,
  `footer` enum('0','1') DEFAULT '1',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `link` (`link`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_page VALUES ('1', 'Главный заголовок вашего магазина', 'index', '2000', '', '', '<p>Это приветственный текст вашего интернет-магазина. Лучше придумать текст, содержащий в себе ключевые слова вашего магазина, по которым вы хотите продвигаться в будущем. Текст должен хорошо читаться. Заголовок этой страницы уже отмечен тегом H1 во всех шаблонах, он будет восприниматься поисковиками как главный. Остальные заголовки вы настраиваете в меню Настройка Seo заголовки.</p>\n<p> </p>', '', '0', '0', '', '', '1', '0', '', '', '', '0');
INSERT INTO phpshop_page VALUES ('62', 'Топ-10 книг для женщин', 'top-10_knig_dlya_zhenschin', '18', '', '', '<p><img src="/UserFiles/Image/Trial/img33_95820s.png" alt="" width="300" height="300" /></p>\r\n<p class="text-justify">С другой стороны начало повседневной работы по формированию позиции обеспечивает широкому кругу (специалистов) участие в формировании дальнейших направлений развития. Разнообразный и богатый опыт рамки и место обучения кадров представляет собой интересный эксперимент проверки дальнейших направлений развития. Не следует, однако забывать, что реализация намеченных плановых заданий в значительной степени обуславливает создание позиций, занимаемых участниками в отношении поставленных задач. Идейные соображения высшего порядка, а также укрепление и развитие структуры в значительной степени обуславливает создание модели развития. Идейные соображения высшего порядка, а также сложившаяся структура организации позволяет выполнять важные задания по разработке систем массового участия. Товарищи! консультация с широким активом в значительной степени обуславливает создание направлений прогрессивного развития.</p>\r\n<p class="text-justify">Повседневная практика показывает, что консультация с широким активом позволяет выполнять важные задания по разработке позиций, занимаемых участниками в отношении поставленных задач. Равным образом консультация с широким активом играет важную роль в формировании систем массового участия.</p>\r\n<p class="text-justify">Равным образом дальнейшее развитие различных форм деятельности влечет за собой процесс внедрения и модернизации новых предложений. Идейные соображения высшего порядка, а также консультация с широким активом представляет собой интересный эксперимент проверки существенных финансовых и административных условий. Задача организации, в особенности же рамки и место обучения кадров позволяет выполнять важные задания по разработке систем массового участия.</p>\r\n<p class="text-justify">Таким образом рамки и место обучения кадров способствует подготовки и реализации позиций, занимаемых участниками в отношении поставленных задач. С другой стороны сложившаяся структура организации влечет за собой процесс внедрения и модернизации соответствующий условий активизации. Не следует, однако забывать, что постоянное информационно-пропагандистское обеспечение нашей деятельности требуют от нас анализа дальнейших направлений развития. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности требуют определения и уточнения существенных финансовых и административных условий. С другой стороны дальнейшее развитие различных форм деятельности в значительной степени обуславливает создание существенных финансовых и административных условий.</p>\r\n<p class="text-justify">Не следует, однако забывать, что дальнейшее развитие различных форм деятельности позволяет оценить значение соответствующий условий активизации. Товарищи! реализация намеченных плановых заданий требуют определения и уточнения систем массового участия. Повседневная практика показывает, что начало повседневной работы по формированию позиции позволяет оценить значение модели развития.</p>\r\n<p class="text-justify">Идейные соображения высшего порядка, а также консультация с широким активом играет важную роль в формировании позиций, занимаемых участниками в отношении поставленных задач. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности в значительной степени обуславливает создание форм развития.</p>', '', '1', '1574805600', '23,25,26,24', '', '1', '0', '', '/UserFiles/Image/Trial/img31_66601s.jpg', '<p class="text-justify">Повседневная практика показывает, что консультация с широким активом позволяет выполнять важные задания по разработке позиций, занимаемых участниками в отношении поставленных задач. Равным образом консультация с широким активом играет важную роль в формировании систем массового участия.</p>\r\n<p class="text-justify">&nbsp;</p>', '0');
INSERT INTO phpshop_page VALUES ('63', 'Мифы о профессиональной косметике', 'mify_prof_cosmetis', '18', '', '', '<p>Создавайте обзоры, запишите видео распаковки ваших топовых продуктов, вставьте видео сюда.&nbsp;</p>\r\n<p>&nbsp;<iframe src="https://www.youtube.com/embed/uxQeNM_N29A" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>\r\n<p>Диаграммы связей призывают нас к новым свершениям, которые, в свою очередь, должны быть в равной степени предоставлены сами себе. Не следует, однако, забывать, что внедрение современных методик создаёт необходимость включения в производственный план целого ряда внеочередных мероприятий с учётом комплекса стандартных подходов. Лишь диаграммы связей представляют собой не что иное, как квинтэссенцию победы маркетинга над разумом и должны быть объективно рассмотрены соответствующими инстанциями.</p>\r\n<p>Внезапно, активно развивающиеся страны третьего мира набирают популярность среди определенных слоев населения, а значит, должны быть ассоциативно распределены по отраслям. Разнообразный и богатый опыт говорит нам, что постоянный количественный рост и сфера нашей активности играет определяющее значение для распределения внутренних резервов и ресурсов. Учитывая ключевые сценарии поведения, реализация намеченных плановых заданий не даёт нам иного выбора, кроме определения вывода текущих активов!</p>', '', '1', '1574805600', '37,36,35', '', '1', '0', '', '/UserFiles/Image/Trial/img19_76156s.jpg', '<p>Это анонс вашей статьи.</p>', '0');
INSERT INTO phpshop_page VALUES ('61', 'Фото какого размера загружать?', 'pro_photo', '1000', '', '', '<p>Все шаблоны в PHPShop - адаптивные. Это значит, что на всех устройствах картинки должны отображаться корректно. Все параметры отображения в шаблоне задаются стилями - HTML версткой шаблона, которые <strong>были подобраны специально для этой тестовой базы товаров</strong>.</p>\n<p>Поэтому важно соблюдать</p>\n<p><strong>- примерно такие же размеры загружаемых изображений;</strong><br /><strong>- не загружать фото огромного размера для картинок каталогов;</strong><br /><strong>- подбирать фото примерно одной пропорции - или горизонтальная ориентация, или вертикальная;</strong><br /><strong>- сразу решить, какой отступ будет в картинке от края изображения на нем, или фото будет обрезано встык.</strong></p>\n<p>Все это влияет на конечное отображение вашего будущего магазина!</p>\n<table class="table table-striped">\n<tbody>\n<tr>\n<td class="td1" style="width: 134px;" valign="top">\n<p class="p1">Вид</p>\n</td>\n<td class="td1" style="width: 94px;" valign="top">\n<p class="p1">Размер</p>\n</td>\n<td class="td1" style="width: 441px;" valign="top">\n<p class="p1">Пояснение</p>\n</td>\n<td class="td1" style="width: 209px;" valign="top">\n<p class="p1">Пример картинки</p>\n</td>\n</tr>\n<tr>\n<td class="td1" style="width: 134px;" valign="top">\n<p class="p1">Фото товаров</p>\n</td>\n<td class="td1" style="width: 94px;" valign="top">\n<p class="p2">~ 800*1000</p>\n</td>\n<td class="td1" style="width: 441px;" valign="top">\n<p class="p1">Добавлять лучше все фото в одной пропорции. Вы добавляете одно большое фото, при ручной загрузке происходит его автоматическая нарезка под размеры, указанные в меню Настройки - Изображения.</p>\n</td>\n<td class="td1" style="width: 209px;" valign="top">\n<p class="p1"><a href="https://demo.phpshop.ru/UserFiles/Image/Trial/img9_38664.jpg" target="_blank" rel="noopener">Пример картинки для товара</a></p>\n</td>\n</tr>\n<tr>\n<td class="td1" style="width: 134px;" valign="top">\n<p class="p1">Фото каталогов</p>\n</td>\n<td class="td1" style="width: 94px;" valign="top">\n<p class="p1">строго 410*200</p>\n</td>\n<td class="td1" style="width: 441px;" valign="top">\n<p class="p1">Фото каталогов лучше добавлять этого размера, поскольку именно под данный размер подобраны стили бесплатных шаблонов, для лучшего отображения на всех устройствах.</p>\n</td>\n<td class="td1" style="width: 209px;" valign="top">\n<p class="p3"><a href="https://demo.phpshop.ru/UserFiles/Image/Trial/phpshop6_catalogs4.jpg" target="_blank" rel="noopener">Пример картинки для каталога</a></p>\n</td>\n</tr>\n<tr>\n<td class="td1" style="width: 134px;" valign="top">\n<p class="p1">Главный баннер для широких экранов</p>\n</td>\n<td class="td1" style="width: 94px;" valign="top">\n<p class="p1">~ 1440*300</p>\n</td>\n<td class="td1" style="width: 441px;" valign="top">\n<p class="p1">Главный баннер загружается в меню Маркетинг - Слайдер. Все баннеры в Слайдер нужно загружать одного размера.</p>\n</td>\n<td class="td1" style="width: 209px;" valign="top">\n<p class="p3"><a href="https://demo.phpshop.ru/UserFiles/Image/Trial/slider1.jpg" target="_blank" rel="noopener">Пример картинки баннера для широких экранов</a></p>\n</td>\n</tr>\n<tr>\n<td class="td1" style="width: 134px;" valign="top">\n<p class="p1">Главный баннер для мобильных устройств</p>\n</td>\n<td class="td1" style="width: 94px;" valign="top">\n<p class="p1">~410*200</p>\n</td>\n<td class="td1" style="width: 441px;" valign="top">\n<p class="p1">Поскольку Слайдер для широких экранов имеет горизонтальную ориентацию, при сужении на маленьких экранов ничего не будет видно. Также его размер и вес будет снижать загрузку на мобильных, что не нравится поисковикам. Для этого мы ввели отдельный стикер в шаблон для мобильного слайдера. Загружается в меню Модули - Стикеры - Слайдер для мобильных.</p>\n</td>\n<td class="td1" style="width: 209px;" valign="top">\n<p class="p3"><a href="https://demo.phpshop.ru/UserFiles/Image/Trial/banner_for_mobile.jpg" target="_blank" rel="noopener">Пример картинки баннера для мобильных</a></p>\n</td>\n</tr>\n<tr>\n<td class="td1" style="width: 134px;" valign="top">\n<p class="p1">Картинка вертикального баннера в колонке</p>\n</td>\n<td class="td1" style="width: 94px;" valign="top">\n<p class="p1">~ 420* 600</p>\n</td>\n<td class="td1" style="width: 441px;" valign="top">\n<p class="p1">Баннер в левой колонке загружается в меню Маркетинг - Баннеры.</p>\n</td>\n<td class="td1" style="width: 209px;" valign="top">\n<p class="p3"><a href="https://demo.phpshop.ru/UserFiles/Image/Trial/banner_vert.jpg" target="_blank" rel="noopener">Пример картинки вертикального баннера</a></p>\n</td>\n</tr>\n<tr>\n<td class="td1" style="width: 134px;" valign="top">\n<p class="p1">Картинка горизонтального баннера</p>\n</td>\n<td class="td1" style="width: 94px;" valign="top">\n<p class="p1">~1830*130</p>\n</td>\n<td class="td1" style="width: 441px;" valign="top">\n<p class="p1">Горизонтальный баннер в теле сайта загружается в меню Модули - Стикеры - Горизонтальный баннер.</p>\n</td>\n<td class="td1" style="width: 209px;" valign="top">\n<p class="p3"><a href="https://demo.phpshop.ru/UserFiles/Image/Trial/banner_hor.jpg" target="_blank" rel="noopener">Пример картинки горизонтального баннера</a></p>\n</td>\n</tr>\n<tr>\n<td class="td1" style="width: 134px;" valign="top">\n<p class="p1">Картинка для логотипа бренда</p>\n</td>\n<td class="td1" style="width: 94px;" valign="top">\n<p class="p1">~ 210*70</p>\n</td>\n<td class="td1" style="width: 441px;" valign="top">\n<p class="p1">Логотипы лучше делать на подложке, чтобы они хорошо смотрелись на темном фоне. Также подложка нужна, чтобы логотипы разной ориентации - высокие, длинные, смотрелись одинаково хорошо.  В картинках желательно делать одинаковые отступы от края, чтобы логотипы шли в ряд ровно. Обратите внимание на пример тестового логотипа. </p>\n</td>\n<td class="td1" style="width: 209px;" valign="top">\n<p class="p3"><a href="https://demo.phpshop.ru/UserFiles/Image/Trial/brand-3.png" target="_blank" rel="noopener">Пример логотипа бренда</a></p>\n</td>\n</tr>\n</tbody>\n</table>', '', '1', '1574805600', '', '', '1', '0', '', '', '', '1');
INSERT INTO phpshop_page VALUES ('26', 'Сохранятся ли данные демо-версии?', 'purchase', '1000', '', '', '<p>Ваш тестовый интернет-магазин <strong>@serverName@</strong> на базе платформы PHPShop @version@ будет работать 30 дней. <strong>Вы можете уже сейчас наполнять свой магазин, все данные после покупки сохранятся!&nbsp;</strong></p>\r\n<p>Для приобретения платформы интернет-магазина PHPShop, нужно перейти в раздел оформления заказа по кнопке ниже. Далее, вам нужно выбрать удобный тип оплаты - электронный: картами Visa, Mastercard, через банкоматы Qiwi, через Сбербанк, банковским переводом для юридических лиц. После выбора оплаты, в разделе Счета появится счет на оплату в электронном виде. Оригиналы всех документов мы отправляем по почте, указанной в разделе Профиль вашего личного кабинета.</p>\r\n<p><a class="btn btm-sm btn-primary" href="https://www.phpshop.ru/order/?from=@serverName@&amp;action=order" target="_blank" rel="noopener">Перейти к оформлению заказа PHPShop</a></p>', '', '2', '1574373600', '', 'Купить PHPShop', '1', '0', '', '', '', '0');
INSERT INTO phpshop_page VALUES ('23', 'Управление', 'admin', '1000', '', '', '<p>Для доступа к панели управления PHPShop нажмите сочетание клавиш <kbd>Ctrl</kbd> &#43; <kbd>F12</kbd> или используйте кнопку перехода ниже.<br /> Логин по умолчанию <strong>demo</strong>, пароль <strong>demouser</strong>. Если вы при установке задали свой логин и пароль, то используйте свои данные при авторизации.</p>\n<p><a class="btn btn-success btn-sm" href="phpshop/admpanel/" target="_blank" rel="noopener"> Переход в панель управления</a></p>\n<h2>Тестовая база</h2>\n<p>При установке магазина заполняется тестовая товарная база для демонстрации возможностей программы. Для очистки тестовой базы следует в панели управления магазином перейти в меню <kbd>База</kbd> - <kbd>SQL запрос к базе</kbd> выбрать в выпадающем списке опцию <strong>"Очистить базу"</strong>. Обращаем Ваше внимание, что очистится вся товарная база с момента начала работы магазина.</p>\n<h2>Дополнительные утилиты</h2>\n<p>PHPShop EasyControl - <strong>уникальный набор бесплатных утилит</strong> для создания и управления интернет-магазином PHPShop на локальном компьютере . EasyControl прост в установке и не требует никаких специальных навыков. С помощью EasyControl Вы сможете установить сайт локально на ПК либо на хостинг, обновлять платформу сайта, обрабатывать заказы, заполнять товарную базу и редактировать шаблоны. В состав пакета входят более 10 утилит: <strong>Monitor, Updater, Installer, Chat, Price Loader, Password Restore</strong> и другие.</p>\n<p><a class="btn btn-info btn-sm" href="https://www.phpshop.ru/loads/files/setup.exe" target="_blank" rel="noopener"> Скачать утилиты EasyControl</a></p>', '', '3', '1563400800', '39,40', 'Администрирование PHPShop', '1', '0', '', '', '', '0');
INSERT INTO phpshop_page VALUES ('60', 'Обзор вашего бестселлера', 'obzor_vashego_bestsellera', '18', '', '', '<p>Опишите ваш лучший продукт, добавьте фото и видео распаковки товара в меню Веб-сайт - Страницы.</p>\r\n<p><img src="/UserFiles/Image/Trial/phpshop6_catalogs7.jpg" alt="" width="410" height="200" /></p>\r\n<p class="text-justify">Не следует, однако забывать, что новая модель организационной деятельности играет важную роль в формировании дальнейших направлений развития. Задача организации, в особенности же новая модель организационной деятельности играет важную роль в формировании соответствующий условий активизации. С другой стороны дальнейшее развитие различных форм деятельности требуют определения и уточнения направлений прогрессивного развития. Значимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции обеспечивает широкому кругу (специалистов) участие в формировании систем массового участия. Не следует, однако забывать, что постоянный количественный рост и сфера нашей активности требуют от нас анализа позиций, занимаемых участниками в отношении поставленных задач.</p>\r\n<p class="text-justify">Разнообразный и богатый опыт сложившаяся структура организации способствует подготовки и реализации соответствующий условий активизации. Разнообразный и богатый опыт начало повседневной работы по формированию позиции требуют от нас анализа направлений прогрессивного развития. Задача организации, в особенности же начало повседневной работы по формированию позиции представляет собой интересный эксперимент проверки позиций, занимаемых участниками в отношении поставленных задач. Равным образом дальнейшее развитие различных форм деятельности способствует подготовки и реализации системы обучения кадров, соответствует насущным потребностям. Задача организации, в особенности же новая модель организационной деятельности требуют от нас анализа существенных финансовых и административных условий.</p>\r\n<p class="text-justify"><img src="/UserFiles/Image/Trial/img15_92639s.jpg" alt="" width="240" height="300" /></p>\r\n<p class="text-justify">Значимость этих проблем настолько очевидна, что консультация с широким активом способствует подготовки и реализации соответствующий условий активизации. Повседневная практика показывает, что начало повседневной работы по формированию позиции позволяет оценить значение системы обучения кадров, соответствует насущным потребностям. Равным образом новая модель организационной деятельности позволяет оценить значение дальнейших направлений развития. Товарищи! сложившаяся структура организации требуют определения и уточнения новых предложений. Равным образом дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании дальнейших направлений развития. Значимость этих проблем настолько очевидна, что сложившаяся структура организации играет важную роль в формировании систем массового участия.</p>\r\n<p class="text-justify">Не следует, однако забывать, что начало повседневной работы по формированию позиции в значительной степени обуславливает создание модели развития. Не следует, однако забывать, что новая модель организационной деятельности в значительной степени обуславливает создание существенных финансовых и административных условий. Товарищи! постоянный количественный рост и сфера нашей активности играет важную роль в формировании модели развития. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности способствует подготовки и реализации новых предложений.</p>\r\n<p class="text-justify">Равным образом начало повседневной работы по формированию позиции позволяет оценить значение систем массового участия. Таким образом дальнейшее развитие различных форм деятельности позволяет выполнять важные задания по разработке существенных финансовых и административных условий.</p>\r\n<p class="text-justify">С другой стороны постоянный количественный рост и сфера нашей активности позволяет оценить значение систем массового участия. Идейные соображения высшего порядка, а также начало повседневной работы по формированию позиции позволяет оценить значение новых предложений. Товарищи! новая модель организационной деятельности влечет за собой процесс внедрения и модернизации существенных финансовых и административных условий.</p>', '', '1', '1574460000', '', '', '1', '0', '', '', '', '0');
INSERT INTO phpshop_page VALUES ('27', 'Ресурсы', 'help', '0', '', '', '<h3>Справка</h3>\r\n<p>Справочно-информационный сайт (F.A.Q.), описывающий возможности PHPShop и ответы на частые вопросы по управлению интернет-магазином. Снабжен большим количеством скриншотов и видео-уроков.<br />Адрес: <a href="http://faq.phpshop.ru" target="_blank" rel="noopener">faq.phpshop.ru</a></p>\r\n<h3>Техническая документация</h3>\r\n<p>Справочный сайт для разработчиков (WIKI). Содержит большое количество технической документации с примерами по разработке PHPShop. Описание утилит EasyControl и дополнительных модулей.<br />Адрес: <a href="http://wiki.phpshop.ru" target="_blank" rel="noopener">wiki.phpshop.ru</a></p>\r\n<h3>Описание API</h3>\r\n<p>Справочный сайт для разработчиков (PHPDoc). Содержит подробное описание API PHPShop, функций и классов.<br />Адрес: <a href="http://doc.phpshop.ru" target="_blank" rel="noopener">doc.phpshop.ru</a></p>\r\n<h3>База знаний</h3>\r\n<p>Справочный сайт службы технической поддержки. Содержит ответы по наиболее частым вопросам, встречающихся у пользователей PHPShop в поддержке.<br />Адрес: <a href="https://help.phpshop.ru" target="_blank" rel="noopener">help.phpshop.ru</a></p>\r\n<h3>Социальные сети</h3>\r\n<p>Персональные странички в популярных социальный сетях. Содержат много интересных публикаций по возможностям платформы, новостях и акциям.<br />Адрес: <a href="https://www.facebook.com/shopsoft" target="_blank" rel="noopener">https://www.facebook.com/shopsoft</a><br /><a href="https://twitter.com/PHPShopCMS" target="_blank" rel="noopener">https://twitter.com/PHPShopCMS</a><br /><a href="https://plus.google.com/&#43;PhpshopRu" target="_blank" rel="noopener">https://plus.google.com/&#43;PhpshopRu</a></p>\r\n<h3>Видео-уроки</h3>\r\n<p>Информационный портал с видео-уроками по работе с PHPShop на портале YouTube. Содержат подробные уроки по настройки и работе с 1С-Синхронизацией, PHPShop и утилитами EasyControl.<br />Адрес: <a href="http://www.youtube.com/user/phpshopsoftware" target="_blank" rel="noopener">http://www.youtube.com/user/phpshopsoftware</a></p>', '', '1', '1574373600', '', '', '1', '0', '', '', '', '0');
INSERT INTO phpshop_page VALUES ('22', 'Условия оферты при оплате Visa и Mastercard', 'agreement', '0', '', '', '', '', '1', '1574373600', '', '', '1', '0', '', '', '', '0');
INSERT INTO phpshop_page VALUES ('43', 'Политика конфиденциальности', 'politika_konfidencialnosti', '0', '', '', '<h2>Политика конфиденциальности для интернет-магазина</h2>\n<p>В примере жирным я выделил то, что вам надо подправить для своего проекта.</p>\n<ol>\n<li>ОПРЕДЕЛЕНИЕ ТЕРМИНОВ\n<ol>\n<li>Существующая на текущий момент политика конфиденциальности персональных данных (далее  Политика конфиденциальности) работает со следующими понятиями:\n<ol>\n<li>«Администрация сайта Интернет-магазина (далее  Администрация сайта)». Так называют представляющих интересы организации специалистов, в чьи обязанности входит управление сайтом, то есть организация и (или) обработка поступивших на него персональных данных. Для выполнения этих обязанностей они должны чётко представлять, для чего обрабатываются сведения, какие сведения должна быть обработаны, какие действия (операции) должны производиться с полученными сведениями.</li>\n<li>«Персональные данные»  сведения, имеющие прямое или косвенное отношение к определённому либо определяемому физическому лицу (также называемому субъектом персональных данных).</li>\n<li>«Обработка персональных данных»  любая операция (действие) либо совокупность таковых, которые Администрация производит с персональными данными. Их могут собирать, записывать, систематизировать, накапливать, хранить, уточнять (при необходимости обновлять или изменять), извлекать, использовать, передавать (распространять, предоставлять, открывать к ним доступ), обезличивать, блокировать, удалять и даже уничтожать. Данные операции (действия) могут выполняться как автоматически, так и вручную.</li>\n<li>«Конфиденциальность персональных данных»  обязательное требование, предъявляемое к Оператору или иному работающему с данными Пользователя должностному лицу, хранить полученные сведения в тайне, не посвящая в них посторонних, если предоставивший персональные данные Пользователь не изъявил своё согласие, а также отсутствует законное основание для разглашения.</li>\n<li>«Пользователь сайта Интернет-магазина» (далее  Пользователь)»  человек, посетивший сайт Интернет-магазина, а также пользующийся его программами и продуктами.</li>\n<li>«Cookies»  короткий фрагмент данных, пересылаемый веб-браузером или веб-клиентом веб-серверу в HTTP-запросе, всякий раз, когда Пользователь пытается открыть страницу Интернет-магазина. Фрагмент хранится на компьютере Пользователя.</li>\n<li>«IP-адрес»  уникальный сетевой адрес узла в компьютерной сети, построенной по протоколу TCP/IP.</li>\n</ol>\n</li>\n</ol>\n</li>\n<li>ОБЩИЕ ПОЛОЖЕНИЯ\n<ol>\n<li>Просмотр сайта Интернет-магазина, а также использование его программ и продуктов подразумевают автоматическое согласие с принятой там Политикой конфиденциальности, подразумевающей предоставление Пользователем персональных данных на обработку.</li>\n<li>Если Пользователь не принимает существующую Политику конфиденциальности, Пользователь должен покинуть Интернет-магазин.</li>\n<li>Имеющаяся Политика конфиденциальности распространяется только на сайт Интернет-магазина. Если по ссылкам, размещённым на сайте последнего, Пользователь зайдёт на ресурсы третьих лиц, Интернет-магазин за его действия ответственности не несёт.</li>\n<li>Проверка достоверности персональных данных, которые решил сообщить принявший Политику конфиденциальности Пользователь, не входит в обязанности Администрации сайта.</li>\n</ol>\n</li>\n<li>ПРЕДМЕТ ПОЛИТИКИ КОНФИДЕНЦИАЛЬНОСТИ\n<ol>\n<li>Согласно проводимой в текущий период Политике конфиденциальности Администрация Интернет-магазина обязана не разглашать персональные данные, сообщаемые Пользователями, регистрирующимися на сайте или оформляющими заказ на покупку товара, а также обеспечивать этим данным абсолютную конфиденциальность.</li>\n<li>Чтобы сообщить персональные данные, Пользователь заполняет расположенные на сайте интернет-магазина электронные формы. Персональными данными Пользователя, которые подлежат обработке, являются:\n<ol>\n<li>его фамилия, имя, отчество;</li>\n<li>его контактный телефон;</li>\n<li>его электронный адрес (e-mail);</li>\n<li>адрес, по которому должен быть доставлен купленный им товар;</li>\n<li>адрес проживания Пользователя.</li>\n</ol>\n</li>\n<li>Защита данных, автоматически передаваемых при просмотре рекламных блоков и посещении страниц с установленными на них статистическими скриптами системы (пикселями) осуществляется Интернет-магазином. Вот перечень этих данных:<br />IP-адрес;<br />сведения из cookies;<br />сведения о браузере (либо другой программе, через которую становится доступен показ рекламы);<br />время посещения сайта;<br />адрес страницы, на которой располагается рекламный блок;<br />реферер (адрес предыдущей страницы).</li>\n<li>Последствием отключения cookies может стать невозможность доступа к требующим авторизации частям сайта Интернет-магазина.</li>\n<li>Интернет-магазин собирает статистику об IP-адресах всех посетителей. Данные сведения нужны, чтобы выявить и решить технические проблемы и проконтролировать, насколько законным будет проведение финансовых платежей.</li>\n<li>Любые другие неоговорённые выше персональные сведения (о том, когда и какие покупки были сделаны, какой при этом использовался браузер, какая была установлена операционная система и пр.) надёжно хранятся и не распространяются. Исключение существующая Политика конфиденциальности предусматривает для случаев, описанных в п.п. 5.2 и 5.3.</li>\n</ol>\n</li>\n<li>ЦЕЛИ СБОРА ПЕРСОНАЛЬНОЙ ИНФОРМАЦИИ ПОЛЬЗОВАТЕЛЯ\n<ol>\n<li>Сбор персональных данных Пользователя Администрацией Интернет-магазина проводится ради того, чтобы:\n<ol>\n<li>Идентифицировать Пользователя, который прошёл процедуру регистрации на сайте Интернет-магазина, чтобы оформить заказ и (или) приобрести товар данного магазина дистанционно.</li>\n<li>Открыть Пользователю доступ к персонализированным ресурсам данного сайта.</li>\n<li>Установить с Пользователем обратную связь, под которой подразумевается, в частности, рассылка запросов и уведомлений, касающихся использования сайта Интернет-магазина, обработка пользовательских запросов и заявок, оказание прочих услуг.</li>\n<li>Определить местонахождение Пользователя, чтобы обеспечить безопасность платежей и предотвратить мошенничество.</li>\n<li>Подтвердить, что данные, которые предоставил Пользователь, полны и достоверны.</li>\n<li>Создать учётную запись для совершения Покупок, если Пользователь изъявил на то своё желание.</li>\n<li>Уведомить Пользователя о состоянии его заказа в Интернет-магазине.</li>\n<li>Обрабатывать и получать платежи, подтверждать налог или налоговые льготы, оспаривать платёж, определять, целесообразно ли предоставить конкретному Пользователю кредитную линию.</li>\n<li>Обеспечить Пользователю максимально быстрое решение проблем, встречающихся при использовании Интернет-магазина, за счёт эффективной клиентской и технической поддержки.</li>\n<li>Своевременно информировать Пользователя об обновлённой продукции, ознакомлять его с уникальными предложениями, новыми прайсами, новостями о деятельности Интернет-магазина или его партнёров и прочими сведениями, если Пользователь изъявит на то своё согласие.</li>\n<li>Рекламировать товары Интернет-магазина, если Пользователь изъявит на то своё согласие.</li>\n<li>Предоставить Пользователю доступ на сайты или сервисы Интернет-магазина, помогая ему тем самым получать продукты, обновления и услуги.</li>\n</ol>\n</li>\n</ol>\n</li>\n<li>СПОСОБЫ И СРОКИ ОБРАБОТКИ ПЕРСОНАЛЬНОЙ ИНФОРМАЦИИ\n<ol>\n<li>Срок обработки персональных данных Пользователя ничем не ограничен. Процедура обработки может проводиться любым предусмотренным законодательством способом. В частности, с помощью информационных систем персональных данных, которые могут вестись автоматически либо без средств автоматизации.</li>\n<li>Обработанные Администрацией сайта персональные данные Пользователя могут передаваться третьим лицам, в число которых входят курьерские службы, организации почтовой связи, операторы электросвязи. Делается это для того, чтобы выполнить заказ Пользователя, оставленный им на сайте Интернет-магазина, и доставить товар по адресу. Согласие Пользователя на подобную передачу предусмотрено правилами политики сайта.</li>\n<li>Также обработанные Администрацией сайта персональные данные могут передаваться уполномоченным органов государственной власти Российской Федерации, если это осуществляется на законных основаниях и в предусмотренном российским законодательством порядке.</li>\n<li>Если персональные данные будут утрачены или разглашены, Пользователь уведомляется об этом Администрацией сайта.</li>\n<li>Все действия Администрации сайта направлены на то, чтобы не допустить к персональным данным Пользователя третьих лиц (за исключением п.п. 5.2, 5.3). Последним эта информация не должна быть доступна даже случайно, дабы те не уничтожили её, не изменили и не блокировали, не копировали и не распространяли, а также не совершали прочие противозаконные действия. Для защиты пользовательских данных Администрация располагает комплексом организационных и технических мер.</li>\n<li>Если персональные данные будут утрачены либо разглашены, Администрация сайта совместно с Пользователем готова принять все возможные меры, дабы предотвратить убытки и прочие негативные последствия, вызванные данной ситуацией.</li>\n</ol>\n</li>\n<li>ОБЯЗАТЕЛЬСТВА СТОРОН\n<ol>\n<li>В обязанности Пользователя входит:\n<ol>\n<li>Сообщение соответствующих требованиям Интернет-магазина сведений о себе.</li>\n<li>Обновление и дополнение предоставляемых им сведений в случае изменения таковых.</li>\n</ol>\n</li>\n<li>В обязанности Администрации сайта входит:\n<ol>\n<li>Применение полученных сведений исключительно в целях, обозначенных в п. 4 существующей Политики конфиденциальности.</li>\n<li>Обеспечение конфиденциальности поступивших от Пользователя сведений. Они не должны разглашаться, если Пользователь не даст на то письменное разрешение. Также Администрация не имеет права продавать, обменивать, публиковать либо разглашать прочими способами переданные Пользователем персональные данные, исключая п.п. 5.2 и 5.3 существующей Политики конфиденциальности.</li>\n<li>Принятие мер предосторожности, дабы персональные данные Пользователя оставались строго конфиденциальными, точно также, как остаются конфиденциальными такого рода сведения в современном деловом обороте.</li>\n<li>Блокировка персональных пользовательских данных с того момента, с которого Пользователь либо его законный представитель сделает соответствующий запрос. Право сделать запрос на блокировку также предоставляется органу, уполномоченному защищать права Пользователя, предоставившего Администрации сайта свои данные, на период проверки, в случае обнаружения недостоверности сообщённых персональных данных либо неправомерности действий.</li>\n</ol>\n</li>\n</ol>\n</li>\n<li>ОТВЕТСТВЕННОСТЬ СТОРОН\n<ol>\n<li>В случае неисполнения Администрацией сайта собственных обязательств и, как следствие, убытков Пользователя, понесённых из-за неправомерного использования предоставленной им информации, ответственность возлагается на неё. Об этом, в частности, утверждает российское законодательство. Исключение существующая в настоящее время Политика конфиденциальности делает для случаев, отражённых в п.п. 5.2, 5.3 и 7.2.</li>\n<li>Но существует ряд случаев, когда Администрация сайта ответственности не несёт, если пользовательские данные утрачиваются или разглашаются. Это происходит тогда, когда они:\n<ol>\n<li>Превратились в достояние общественности до того, как были утрачены или разглашены.</li>\n<li>Были предоставлены третьими лицами до того, как их получила Администрация сайта.</li>\n<li>Разглашались с согласия Пользователя.</li>\n</ol>\n</li>\n</ol>\n</li>\n<li>РАЗРЕШЕНИЕ СПОРОВ\n<ol>\n<li>Если Пользователь недоволен действиями Администрации Интернет-магазина и намерен отстаивать свои права в суде, до того как обратиться с иском, он в обязательном порядке должен предъявить претензию (письменно предложить урегулировать конфликт добровольно).</li>\n<li>Получившая претензию Администрация обязана в течение 30 календарных дней с даты её получения письменно уведомить Пользователя о её рассмотрении и принятых мерах.</li>\n<li>Если обе стороны так и не смогли договориться, спор передаётся в судебный орган, где его должны рассмотреть согласно действующему российскому законодательству.</li>\n<li>Регулирование отношений Пользователя и Администрации сайта в Политике конфиденциальности проводится согласно действующему российскому законодательству.</li>\n</ol>\n</li>\n<li>ДОПОЛНИТЕЛЬНЫЕ УСЛОВИЯ\n<ol>\n<li>Администрация сайта вправе менять существующую на текущий момент Политику конфиденциальности, не спрашивая согласия у Пользователя.</li>\n<li>Вступление в силу новой Политики конфиденциальности начинается после того, как информация о ней будет выложена на сайт Интернет-магазина, если изменившаяся Политика не подразумевает иного варианта размещения.</li>\n<li> Все предложения, пожелания, требования или вопросы по настоящей Политике конфиденциальности следует сообщать в раздел обратной связи, расположенный по адресу: <strong>(ссылка)</strong>. Или путем отправки электронного письма по адресу <strong>(тут ваш email)</strong></li>\n<li>Прочитать о существующей Политике конфиденциальности можно, зайдя на страницу по <strong>адресу www.адрес магазина.ru</strong></li>\n</ol>\n</li>\n</ol>', '', '1', '1574373600', '', '', '1', '0', '', '', '', '1');
INSERT INTO phpshop_page VALUES ('44', 'Согласие на обработку персональных данных', 'soglasie_na_obrabotku_personalnyh_dannyh', '0', '', '', '<p>Согласие на обработку персональных данных</p>\r\n<p>Настоящим я, далее &laquo;Субъект Персональных Данных&raquo;, во исполнение требований Федерального закона от 27.07.2006 г. 152-ФЗ &laquo;О персональных данных&raquo; (с изменениями и дополнениями) свободно, своей волей и в своем интересе даю свое согласие&nbsp;<strong>Индивидуальном предпринимателю Иванову Ивану Ивановичу</strong>&nbsp;(далее &laquo;Интернет-магазин&raquo;, адрес:&nbsp;<strong>(тут ваш адрес)&nbsp;</strong>) на обработку своих персональных данных, указанных при регистрации путем заполнения веб-формы на сайте Интернет-магазина&nbsp;<strong>вашдомен.ру</strong>&nbsp;и его поддоменов&nbsp;<strong>*.вашдомен.ру</strong>&nbsp;(далее Сайт), направляемой (заполненной) с использованием Сайта.</p>\r\n<p>Под персональными данными я понимаю любую информацию, относящуюся ко мне как к Субъекту Персональных Данных, в том числе мои фамилию, имя, отчество, адрес, образование, профессию, контактные данные (телефон, факс, электронная почта, почтовый адрес), фотографии,&nbsp; иную другую информацию. Под обработкой персональных данных я понимаю сбор, систематизацию, накопление, уточнение, обновление, изменение, использование, распространение, передачу, в том числе трансграничную, обезличивание, блокирование, уничтожение, бессрочное хранение), и любые другие действия (операции) с персональными данными.</p>\r\n<p>Обработка персональных данных Субъекта Персональных Данных осуществляется исключительно в целях регистрации Субъекта Персональных Данных в базе данных Интернет-магазина с последующим направлением Субъекту Персональных Данных почтовых сообщений и смс-уведомлений, в том числе рекламного содержания, от Интернет-магазина, его аффилированных лиц и/или субподрядчиков, информационных и новостных рассылок,&nbsp; приглашений на мероприятия Интернет-магазина и другой информации рекламно-новостного содержания, а также с целью подтверждения личности Субъекта Персональных Данных при посещении мероприятий Интернет-магазина.</p>\r\n<p>Датой выдачи согласия на обработку персональных данных Субъекта Персональных Данных является дата отправки регистрационной веб-формы с Сайта Интернет-магазина.</p>\r\n<p>Обработка персональных данных Субъекта Персональных Данных может осуществляться с помощью средств автоматизации и/или без использования средств автоматизации в соответствии с действующим законодательством РФ и внутренними положениями Интернет-магазина.</p>\r\n<p>Интернет-магазин принимает необходимые правовые, организационные и технические меры или обеспечивает их принятие для защиты персональных данных от неправомерного или случайного доступа к ним, уничтожения, изменения, блокирования, копирования, предоставления, распространения персональных данных, а также от иных неправомерных действий в отношении персональных данных, а также принимает на себя обязательство сохранения конфиденциальности персональных данных Субъекта Персональных Данных. Интернет-магазин вправе привлекать для обработки персональных данных Субъекта Персональных Данных субподрядчиков, а также вправе передавать персональные данные для обработки своим аффилированным лицам, обеспечивая при этом принятие такими субподрядчиками и аффилированными лицами соответствующих обязательств в части конфиденциальности персональных данных.</p>\r\n<p>Я ознакомлен(а), что:</p>\r\n<ol>\r\n<li>настоящее согласие на обработку моих персональных данных, указанных при регистрации на Сайте Интернет-магазина, направляемых (заполненных) с использованием Cайта, действует в течение 20 (двадцати) лет с момента регистрации на Cайте Интернет-магазина;</li>\r\n<li>согласие может быть отозвано мною на основании письменного заявления в произвольной форме;</li>\r\n<li>предоставление персональных данных третьих лиц без их согласия влечет ответственность в соответствии с действующим законодательством Российской Федерации.</li>\r\n</ol>', '', '3', '1574373600', '', '', '1', '0', '', '', '', '1');
INSERT INTO phpshop_page VALUES ('45', 'Контакты', 'contacts', '0', '', '', '<div class="col-md-5 col-sm-5 col-xs-12">\r\n<ul class="contacts">\r\n<li class="flex-block"><span class="icons-map">&nbsp;</span><strong>Наш адрес: </strong> @streetAddress@</li>\r\n<li class="flex-block"><span class="icons-mail">&nbsp;</span><strong>Почта: </strong> <a href="mailto:@adminMail@">@adminMail@</a></li>\r\n<li class="flex-block"><span class="icons-call">&nbsp;</span><strong>Телефон: </strong> <a href="tel:@telNumMobile@">@telNumMobile@</a></li>\r\n</ul>\r\n<ul class="contacts">\r\n<li><span class="icons-clock">&nbsp;</span><strong>Мы работаем: </strong><br />\r\n<p>10:00 - 18:00 пнд-птн <br /> субб вскр - выходной</p>\r\n</li>\r\n</ul>\r\n<ul class="contacts">\r\n<li><span class="icons-location">&nbsp;</span><strong>Как нас найти: </strong><br />\r\n<p>Метро &laquo;Шоссе Энтузиастов&raquo;, первый вагон из центра. После эскалатора первый выход направо на шоссе Энтузиастов. По шоссе Энтузиастов в сторону области 10 минут пешком до здания с зеленой вывеской на первом этаже &laquo;Банк &laquo;Далена&raquo;. Вход со стороны банка. Далее звоните по контактным телефонам, чтобы вас встретили у входа.</p>\r\n</li>\r\n</ul>\r\n</div>\r\n<div class="col-md-7 col-sm-7 col-xs-12">@sticker_map@</div>', '', '1', '1557784800', '', '', '1', '0', '', '', '', '0');
INSERT INTO phpshop_page VALUES ('58', 'Ваша статья', 'vasha_statya', '19', '', '', '<p><em>Это раздел Веб-сайт - Страницы. Здесь вы можете заводить статьи или вести Блог.&nbsp; Вы можете прикреплять рекомендуемые товары в закладке Дополнительно, про которые рассказываете в статье. Страница попадет вниз сайта (в подвал), если вы поставите галку Главное меню в подвале в закладке Основное.</em></p>\r\n<p><img src="/UserFiles/Image/Trial/img33_66960s.png" alt="" width="300" height="300" /></p>\r\n<p class="text-justify">С другой стороны рамки и место обучения кадров играет важную роль в формировании соответствующий условий активизации. Не следует, однако забывать, что дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. С другой стороны рамки и место обучения кадров в значительной степени обуславливает создание направлений прогрессивного развития.</p>\r\n<p class="text-justify">Разнообразный и богатый опыт консультация с широким активом требуют определения и уточнения модели развития. Разнообразный и богатый опыт консультация с широким активом способствует подготовки и реализации существенных финансовых и административных условий. Равным образом начало повседневной работы по формированию позиции требуют определения и уточнения существенных финансовых и административных условий. Идейные соображения высшего порядка, а также новая модель организационной деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Задача организации, в особенности же рамки и место обучения кадров играет важную роль в формировании новых предложений.</p>\r\n<p class="text-justify">С другой стороны сложившаяся структура организации позволяет выполнять важные задания по разработке позиций, занимаемых участниками в отношении поставленных задач. Товарищи! постоянный количественный рост и сфера нашей активности позволяет оценить значение систем массового участия. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение новых предложений.</p>\r\n<p class="text-justify">Не следует, однако забывать, что постоянный количественный рост и сфера нашей активности способствует подготовки и реализации позиций, занимаемых участниками в отношении поставленных задач. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности требуют определения и уточнения позиций, занимаемых участниками в отношении поставленных задач. Задача организации, в особенности же постоянный количественный рост и сфера нашей активности играет важную роль в формировании соответствующий условий активизации. Товарищи! рамки и место обучения кадров в значительной степени обуславливает создание направлений прогрессивного развития. Задача организации, в особенности же постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании форм развития. Повседневная практика показывает, что рамки и место обучения кадров играет важную роль в формировании модели развития.</p>\r\n<p class="text-justify">С другой стороны укрепление и развитие структуры представляет собой интересный эксперимент проверки позиций, занимаемых участниками в отношении поставленных задач. Задача организации, в особенности же новая модель организационной деятельности требуют от нас анализа соответствующий условий активизации. Товарищи! реализация намеченных плановых заданий способствует подготовки и реализации позиций, занимаемых участниками в отношении поставленных задач. Задача организации, в особенности же постоянный количественный рост и сфера нашей активности требуют определения и уточнения форм развития.</p>\r\n<p class="text-justify">С другой стороны консультация с широким активом представляет собой интересный эксперимент проверки соответствующий условий активизации. Равным образом сложившаяся структура организации требуют от нас анализа системы обучения кадров, соответствует насущным потребностям. Равным образом дальнейшее развитие различных форм деятельности требуют определения и уточнения новых предложений. Задача организации, в особенности же сложившаяся структура организации требуют определения и уточнения позиций, занимаемых участниками в отношении поставленных задач. Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности требуют от нас анализа соответствующий условий активизации. Не следует, однако забывать, что укрепление и развитие структуры в значительной степени обуславливает создание новых предложений.</p>', '', '1', '1574460000', '11,1,2,3', '', '1', '0', '', '/UserFiles/Image/Trial/img32_33694s.jpg', '<p class="text-justify">С другой стороны рамки и место обучения кадров играет важную роль в формировании соответствующий условий активизации. Не следует, однако забывать, что дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. С другой стороны рамки и место обучения кадров в значительной степени обуславливает создание направлений прогрессивного развития.</p>\r\n<p class="text-justify">&nbsp;</p>', '1');
INSERT INTO phpshop_page VALUES ('59', 'Создавайте промо-акции', 'sozdavayte_promo-akcii', '19', '', '', '<p><em>Вы можете создавать промо-акции, делать скидки на товары в разделе Маркетинг - Промоакции.</em></p>\r\n<p class="text-justify">С другой стороны рамки и место обучения кадров играет важную роль в формировании соответствующий условий активизации. Не следует, однако забывать, что дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. С другой стороны рамки и место обучения кадров в значительной степени обуславливает создание направлений прогрессивного развития.</p>\r\n<p class="text-justify">Разнообразный и богатый опыт консультация с широким активом требуют определения и уточнения модели развития. Разнообразный и богатый опыт консультация с широким активом способствует подготовки и реализации существенных финансовых и административных условий. Равным образом начало повседневной работы по формированию позиции требуют определения и уточнения существенных финансовых и административных условий. Идейные соображения высшего порядка, а также новая модель организационной деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Задача организации, в особенности же рамки и место обучения кадров играет важную роль в формировании новых предложений.</p>\r\n<p class="text-justify">С другой стороны сложившаяся структура организации позволяет выполнять важные задания по разработке позиций, занимаемых участниками в отношении поставленных задач. Товарищи! постоянный количественный рост и сфера нашей активности позволяет оценить значение систем массового участия. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение новых предложений.</p>\r\n<p class="text-justify">Не следует, однако забывать, что постоянный количественный рост и сфера нашей активности способствует подготовки и реализации позиций, занимаемых участниками в отношении поставленных задач. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности требуют определения и уточнения позиций, занимаемых участниками в отношении поставленных задач. Задача организации, в особенности же постоянный количественный рост и сфера нашей активности играет важную роль в формировании соответствующий условий активизации. Товарищи! рамки и место обучения кадров в значительной степени обуславливает создание направлений прогрессивного развития. Задача организации, в особенности же постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании форм развития. Повседневная практика показывает, что рамки и место обучения кадров играет важную роль в формировании модели развития.</p>\r\n<p class="text-justify">С другой стороны укрепление и развитие структуры представляет собой интересный эксперимент проверки позиций, занимаемых участниками в отношении поставленных задач. Задача организации, в особенности же новая модель организационной деятельности требуют от нас анализа соответствующий условий активизации. Товарищи! реализация намеченных плановых заданий способствует подготовки и реализации позиций, занимаемых участниками в отношении поставленных задач. Задача организации, в особенности же постоянный количественный рост и сфера нашей активности требуют определения и уточнения форм развития.</p>\r\n<p class="text-justify">С другой стороны консультация с широким активом представляет собой интересный эксперимент проверки соответствующий условий активизации. Равным образом сложившаяся структура организации требуют от нас анализа системы обучения кадров, соответствует насущным потребностям. Равным образом дальнейшее развитие различных форм деятельности требуют определения и уточнения новых предложений. Задача организации, в особенности же сложившаяся структура организации требуют определения и уточнения позиций, занимаемых участниками в отношении поставленных задач. Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности требуют от нас анализа соответствующий условий активизации. Не следует, однако забывать, что укрепление и развитие структуры в значительной степени обуславливает создание новых предложений.</p>', '', '1', '1574460000', '37,36,35', '', '1', '0', '', '/UserFiles/Image/Trial/img17_95275s.jpg', '<p class="text-justify">С другой стороны рамки и место обучения кадров играет важную роль в формировании соответствующий условий активизации. Не следует, однако забывать, что дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. С другой стороны рамки и место обучения кадров в значительной степени обуславливает создание направлений прогрессивного развития.</p>', '0');
DROP TABLE IF EXISTS phpshop_page_categories;
CREATE TABLE phpshop_page_categories (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `num` int(64) DEFAULT '1',
  `parent_to` int(11) DEFAULT '0',
  `content` text,
  `servers` varchar(64) DEFAULT '',
  `menu` enum('0','1') DEFAULT '0',
  `page_cat_seo_name` varchar(255) DEFAULT '',
  `icon` VARCHAR(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `keywords` text,
  PRIMARY KEY (`id`),
  KEY `parent_to` (`parent_to`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_page_categories VALUES ('18', 'Органическая косметика', '1', '0', '', '', '0', 'organicheskaya-kosmetika','','','','');
INSERT INTO phpshop_page_categories VALUES ('19', 'Внимание, конкурс!', '1', '0', '<p>Это раздел редактирования каталога Статей. Сюда можно добавить описание, фото или видео. Каталог Статей можно вывести в Главное меню сайта, поставив галочку в закладке Основное. </p>\n<p><iframe src="https://www.youtube.com/embed/HnEDodW4SCE" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>', '', '0', 'vnimanie-konkurs','','','','');
INSERT INTO phpshop_page_categories VALUES ('16', 'Пример статьи', '1', '7', '<p>вамвамвам</p>', '', '0', 'primer-stati','','','','');
DROP TABLE IF EXISTS phpshop_parent_name;
CREATE TABLE phpshop_parent_name (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `enabled` enum('0','1') NOT NULL DEFAULT '1',
  `color` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_parent_name VALUES ('1', 'Габариты', '1','');
INSERT INTO phpshop_parent_name VALUES ('2', 'Цвет корпуса', '1','');
INSERT INTO phpshop_parent_name VALUES ('4', 'Размер', '1','');
INSERT INTO phpshop_parent_name VALUES ('5', 'Цвет', '1','');
INSERT INTO phpshop_parent_name VALUES ('6', 'Материал', '1','');
DROP TABLE IF EXISTS phpshop_payment;
CREATE TABLE phpshop_payment (
  `uid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `sum` float DEFAULT '0',
  `datas` int(11) DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `order` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_payment_systems;
CREATE TABLE phpshop_payment_systems (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `path` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '1',
  `num` tinyint(11) DEFAULT '0',
  `message` text,
  `message_header` text,
  `yur_data_flag` enum('0','1') DEFAULT '0',
  `icon` varchar(255) DEFAULT '',
  `color` varchar(64) DEFAULT '#000000',
  `servers` varchar(64) default '',
  `company` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_payment_systems VALUES ('1', 'Банковский перевод', 'bank', '1', '4', '<h3>Благодарим Вас за заказ!</h3>\r\n<p>Счет уже доступен в Вашем&nbsp;<a href="/users/order.html">личном кабинете</a>.&nbsp;</p>\r\n<p>Пароли доступа от личного кабинета находятся в Вашей почте.</p>', '', '1', '/UserFiles/Image/Payments/beznal.png', '#000000','','0');
INSERT INTO phpshop_payment_systems VALUES ('3', 'Наличная оплата', 'message', '1', '0', '<h3>Благодарим Вас за заказ!</h3>\r\n<p>В ближайшее время с Вами свяжется наш менеджер для уточнения деталей.</p>', '', '0', '/UserFiles/Image/Payments/nal.png', '#000000','','0');
DROP TABLE IF EXISTS phpshop_photo;
CREATE TABLE phpshop_photo (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '0',
  `name` varchar(64) DEFAULT '',
  `num` tinyint(11) DEFAULT '0',
  `info` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_photo_categories;
CREATE TABLE phpshop_photo_categories (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_to` int(11) DEFAULT '0',
  `link` varchar(64) DEFAULT '',
  `name` varchar(64) DEFAULT '',
  `num` tinyint(11) DEFAULT '0',
  `content` text,
  `enabled` enum('0','1') DEFAULT '0',
  `page` varchar(255) DEFAULT '',
  `count` tinyint(11) default 2,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_products;
CREATE TABLE IF NOT EXISTS `phpshop_products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `description` text,
  `content` text,
  `price` float DEFAULT '0',
  `price_n` float DEFAULT '0',
  `sklad` enum('0','1') DEFAULT '0',
  `p_enabled` enum('0','1') DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '1',
  `uid` varchar(64) DEFAULT '',
  `spec` enum('0','1') DEFAULT '0',
  `odnotip` varchar(64) DEFAULT '',
  `vendor` varchar(255) DEFAULT '',
  `vendor_array` blob,
  `yml` enum('0','1') DEFAULT '0',
  `num` int(11) DEFAULT '1',
  `newtip` enum('0','1') DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `title_enabled` enum('0','1','2') DEFAULT '0',
  `datas` int(11) DEFAULT '0',
  `page` varchar(255) DEFAULT '',
  `user` tinyint(11) DEFAULT '0',
  `descrip` varchar(255) DEFAULT '',
  `descrip_enabled` enum('0','1','2') DEFAULT '0',
  `title_shablon` varchar(255) DEFAULT '',
  `descrip_shablon` varchar(255) DEFAULT '',
  `keywords` varchar(255) DEFAULT '',
  `keywords_enabled` enum('0','1','2') DEFAULT '0',
  `keywords_shablon` varchar(255) DEFAULT '',
  `pic_small` varchar(255) DEFAULT '',
  `pic_big` varchar(255) DEFAULT '',
  `yml_bid_array` tinyblob,
  `parent_enabled` enum('0','1') DEFAULT '0',
  `parent` text,
  `items` int(11) DEFAULT '0',
  `weight` float DEFAULT '0',
  `price2` float DEFAULT '0',
  `price3` float DEFAULT '0',
  `price4` float DEFAULT '0',
  `price5` float DEFAULT '0',
  `files` text,
  `baseinputvaluta` int(11) DEFAULT '0',
  `ed_izm` varchar(255) DEFAULT '',
  `dop_cat` varchar(255) DEFAULT '',
  `rate` float unsigned DEFAULT '0',
  `rate_count` int(10) unsigned DEFAULT '0',
  `price_search` float DEFAULT '0',
  `parent2` text,
  `color` varchar(64) DEFAULT NULL,
  `productsgroup_check` enum('0','1') NOT NULL DEFAULT '0',
  `productsgroup_products` text NOT NULL,
  `vendor_code` varchar(255) DEFAULT '',
  `vendor_name` varchar(255) DEFAULT '',
  `productday` enum('0','1') DEFAULT '0',
  `hit` enum('0','1') DEFAULT '0',
  `option1` text,
  `option2` text,
  `option3` text,
  `option4` text,
  `option5` text,
  `prod_seo_name` varchar(255) DEFAULT '',
  `prod_seo_name_old` varchar(255) DEFAULT '',
  `manufacturer_warranty` enum('1','2') DEFAULT '2',
  `sales_notes` varchar(50) DEFAULT '',
  `country_of_origin` varchar(50) DEFAULT '',
  `adult` enum('1','2') DEFAULT '2',
  `delivery` enum('1','2') DEFAULT '1',
  `pickup` enum('1','2') DEFAULT '2',
  `store` enum('1','2') DEFAULT '2',
  `yandex_min_quantity` int(11) DEFAULT '0',
  `yandex_step_quantity` int(11) DEFAULT '0',
  `yandex_condition` enum('1','2','3') DEFAULT '1',
  `yandex_condition_reason` text,
  `length` varchar(64) default '',
  `width` varchar(64) default '',
  `height` varchar(64) default '',
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `enabled` (`enabled`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_products` (`id`, `category`, `name`, `description`, `content`, `price`, `price_n`, `sklad`, `p_enabled`, `enabled`, `uid`, `spec`, `odnotip`, `vendor`, `vendor_array`, `yml`, `num`, `newtip`, `title`, `title_enabled`, `datas`, `page`, `user`, `descrip`, `descrip_enabled`, `title_shablon`, `descrip_shablon`, `keywords`, `keywords_enabled`, `keywords_shablon`, `pic_small`, `pic_big`, `yml_bid_array`, `parent_enabled`, `parent`, `items`, `weight`, `price2`, `price3`, `price4`, `price5`, `files`, `baseinputvaluta`, `ed_izm`, `dop_cat`, `rate`, `rate_count`, `price_search`, `parent2`, `color`, `productsgroup_check`, `productsgroup_products`, `vendor_code`, `vendor_name`, `productday`, `hit`, `option1`, `option2`, `option3`, `option4`, `option5`, `prod_seo_name`, `prod_seo_name_old`, `manufacturer_warranty`, `sales_notes`, `country_of_origin`, `adult`, `delivery`, `pickup`, `store`, `yandex_min_quantity`, `yandex_step_quantity`, `yandex_condition`, `yandex_condition_reason`) VALUES
(1, 26, 'Джинсы 1001 Dressyy', '<p>Легкий, комфортный, не сковывает движения. Мягкая ткань дополнительный комфорт при носке, отводит лишнюю влагу во время тренировок, не раздражает кожу. Благодаря спортивной посадке идеально смотрится на теле. Легко стирается, не меняет форму после стирки.&nbsp;</p>', '<p>Длина изделия по спинке: 142,0см.<br />Длина изделия от линии талии: 120,0см.<br />Силуэт: А-силуэт<br />Покрой лифа: прилегающий<br />Покрой юбки: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 1200, 1350, '0', '1', '1', '3343460', '1', '25,26,24', 'i5-13ii3-6ii4-7ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223133223b7d693a333b613a313a7b693a303b733a313a2236223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574938130, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img1_69884s.jpg', '/UserFiles/Image/Trial/img1_69884.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '65,64,63,62', 11, 200, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '1', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'dzhinsy-1001-dressyy', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(2, 26, 'Джинсы Befreedom', '<p>Легкий, комфортный, не сковывает движения.&nbsp; Легко стирается, не меняет форму после стирки. Спортивный подчеркнет вашей фигуры.</p>', '<p>Длина изделия по спинке: 142,0см.<br />Длина изделия от линии талии: 120,0см.<br />Силуэт: А-силуэт<br />Покрой лифа: прилегающий<br />Покрой юбки: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 2000, 2500, '0', '1', '1', '234246246', '1', '18,17,16', 'i5-14ii3-5ii4-7ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223134223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574938122, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img2_44763s.jpg', '/UserFiles/Image/Trial/img2_44763.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '70,69,68,67,66', 46, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'dzhinsy-befreedom', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(3, 26, 'Джинсы Concepted Clubs', '<p>Легкий, комфортный, не сковывает движения. Мягкая ткань дополнительный комфорт при носке, отводит лишнюю влагу во время тренировок, не раздражает кожу. Благодаря спортивной посадке идеально смотрится на теле.&nbsp;</p>', '<p>Длина изделия по спинке: 142,0см.<br />Длина изделия от линии талии: 120,0см.<br />Силуэт: А-силуэт<br />Покрой лифа: прилегающий<br />Покрой юбки: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 3500, 4000, '0', '1', '1', '', '0', '23,25,24', 'i5-15ii3-4ii4-7ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223135223b7d693a333b613a313a7b693a303b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574938116, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img3_29892s.jpg', '/UserFiles/Image/Trial/img3_29892.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '76,75,74,73,72,71', 23, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'dzhinsy-concepted-clubs', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(4, 27, 'Джинсы Modizy', '<p>Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена.</p>', '<p>Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена.</p>', 5600, 6000, '0', '1', '1', '98769786', '0', '25,26', 'i5-19ii3-5ii4-8ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223139223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574937807, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img4_31272s.jpg', '/UserFiles/Image/Trial/img4_31272.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '108,107,106', 78, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'dzhinsy-modizy', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(5, 26, 'Брюки Oodjim Ultra', '<p>Легкий, комфортный, не сковывает движения. Мягкая ткань дополнительный комфорт при носке, отводит лишнюю влагу во время тренировок, не раздражает кожу. Спортивный подчеркнет вашей фигуры.</p>', '<p>&nbsp;Длина изделия по спинке: 142,0см.<br />Длина изделия от линии талии: 120,0см.<br />Силуэт: А-силуэт<br />Покрой лифа: прилегающий<br />Покрой юбки: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 3590, 0, '0', '1', '1', '345345', '0', '11,1,2', 'i5-12ii3-4ii4-7ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223132223b7d693a333b613a313a7b693a303b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574938144, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img5_43616s.jpg', '/UserFiles/Image/Trial/img5_43616.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '61,60,59', 18, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'bruki-oodjim-ultra', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(59, 26, 'Брюки Oodjim Ultra 37 ', NULL, NULL, 3590, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844717, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(6, 27, 'Брюки мужские Oliverty', '<p>Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа.&nbsp;</p>', '<p>Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена.</p>', 3000, 3700, '0', '1', '1', '8765865', '0', '20,4,21,8', 'i5-18ii3-5ii4-8ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223138223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574937794, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img6_51944s.jpg', '/UserFiles/Image/Trial/img6_51944.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '105,104,103,102', 100, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'bruki-muzhskie-oliverty', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(7, 27, 'Рубашка Oliverty', '<p>Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами.&nbsp;</p>', '<p>Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами.&nbsp;</p>', 2500, 3000, '0', '1', '1', '', '0', '23,25', 'i5-nullii3-nullii4-nullii2-nulli', 0x4e3b, '1', 0, '1', '', '0', 1574937873, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img7_34107s.jpg', '/UserFiles/Image/Trial/img7_34107.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '124,123,122', 3, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'rubashka-oliverty', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(8, 27, 'Рубашка KUSTANG', '<p>Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа.</p>', '<p>Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами.&nbsp;</p>', 6000, 0, '0', '1', '1', '9809876', '0', '23,25', 'i5-20ii3-5ii3-4ii4-8ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223230223b7d693a333b613a323a7b693a303b733a313a2235223b693a313b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574937859, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img8_54208s.jpg', '/UserFiles/Image/Trial/img8_54208.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '121,120,119', 88, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'rubashka-kustang', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(9, 26, 'Футболка Springfold', '<p>Мягкая ткань дополнительный комфорт при носке, отводит лишнюю влагу во время тренировок, не раздражает кожу. Благодаря спортивной посадке идеально смотрится на теле. Легко стирается, не меняет форму после стирки. Спортивный подчеркнет вашей фигуры.</p>', '<p>Длина изделия по спинке: 142,0см.<br />Длина изделия от линии талии: 120,0см.<br />Силуэт: А-силуэт<br />Покрой лифа: прилегающий<br />Покрой юбки: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 800, 0, '0', '1', '1', '0980987', '1', '19,18,17,16', 'i5-16ii3-5ii3-4ii4-7ii2-3ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223136223b7d693a333b613a323a7b693a303b733a313a2235223b693a313b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a323a7b693a303b733a313a2233223b693a313b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574938082, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img9_38664s.jpg', '/UserFiles/Image/Trial/img9_38664.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '93,92,91', 9, 120, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '1', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'futbolka-springfold', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(10, 26, 'Футболка Mangoff', '<p>Легкий, комфортный, не сковывает движения. Легко стирается, не меняет форму после стирки. Спортивный подчеркнет вашей фигуры.</p>', '<p>Длина изделия по спинке: 142,0см.<br />Длина изделия от линии талии: 120,0см.<br />Силуэт: А-силуэт<br />Покрой лифа: прилегающий<br />Покрой юбки: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 800, 0, '0', '1', '1', '455657', '1', '5,1,2,3', 'i5-11ii3-5ii3-4ii4-7ii2-3ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223131223b7d693a333b613a323a7b693a303b733a313a2235223b693a313b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a323a7b693a303b733a313a2233223b693a313b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574938095, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img10_90450s.jpg', '/UserFiles/Image/Trial/img10_90450.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '90,89,88,87,86', 30, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '1', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<p><small>Ссылка <strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и&nbsp;<small>productoption2.</small></small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'futbolka-mangoff', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL);
INSERT INTO `phpshop_products` (`id`, `category`, `name`, `description`, `content`, `price`, `price_n`, `sklad`, `p_enabled`, `enabled`, `uid`, `spec`, `odnotip`, `vendor`, `vendor_array`, `yml`, `num`, `newtip`, `title`, `title_enabled`, `datas`, `page`, `user`, `descrip`, `descrip_enabled`, `title_shablon`, `descrip_shablon`, `keywords`, `keywords_enabled`, `keywords_shablon`, `pic_small`, `pic_big`, `yml_bid_array`, `parent_enabled`, `parent`, `items`, `weight`, `price2`, `price3`, `price4`, `price5`, `files`, `baseinputvaluta`, `ed_izm`, `dop_cat`, `rate`, `rate_count`, `price_search`, `parent2`, `color`, `productsgroup_check`, `productsgroup_products`, `vendor_code`, `vendor_name`, `productday`, `hit`, `option1`, `option2`, `option3`, `option4`, `option5`, `prod_seo_name`, `prod_seo_name_old`, `manufacturer_warranty`, `sales_notes`, `country_of_origin`, `adult`, `delivery`, `pickup`, `store`, `yandex_min_quantity`, `yandex_step_quantity`, `yandex_condition`, `yandex_condition_reason`) VALUES
(11, 26, 'Брюки Mangoff', '<p>Практичные ботинки в ненастную зимнюю погоду. Объемный задний мягкий кант обеспечивает более плотную и удобную посадку на ноге. Одновременно кант блокирует попадание снега внутрь обуви. Сквозная прошивка борта подошвы обеспечивает долговечность в носке. Обладают водооталкивающим свойством для прогулок в дождливую и снежную погоду.</p>', '<p>Практичные ботинки в ненастную зимнюю погоду. Объемный задний мягкий кант обеспечивает более плотную и удобную посадку на ноге. Одновременно кант блокирует попадание снега внутрь обуви. Сквозная прошивка борта подошвы обеспечивает долговечность в носке. Обладают водооталкивающим свойством для прогулок в дождливую и снежную погоду.&nbsp;Практичные ботинки в ненастную зимнюю погоду. Объемный задний мягкий кант обеспечивает более плотную и удобную посадку на ноге. Одновременно кант блокирует попадание снега внутрь обуви. Сквозная прошивка борта подошвы обеспечивает долговечность в носке. Обладают водооталкивающим свойством для прогулок в дождливую и снежную погоду.</p>', 4500, 0, '0', '1', '1', '098099', '0', '21,30,8', 'i5-11ii3-5ii4-7ii2-3i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223131223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2233223b7d7d, '1', 0, '1', '', '0', 1574937974, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img11_84557s.jpg', '/UserFiles/Image/Trial/img11_84557.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '58,57,56,55,54,53', 21, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'bruki-mangoff', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(56, 26, 'Брюки Mangoff 38 белый', NULL, NULL, 4900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844276, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'белый', '#ffffff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(12, 26, 'Пуловер Springfold', '<p>Легкий, комфортный, не сковывает движения. Мягкая ткань дополнительный комфорт при носке, отводит лишнюю влагу во время тренировок, не раздражает кожу. Благодаря спортивной посадке идеально смотрится на теле. Легко стирается, не меняет форму после стирки. Спортивный подчеркнет вашей фигуры.</p>', '<p>Длина изделия по спинке: 142,0см.<br />Длина изделия от линии талии: 120,0см.<br />Силуэт: А-силуэт<br />Покрой лифа: прилегающий<br />Покрой юбки: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 3800, 0, '0', '1', '1', '987-987', '1', '15,19,18', 'i5-16ii3-4ii4-7ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223136223b7d693a333b613a313a7b693a303b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574938101, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img12_26952s.jpg', '/UserFiles/Image/Trial/img12_26952.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '85,84,83,82', 1, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '1', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'pulover-springfold', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(13, 26, 'Майка Oodjim', '<p>Легкий, комфортный, не сковывает движения. Мягкая ткань дополнительный комфорт при носке, отводит лишнюю влагу во время тренировок, не раздражает кожу. Благодаря спортивной посадке идеально смотрится на теле. Спортивный подчеркнет вашей фигуры.</p>', '<p>Покрой: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 600, 0, '0', '1', '1', '87686', '0', '6,4,21,30', 'i5-12ii3-4ii4-7ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223132223b7d693a333b613a313a7b693a303b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574938109, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img13_59800s.jpg', '/UserFiles/Image/Trial/img13_59800.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '81,79,78,77', 20, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'mayka-oodjim', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(14, 26, 'Юбка Mangoff', '<p>Легкий, комфортный, не сковывает движения. Мягкая ткань дополнительный комфорт при носке, отводит лишнюю влагу во время тренировок, не раздражает кожу. Благодаря спортивной посадке идеально смотрится на теле. Легко стирается, не меняет форму после стирки. Спортивный подчеркнет вашей фигуры.</p>', '<p>Длина изделия по спинке: 142,0см.<br />Длина изделия от линии талии: 120,0см.<br />Силуэт: А-силуэт<br />Покрой лифа: прилегающий<br />Покрой юбки: солнце<br />Материал верха: тафта<br />Материал юбки: тафта<br />Подклад: интерлок, по лифу до линии талии<br />Тип и расположение застежки: молния на спинке<br />Вид и расположение отделки и декоративных элементов: нет</p>', 5000, 5600, '0', '1', '1', '876876876', '1', '25,26,24', 'i5-11ii3-6ii3-5ii4-7ii2-1ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223131223b7d693a333b613a323a7b693a303b733a313a2236223b693a313b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a323a7b693a303b733a313a2231223b693a313b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574938074, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img14_84532s.jpg', '/UserFiles/Image/Trial/img14_84532.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '97,96,95,94', 70, 400, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '<p>&nbsp;</p>\r\n<table width="100%" align="center">\r\n<thead>\r\n<tr class="tr_head">\r\n<td style="width: 20%;">\r\n<div>Российский размер</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Размер производителя</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват талии, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Обхват бедер, в см</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>Рост, см</div>\r\n</td>\r\n</tr>\r\n</thead>\r\n</table>\r\n<div>\r\n<div style="height: auto; margin-bottom: 0px; margin-right: 0px;">\r\n<table width="100%" align="center">\r\n<tbody>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>24</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-62</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>40/42-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>25</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>58-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>86-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42/44-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>27</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>42-170</div>\r\n</td>\r\n<td>\r\n<div>26</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>62-66</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>90-94</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44/46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>29</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>44-170</div>\r\n</td>\r\n<td>\r\n<div>28</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>66-70</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>94-98</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46/48-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>31</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>46-170</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>30</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>70-74</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>98-102</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="width: 20%;">\r\n<div>48-170</div>\r\n</td>\r\n<td>\r\n<div>32</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>74-78</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>102-104</div>\r\n</td>\r\n<td style="width: 20%;">\r\n<div>170</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><small>Ссылка в карточке товара&nbsp;<strong>Таблица размеров</strong> редактируется в меню Модули - Стикеры: <strong>выключите стикер, и надпись пропадет.</strong> Содержание <strong>всплывающего окна</strong> редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>\r\n</div>\r\n</div>', '<p><small>Ссылка <strong>Информация о доставке</strong> редактируется в меню Модули - Стикеры: выключите стикер, и надпись пропадет. Содержание всплывающего окна редактируется отдельно для каждого товара: закладка Дополнительно, которая включается модулем Product Option. Можно редактировать пакетно через меню База - Экспорт базы, выбрав поля productoption1 и productoption2.</small></p>', NULL, NULL, NULL, 'ubka-mangoff', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(15, 28, 'Очки Mangoff', '<p>Модная выполнена из экокожи ярких расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию. В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см.</p>', '<p>Модная выполнена из ярких расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию. В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см.&nbsp;Модная выполнена из ярких расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию. В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см.</p>', 4500, 4700, '0', '1', '1', '9886876', '0', '', 'i5-11ii3-5ii4-7ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223131223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574938243, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img15_92639s.jpg', '/UserFiles/Image/Trial/img15_92639.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '140,139', 27, 120, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'ochki-mangoff', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(16, 28, 'Ремень Oodjim', '<p>Модная выполнена из экокожи ярких расцветок и прекрасно подходит для ежедневного использования. Закрываются на пластиковую молнию. В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече.&nbsp;</p>', '<p>В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см. Модная выполнена из экокожи ярких расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию.</p>', 1290, 0, '0', '1', '1', '987987', '0', '', 'i5-12ii3-5ii4-7ii4-8ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223132223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a323a7b693a303b733a313a2237223b693a313b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574938302, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img16_92849s.jpg', '/UserFiles/Image/Trial/img16_92849.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 56, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'remen-oodjim', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(17, 28, 'Платок Springfold', '<p>Модная выполнена из экокожи ярких расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию. В комплекте к сумочке идет съемный, ремень геральдической расцветки.&nbsp;</p>', '<p>Расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию. В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см.</p>', 2590, 0, '1', '1', '1', '876765', '0', '', 'i5-16ii3-5ii4-7ii2-3ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223136223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a323a7b693a303b733a313a2233223b693a313b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574938282, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img17_22660s.jpg', '/UserFiles/Image/Trial/img17_22660.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 4, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'platok-springfold', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(18, 28, 'Очки Springfold', '<p>В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см.</p>', '<p>Модная выполнена из экокожи ярких расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию. В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см.</p>', 3590, 0, '0', '1', '1', '876876', '0', '', 'i5-16ii3-6ii3-5ii4-7ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223136223b7d693a333b613a323a7b693a303b733a313a2236223b693a313b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574938267, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img18_49453s.jpg', '/UserFiles/Image/Trial/img18_49453.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '141', 87, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'ochki-springfold', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(19, 28, 'Очки Oodjim', '<p>Модная выполнена из экокожи ярких расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию.&nbsp;</p>', '<p>В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см.&nbsp;Модная выполнена из ярких расцветок и прекрасно подходит для ежедневного использования. Модель состоит из двух отделений, которые закрываются на пластиковую молнию. В комплекте к сумочке идет съемный, ремень геральдической расцветки. Клатч можно носить в руках и на плече. Максимальная плечевого ремня 79 см.</p>', 3000, 0, '1', '1', '1', '876876', '0', '', 'i5-12ii3-6ii3-5ii4-7ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223132223b7d693a333b613a323a7b693a303b733a313a2236223b693a313b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2237223b7d693a323b613a313a7b693a303b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574938258, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img19_68698s.jpg', '/UserFiles/Image/Trial/img19_68698.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 87, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'ochki-oodjim', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(20, 27, 'Polo Crocsby', '<p>Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой.&nbsp;</p>', '<p>Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена.</p>', 4500, 4700, '0', '1', '1', '0987087', '0', '25,26,24', 'i5-17ii3-5ii4-8ii2-3ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223137223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a323a7b693a303b733a313a2233223b693a313b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574937782, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img20_30838s.jpg', '/UserFiles/Image/Trial/img20_30838.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '101,100', 87, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'polo-crocsby', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(21, 27, 'Пуловер Kustang', '<p>Для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. </p>', '<p>Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. </p>', 6700, 11000, '0', '1', '1', '34435', '0', '23,25,26,24', 'i5-nullii3-nullii4-nullii2-nulli', 0x613a343a7b693a353b613a313a7b693a303b733a343a226e756c6c223b7d693a333b613a313a7b693a303b733a343a226e756c6c223b7d693a343b613a313a7b693a303b733a343a226e756c6c223b7d693a323b613a313a7b693a303b733a343a226e756c6c223b7d7d, '1', 0, '1', '', '0', 1574969530, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img21_18874s.jpg', '/UserFiles/Image/Trial/img21_18874.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '115,114,113', 9, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'pulover-kustang', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(22, 27, 'Рубашка Polambia', '<p>Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани.&nbsp;</p>', '<p>Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами.&nbsp;</p>', 4690, 7000, '0', '1', '1', '98987', '0', '23,25', 'i5-21ii3-5ii4-8ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223231223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574937888, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img22_11677s.jpg', '/UserFiles/Image/Trial/img22_11677.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '126,125', 45, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'rubashka-polambia', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(23, 30, 'Кеды Abibas', '<p>Практичные ботинки в ненастную зимнюю погоду. Объемный задний мягкий кант обеспечивает более плотную и удобную посадку на ноге. Одновременно кант блокирует попадание снега внутрь обуви. Сквозная прошивка борта подошвы обеспечивает долговечность в носке. Обладают водооталкивающим свойством для прогулок в дождливую и снежную погоду.</p>', '<p>Увеличенная жесткость ботинка<br />Комбинированный тканевый верх ботинка из нейлона<br />Мягкие вкладыши из EVA-пены<br />Носок из высокопрочного пластика (PVC)<br />Язык из войлока толщиной 5 мм<br />Широкий размерный ряд<br />Основные характеристики:</p>', 4500, 4900, '0', '1', '1', '8776987', '0', '4,29,23', 'i3-4ii4-8ii2-2i', 0x613a333a7b693a333b613a313a7b693a303b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574949962, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img23_77945s.jpg', '/UserFiles/Image/Trial/img23_77945.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '133,132,131', 17, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'kedy-abibas', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(24, 30, 'Слипоны Luma', '<p>Объемный задний мягкий кант обеспечивает более плотную и удобную посадку на ноге. Одновременно кант блокирует попадание снега внутрь обуви. Сквозная прошивка борта подошвы обеспечивает долговечность в носке. Обладают водооталкивающим свойством для прогулок в дождливую и снежную погоду.</p>', '<p>Увеличенная жесткость ботинка<br />Комбинированный тканевый верх ботинка из нейлона<br />Мягкие вкладыши из EVA-пены<br />Носок из высокопрочного пластика (PVC)<br />Язык из войлока толщиной 5 мм<br />Широкий размерный ряд<br />Основные характеристики:</p>', 6700, 0, '0', '1', '1', '96759765', '0', '33,32', 'i3-5ii3-4ii4-8ii2-2i', 0x613a333a7b693a333b613a323a7b693a303b733a313a2235223b693a313b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574949973, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img24_36368s.jpg', '/UserFiles/Image/Trial/img24_36368.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '138,137,136', 14, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'slipony-luma', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL);
INSERT INTO `phpshop_products` (`id`, `category`, `name`, `description`, `content`, `price`, `price_n`, `sklad`, `p_enabled`, `enabled`, `uid`, `spec`, `odnotip`, `vendor`, `vendor_array`, `yml`, `num`, `newtip`, `title`, `title_enabled`, `datas`, `page`, `user`, `descrip`, `descrip_enabled`, `title_shablon`, `descrip_shablon`, `keywords`, `keywords_enabled`, `keywords_shablon`, `pic_small`, `pic_big`, `yml_bid_array`, `parent_enabled`, `parent`, `items`, `weight`, `price2`, `price3`, `price4`, `price5`, `files`, `baseinputvaluta`, `ed_izm`, `dop_cat`, `rate`, `rate_count`, `price_search`, `parent2`, `color`, `productsgroup_check`, `productsgroup_products`, `vendor_code`, `vendor_name`, `productday`, `hit`, `option1`, `option2`, `option3`, `option4`, `option5`, `prod_seo_name`, `prod_seo_name_old`, `manufacturer_warranty`, `sales_notes`, `country_of_origin`, `adult`, `delivery`, `pickup`, `store`, `yandex_min_quantity`, `yandex_step_quantity`, `yandex_condition`, `yandex_condition_reason`) VALUES
(25, 30, 'Кеды Gans', '<p>Практичные ботинки в ненастную зимнюю погоду. Объемный задний мягкий кант обеспечивает более плотную и удобную посадку на ноге. Одновременно кант блокирует попадание снега внутрь обуви. Сквозная прошивка борта подошвы обеспечивает долговечность в носке. Обладают водооталкивающим свойством для прогулок в дождливую и снежную погоду.</p>', '<p>Увеличенная жесткость ботинка<br />Комбинированный тканевый верх ботинка из нейлона<br />Мягкие вкладыши из EVA-пены<br />Носок из высокопрочного пластика (PVC)<br />Язык из войлока толщиной 5 мм<br />Широкий размерный ряд<br />Основные характеристики:</p>', 8000, 11000, '0', '1', '1', '98769876', '0', '4,29,30,22', 'i3-5ii4-8ii2-3ii2-1i', 0x613a333a7b693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a323a7b693a303b733a313a2233223b693a313b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574949948, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img25_67891s.jpg', '/UserFiles/Image/Trial/img25_67891.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '130,129,128', 100, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'kedy-gans', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(26, 30, 'Слипоны Gans', '<p>Практичные ботинки в ненастную зимнюю погоду. Объемный задний мягкий кант обеспечивает более плотную и удобную посадку на ноге. Одновременно кант блокирует попадание снега внутрь обуви. Сквозная прошивка борта подошвы обеспечивает долговечность в носке.&nbsp;</p>', '<p>Увеличенная жесткость ботинка<br />Комбинированный тканевый верх ботинка из нейлона<br />Мягкие вкладыши из EVA-пены<br />Носок из высокопрочного пластика (PVC)<br />Язык из войлока толщиной 5 мм<br />Широкий размерный ряд<br />Основные характеристики:</p>', 8000, 12200, '0', '1', '1', '09878976', '0', '4,29,23,22', 'i3-5ii4-8ii2-2i', 0x613a333a7b693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574949968, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img26_58467s.jpg', '/UserFiles/Image/Trial/img26_58467.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '135,134', 74, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'slipony-gans', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(27, 31, 'Сумка Polambia', '<p>Сквозная прошивка борта подошвы обеспечивает долговечность в носке. Обладают водооталкивающим свойством для прогулок в дождливую и снежную погоду.</p>', '<p>Практичные ботинки в ненастную зимнюю погоду. Объемный задний мягкий кант обеспечивает более плотную и удобную посадку на ноге. Одновременно кант блокирует попадание снега внутрь обуви. Сквозная прошивка борта подошвы обеспечивает долговечность в носке. Обладают водооталкивающим свойством для прогулок в дождливую и снежную погоду.</p>', 4500, 7800, '1', '1', '1', '976976', '0', '23,25', 'i5-21ii3-5ii3-4ii4-8ii2-1ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223231223b7d693a333b613a323a7b693a303b733a313a2235223b693a313b733a313a2234223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a323a7b693a303b733a313a2231223b693a313b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574937652, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img27_71254s.jpg', '/UserFiles/Image/Trial/img27_71254.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 68, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'sumka-polambia', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(28, 27, 'Рубашка Polambia', '<p>Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа.&nbsp;</p>', '<p>Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами.&nbsp;</p>', 4500, 4900, '0', '1', '1', '87687659', '0', '23,25,27', 'i5-21ii3-5ii4-8ii2-1ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223231223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a323a7b693a303b733a313a2231223b693a313b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574937904, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img28_10558s.jpg', '/UserFiles/Image/Trial/img28_10558.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '127', 13, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'rubashka-polambia', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(29, 27, 'Поло S.Oliverty', '<p>Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами.&nbsp;</p>', '<p>Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами.&nbsp;</p>', 3590, 4300, '0', '1', '1', '3434521', '0', '25,26,24', 'i5-18ii3-5ii4-8ii2-2i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223138223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1574937825, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img29_65387s.jpg', '/UserFiles/Image/Trial/img29_65387.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '112,111,110,109', 13, 300, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'polo-s-oliverty', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(30, 27, 'Пуловер Oliverty', '<p>Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Свитшот из лимитированной коллекции из мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена.</p>', '<p>Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Подарком как для фанатов спортсмена так и для людей, увлекающихся единоборствами. Мужские спортивные, выполненные из мягкой, тактильно приятной ткани. Прямая модель зауженная к низу, пояс с резинкой. Идеально подойдут для создания повседневного и спортивного образа. Мягкого, дышащего хлопка, отлично подойдет для активного отдыха и спорта. Носишь качественную и эксклюзивную вещь с изображением любимого спортсмена.</p>', 8000, 13000, '0', '1', '1', '5435423', '0', '4,29,8', 'i5-18ii3-5ii4-8ii2-3ii2-1i', 0x613a343a7b693a353b613a313a7b693a303b733a323a223138223b7d693a333b613a313a7b693a303b733a313a2235223b7d693a343b613a313a7b693a303b733a313a2238223b7d693a323b613a323a7b693a303b733a313a2233223b693a313b733a313a2231223b7d7d, '1', 0, '1', '', '0', 1574937843, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img30_85931s.jpg', '/UserFiles/Image/Trial/img30_85931.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '118,117,116', 67, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'pulover-oliverty', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(31, 11, 'Помада Zivage', '<p>Способ применения: Способ применения: Наносите дезодорант ежедневно после душа, на сухую кожу. Не рекомендуется использовать на поврежденной коже и при наличии явных воспалений.</p>', '<p>Особенности состава: Без аммиака, Без дибутилфталата, Без камфоры, Без сульфатов, Без спирта, Без толуола, Без формальдегда, Без фтора, Гипоаллергенная, Не тестируется на животных, Подходит для ежедневного применения, Проверено дерматологамиСостав: Соль Мертвого моря, жемчужный порошок, экстракт граната, экстракт риса</p>', 600, 0, '0', '1', '1', '323255432', '1', '37,35', 'i8-25ii7-22ii7-46ii9-29i', 0x613a333a7b693a383b613a313a7b693a303b733a323a223235223b7d693a373b613a323a7b693a303b733a323a223232223b693a313b733a323a223436223b7d693a393b613a313a7b693a303b733a323a223239223b7d7d, '1', 0, '1', '', '0', 1574792958, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img31_95215s.jpg', '/UserFiles/Image/Trial/img31_95215.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 43, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'pomada-zivage', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(32, 11, 'Блеск для губ Zivage', '<p>Благодаря отсутствию синтетических химических веществ не вызывает негативных кожных реакций, за исключением случаев индивидуальной непереносимости.</p>', '<p>Способ применения: Способ применения: Наносите дезодорант ежедневно после душа, на сухую кожу. Не рекомендуется использовать на поврежденной коже и при наличии явных воспалений.Особенности состава: Без аммиака, Без дибутилфталата, Без камфоры, Без сульфатов, Без спирта, Без толуола, Без формальдегда, Без фтора, Гипоаллергенная, Не тестируется на животных, Подходит для ежедневного применения, Проверено дерматологамиСостав: Соль Мертвого моря, жемчужный порошок, экстракт граната, экстракт риса</p>', 230, 450, '0', '1', '1', '656754', '0', '37,36,35', 'i8-25ii7-22ii7-24ii7-46ii9-31i', 0x613a333a7b693a383b613a313a7b693a303b733a323a223235223b7d693a373b613a333a7b693a303b733a323a223232223b693a313b733a323a223234223b693a323b733a323a223436223b7d693a393b613a313a7b693a303b733a323a223331223b7d7d, '1', 0, '1', '', '0', 1574792928, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img32_33653s.jpg', '/UserFiles/Image/Trial/img32_33653.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 77, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'blesk-dlya-gub-zivage', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(33, 11, 'Палетка теней Zivage', '<p>Особенности состава: Без аммиака, Без дибутилфталата, Без камфоры, Без сульфатов, Без спирта, Без толуола, Без формальдегда, Без фтора, Гипоаллергенная, Не тестируется на животных, Подходит для ежедневного применения, Проверено дерматологамиСостав: Соль Мертвого моря, жемчужный порошок, экстракт граната, экстракт риса</p>', '<p>Способ применения: Способ применения: Наносите дезодорант ежедневно после душа, на сухую кожу. Не рекомендуется использовать на поврежденной коже и при наличии явных воспалений.Особенности состава: Без аммиака, Без дибутилфталата, Без камфоры, Без сульфатов, Без спирта, Без толуола, Без формальдегда, Без фтора, Гипоаллергенная, Не тестируется на животных, Подходит для ежедневного применения, Проверено дерматологамиСостав: Соль Мертвого моря, жемчужный порошок, экстракт граната, экстракт риса</p>', 1200, 1300, '0', '1', '1', '6546543', '0', '37,35', 'i8-26ii7-24ii7-46ii9-31ii9-32i', 0x613a333a7b693a383b613a313a7b693a303b733a323a223236223b7d693a373b613a323a7b693a303b733a323a223234223b693a313b733a323a223436223b7d693a393b613a323a7b693a303b733a323a223331223b693a313b733a323a223332223b7d7d, '1', 0, '1', '', '0', 1574792945, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img33_66960s.png', '/UserFiles/Image/Trial/img33_66960.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 8, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'paletka-teney-zivage', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(34, 11, 'Пудра Saybelline', '<p>Благодаря отсутствию синтетических химических веществ не вызывает негативных кожных реакций, за исключением случаев индивидуальной непереносимости.</p>', '<p>Благодаря отсутствию синтетических химических веществ не вызывает негативных кожных реакций, за исключением случаев индивидуальной непереносимости.</p>\r\n<p>Способ применения: Способ применения: Наносите дезодорант ежедневно после душа, на сухую кожу. Не рекомендуется использовать на поврежденной коже и при наличии явных воспалений.Особенности состава: Без аммиака, Без дибутилфталата, Без камфоры, Без сульфатов, Без спирта, Без толуола, Без формальдегда, Без фтора, Гипоаллергенная, Не тестируется на животных, Подходит для ежедневного применения, Проверено дерматологамиСостав: Соль Мертвого моря, жемчужный порошок, экстракт граната, экстракт риса</p>', 5000, 0, '0', '1', '1', '123245', '1', '37,32', 'i8-25ii7-23ii7-24ii9-31ii9-29i', 0x613a333a7b693a383b613a313a7b693a303b733a323a223235223b7d693a373b613a323a7b693a303b733a323a223233223b693a313b733a323a223234223b7d693a393b613a323a7b693a303b733a323a223331223b693a313b733a323a223239223b7d7d, '1', 0, '1', '', '0', 1574792974, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img34_27336s.png', '/UserFiles/Image/Trial/img34_27336.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 44, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'pudra-saybelline', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(35, 12, 'Сыворотка для сухой кожи Clatins', '<p>Дезодорант от подарит ощущение свежести на 24 часа.</p>', '<p>Дезодорант от подарит ощущение свежести на 24 часа. Впитывается моментально, не оставляет следов на одежде, успокаивает кожу и регулирует потоотделение. Формула средства обогащена жемчужным порошком и экстрактом водорослей, позволяет защитить кожу от неприятного запаха, устранить бактерии, вызывающие запах пота и способствующие возникновению воспалений. Без парабенов и алюминия.</p>', 4500, 4900, '0', '1', '1', '443543', '0', '37,34', 'i8-25ii7-22ii7-46ii9-29i', 0x613a333a7b693a383b613a313a7b693a303b733a323a223235223b7d693a373b613a323a7b693a303b733a323a223232223b693a313b733a323a223436223b7d693a393b613a313a7b693a303b733a323a223239223b7d7d, '1', 0, '1', '', '0', 1574792990, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img35_18528s.jpg', '/UserFiles/Image/Trial/img35_18528.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 12, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'syvorotka-dlya-suhoy-kozhi-clatins', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(36, 12, 'Молочко Clatins', '<p>Впитывается моментально, не оставляет следов на одежде, успокаивает кожу и регулирует потоотделение.&nbsp;</p>', '<p>Формула средства обогащена жемчужным порошком и экстрактом водорослей, позволяет защитить кожу от неприятного запаха, устранить бактерии, вызывающие запах пота и способствующие возникновению воспалений. Без парабенов и алюминия.</p>', 2300, 2900, '0', '1', '1', '3434454', '0', '37,35', '', 0x4e3b, '1', 0, '1', '', '0', 1574719456, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img36_99871s.jpg', '/UserFiles/Image/Trial/img36_99871.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 15, 100, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(37, 12, 'Крем для рук Saybelline', '<p>Дезодорант устранит бактерии, вызывающие запах пота и способствующие возникновению воспалений. Без парабенов и алюминия.</p>', '<p>Дезодорант от подарит ощущение свежести на 24 часа. Впитывается моментально, не оставляет следов на одежде, успокаивает кожу и регулирует потоотделение. Формула средства обогащена жемчужным порошком и экстрактом водорослей, позволяет защитить кожу от неприятного запаха, устранить бактерии, вызывающие запах пота и способствующие возникновению воспалений. Без парабенов и алюминия.</p>', 2450, 0, '0', '1', '1', '5456433', '0', '37,35', 'i7-22ii7-46ii9-30ii8-47i', 0x613a333a7b693a373b613a323a7b693a303b733a323a223232223b693a313b733a323a223436223b7d693a393b613a313a7b693a303b733a323a223330223b7d693a383b613a313a7b693a303b693a34373b7d7d, '1', 0, '1', '', '0', 1574793020, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img37_82187s.jpg', '/UserFiles/Image/Trial/img37_82187.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 12, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'krem-dlya-ruk-saybelline', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(83, 26, 'Пуловер Springfold 38 ', NULL, NULL, 3800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846478, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(84, 26, 'Пуловер Springfold 39 ', NULL, NULL, 3800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846481, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(85, 26, 'Пуловер Springfold 40 ', NULL, NULL, 3800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846484, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '40', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(86, 26, 'Футболка Mangoff 37 песочный', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846566, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '37', -2, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'бледно-розовый', '#fcdae7', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(88, 26, 'Футболка Mangoff 37 голубой', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846576, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'голубой', '#b6f7f7', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(39, 3, 'Загадки инопланетян  Фтовелл Муи', '<p>Но насколько близки к реальности наши представления об эпохе, на которую опирается вся западная цивилизация?</p>', '<p>Мы встречаемся с образами и историей Древнего Рима в науке, литературе, искусстве. Но насколько близки к реальности наши представления об эпохе, на которую опирается вся западная цивилизация? Ведущий мировой специалист по древней истории в своей книге объясняет, почему нам так важна римская история, каким образом маленький, ничем не примечательный городок Центральной Италии превратился в империю трех континентов.</p>', 900, 0, '0', '1', '1', '87658764', '0', '41,42', '', 0x4e3b, '1', 0, '1', '', '0', 1574719520, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img39_63648s.jpg', '/UserFiles/Image/Trial/img39_63648.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 118, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(40, 3, 'Презент маме. Вдохновляющее лидерство (комплект из 2 книг)', '<p>Но насколько близки к реальности наши представления об эпохе, на которую опирается вся западная цивилизация?</p>', '<p>Благодаря далекое прошлое кажется живым и увлекательным. Она обладает удивительной способностью убеждать, что античность - это стоящая тема для дискуссий.</p>', 1450, 0, '0', '1', '1', '2452456', '0', '41,42', '', 0x4e3b, '1', 0, '1', '', '0', 1574719527, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img40_16081s.jpg', '/UserFiles/Image/Trial/img40_16081.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 56, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(41, 3, 'За сказочной стеной. Горячее сердце 2. Волшебные истории', '<div class="br7" data-v-c66bfbbc="">Книги по истории Древнего Рима или пугают непривычного читателя шеренгами незнакомых лиц, понятий и мест, или все упрощают.</div>\r\n<div class="br6" data-v-c66bfbbc="">&nbsp;</div>', '<p>Книги по истории Древнего Рима или пугают непривычного читателя шеренгами незнакомых лиц, понятий и мест, или все упрощают. Предлагает иной подход: мы быстро погружаемся в увлекательную историю, удаленную от нас на 2000 лет и больше; мы многого про нее не знаем, но все равно на ней построены почти все современные политические системы мира. Знакомство не будет простым, но без него обойтись нельзя: не зная ничего о Древнем Риме, мы не поймем современность.</p>', 4590, 0, '0', '1', '1', '3535656', '0', '39,40,42', '', 0x4e3b, '1', 0, '1', '', '0', 1574719506, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img41_23080s.jpg', '/UserFiles/Image/Trial/img41_23080.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 344, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(42, 3, 'Что такое слон. 2 кубических метра, которые диктуют, как нам спать', '<p>Она обладает удивительной способностью убеждать, что античность - это стоящая тема для дискуссий.</p>', '<p>Книги по истории Древнего Рима или пугают непривычного читателя шеренгами незнакомых лиц, понятий и мест, или все упрощают. Предлагает иной подход: мы быстро погружаемся в увлекательную историю, удаленную от нас на 2000 лет и больше; мы многого про нее не знаем, но все равно на ней построены почти все современные политические системы мира. Знакомство не будет простым, но без него обойтись нельзя: не зная ничего о Древнем Риме, мы не поймем современность.</p>', 3590, 0, '0', '1', '1', '978697', '0', '39,41', '', 0x4e3b, '1', 0, '1', '', '0', 1574719534, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img42_77115s.jpg', '/UserFiles/Image/Trial/img42_77115.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 79, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(43, 3, '451° по Цельсию Крэдбери Муглас', '<p>Знакомство не будет простым, но без него обойтись нельзя: не зная ничего о Древнем Риме, мы не поймем современность.</p>', '<p>Книги по истории Древнего Рима или пугают непривычного читателя шеренгами незнакомых лиц, понятий и мест, или все упрощают. Предлагает иной подход: мы быстро погружаемся в увлекательную историю, удаленную от нас на 2000 лет и больше; мы многого про нее не знаем, но все равно на ней построены почти все современные политические системы мира. Знакомство не будет простым, но без него обойтись нельзя: не зная ничего о Древнем Риме, мы не поймем современность.</p>', 800, 0, '0', '1', '1', '97659765', '0', '42,43', '', 0x4e3b, '1', 0, '1', '', '0', 1574719513, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img43_24630s.jpg', '/UserFiles/Image/Trial/img43_24630.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 90, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(44, 14, 'Набор инструментов для авто и дома TEKOV DMT', '<p>Вытяжка, выполненная из стекла это особенная деталь кухонного интерьера, которая не только выполняет свои прямые функциональные обязанности, но и привносит в дизайн помещения особую эстетику, проявляющуюся в оригинальной форме и исходном материале высокого качества. Вытяжки из стекла очень удобны в очистке.</p>', '<p>Сочетание стекла, стали и прямых, горизонтальных линий, используемых в кухонных вытяжках современного дизайна, объединяет внешний вид кухонной мебели и бытовой техники, создавая полноценное решение в стиле хай-тек.<br />Фронатальная панель из стекла<br />Вытяжка, выполненная из стекла это особенная деталь кухонного интерьера, которая не только выполняет свои прямые функциональные обязанности, но и привносит в дизайн помещения особую эстетику, проявляющуюся в оригинальной форме и исходном материале высокого качества. Вытяжки из стекла очень удобны в очистке.</p>', 2380, 0, '0', '1', '1', '98769875', '0', '49,44', 'i11-36ii12-39ii12-40ii12-45ii13-42i', 0x613a333a7b693a31313b613a313a7b693a303b733a323a223336223b7d693a31323b613a333a7b693a303b733a323a223339223b693a313b733a323a223430223b693a323b733a323a223435223b7d693a31333b613a313a7b693a303b733a323a223432223b7d7d, '1', 0, '1', '', '0', 1574793192, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img44_23523s.jpg', '/UserFiles/Image/Trial/img44_23523.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 8, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '#15#16#', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'nabor-instrumentov-dlya-avto-i-doma-tekov-dmt', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(45, 14, 'Лобзик аккумуляторный Gosch без АКБ и ЗУ', '<p>Данная вытяжка разработана из качественных материалов и с использованием самых современных технологий, что позволяет снизить уровень шума, издаваемый вытяжкой в процессе работы.</p>', '<p>Сочетание стекла, стали и прямых, горизонтальных линий, используемых в кухонных вытяжках современного дизайна, объединяет внешний вид кухонной мебели и бытовой техники, создавая полноценное решение в стиле хай-тек.<br />Фронатальная панель из стекла<br />Вытяжка, выполненная из стекла это особенная деталь кухонного интерьера, которая не только выполняет свои прямые функциональные обязанности, но и привносит в дизайн помещения особую эстетику, проявляющуюся в оригинальной форме и исходном материале высокого качества. Вытяжки из стекла очень удобны в очистке.</p>', 8000, 0, '0', '1', '1', '34345', '0', '47,51,44,49', 'i11-35ii11-33ii12-38ii13-42i', 0x613a333a7b693a31313b613a323a7b693a303b733a323a223335223b693a313b733a323a223333223b7d693a31323b613a313a7b693a303b733a323a223338223b7d693a31333b613a313a7b693a303b733a323a223432223b7d7d, '1', 0, '1', '', '0', 1574793136, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img45_37544s.jpg', '/UserFiles/Image/Trial/img45_37544.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 0, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '#15#', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'lobzik-akkumulyatornyy-gosch-bez-akb-i-zu', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(46, 14, 'Пила циркулярная RedVerg RD-CS130-55', '<p>Данная вытяжка разработана из качественных материалов и с использованием самых современных технологий, что позволяет снизить уровень шума, издаваемый вытяжкой в процессе работы.</p>', '<p>Электронное кнопочное управление<br />Управление вытяжкой происходит при нажатии кнопок, которые активируют выбранную вами скорость. Также вытяжка оснащена электронным дисплеем, который отражает на табло выбранный режим.<br />3 режима<br />Данная модель имеет 3 режима работы, что оптимально для приготовления самых разных блюд. Например, когда готовятся несколько блюд одновременно, стоит включить максимальную скорость, то есть третий режим, а при приготовлении омлета или каши установите свою вытяжку на среднюю или минимальную скорость.</p>', 7000, 0, '0', '1', '1', '34546456', '1', '49,50', 'i11-35ii11-36ii11-33ii12-37ii12-45ii13-41i', 0x613a333a7b693a31313b613a333a7b693a303b733a323a223335223b693a313b733a323a223336223b693a323b733a323a223333223b7d693a31323b613a323a7b693a303b733a323a223337223b693a313b733a323a223435223b7d693a31333b613a313a7b693a303b733a323a223431223b7d7d, '1', 0, '1', '', '0', 1574793235, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img46_54151s.jpg', '/UserFiles/Image/Trial/img46_54151.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 120, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '#15#16#', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'pila-cirkulyarnaya-redverg-rd-cs130-55', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(47, 14, 'Диск пильный Gosch, универсальный, 42Т, 160 x 20 мм', '<p>Следует учитывать, что размеры вытяжки должны соответствовать размерам рабочей поверхности, над которой она устанавливается.&nbsp;</p>', '<p>Следует учитывать, что размеры вытяжки должны соответствовать размерам рабочей поверхности, над которой она устанавливается.&nbsp;Носок из высокопрочного пластика (PVC). Язык из войлока толщиной 5 мм. Широкий размерный ряд</p>', 600, 0, '0', '1', '1', '3453446', '0', '49,44,46,50', 'i11-34ii12-37ii13-44i', 0x613a333a7b693a31313b613a313a7b693a303b733a323a223334223b7d693a31323b613a313a7b693a303b733a323a223337223b7d693a31333b613a313a7b693a303b733a323a223434223b7d7d, '1', 0, '1', '', '0', 1574793157, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img47_24068s.jpg', '/UserFiles/Image/Trial/img47_24068.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 345, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '#15#16#', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'disk-pilnyy-gosch-universalnyy-42t-160-x-20-mm', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(48, 14, 'Пила циркулярная Lammer Bles CRP1300D', '<p>Данная модель имеет 3 режима работы, что оптимально для приготовления самых разных блюд. Например, когда готовятся несколько блюд одновременно, стоит включить максимальную скорость, то есть третий режим, а при приготовлении омлета или каши установите свою вытяжку на среднюю или минимальную скорость.</p>', '<p>Электронное кнопочное управление<br />Управление вытяжкой происходит при нажатии кнопок, которые активируют выбранную вами скорость. Также вытяжка оснащена электронным дисплеем, который отражает на табло выбранный режим.<br />3 режима<br />Данная модель имеет 3 режима работы, что оптимально для приготовления самых разных блюд. Например, когда готовятся несколько блюд одновременно, стоит включить максимальную скорость, то есть третий режим, а при приготовлении омлета или каши установите свою вытяжку на среднюю или минимальную скорость.</p>', 450, 0, '0', '1', '1', '44656', '0', '47,44', 'i11-34ii12-40ii12-45ii13-44i', 0x613a333a7b693a31313b613a313a7b693a303b733a323a223334223b7d693a31323b613a323a7b693a303b733a323a223430223b693a313b733a323a223435223b7d693a31333b613a313a7b693a303b733a323a223434223b7d7d, '1', 0, '1', '', '0', 1574793221, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img48_22317s.jpg', '/UserFiles/Image/Trial/img48_22317.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 10, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '#15#16#', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'pila-cirkulyarnaya-lammer-bles-crp1300d', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(49, 14, 'Насадка на дрель для заточки сверл FML, 3,5-10 мм', '<p>Управление вытяжкой происходит при нажатии кнопок, которые активируют выбранную вами скорость. Также вытяжка оснащена электронным дисплеем, который отражает на табло выбранный режим.</p>', '<p>Сочетание стекла, стали и прямых, горизонтальных линий, используемых в кухонных вытяжках современного дизайна, объединяет внешний вид кухонной мебели и бытовой техники, создавая полноценное решение в стиле хай-тек.<br />Фронатальная панель из стекла<br />Вытяжка, выполненная из стекла это особенная деталь кухонного интерьера, которая не только выполняет свои прямые функциональные обязанности, но и привносит в дизайн помещения особую эстетику, проявляющуюся в оригинальной форме и исходном материале высокого качества. Вытяжки из стекла очень удобны в очистке.</p>', 450, 0, '0', '1', '1', '4543456', '0', '47,50', 'i11-34ii12-38ii12-45ii13-43i', 0x613a333a7b693a31313b613a313a7b693a303b733a323a223334223b7d693a31323b613a323a7b693a303b733a323a223338223b693a313b733a323a223435223b7d693a31333b613a313a7b693a303b733a323a223433223b7d7d, '1', 0, '1', '', '0', 1574793207, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img49_36956s.jpg', '/UserFiles/Image/Trial/img49_36956.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 46, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '#15#16#', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'nasadka-na-drel-dlya-zatochki-sverl-fml-35-10-mm', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL);
INSERT INTO `phpshop_products` (`id`, `category`, `name`, `description`, `content`, `price`, `price_n`, `sklad`, `p_enabled`, `enabled`, `uid`, `spec`, `odnotip`, `vendor`, `vendor_array`, `yml`, `num`, `newtip`, `title`, `title_enabled`, `datas`, `page`, `user`, `descrip`, `descrip_enabled`, `title_shablon`, `descrip_shablon`, `keywords`, `keywords_enabled`, `keywords_shablon`, `pic_small`, `pic_big`, `yml_bid_array`, `parent_enabled`, `parent`, `items`, `weight`, `price2`, `price3`, `price4`, `price5`, `files`, `baseinputvaluta`, `ed_izm`, `dop_cat`, `rate`, `rate_count`, `price_search`, `parent2`, `color`, `productsgroup_check`, `productsgroup_products`, `vendor_code`, `vendor_name`, `productday`, `hit`, `option1`, `option2`, `option3`, `option4`, `option5`, `prod_seo_name`, `prod_seo_name_old`, `manufacturer_warranty`, `sales_notes`, `country_of_origin`, `adult`, `delivery`, `pickup`, `store`, `yandex_min_quantity`, `yandex_step_quantity`, `yandex_condition`, `yandex_condition_reason`) VALUES
(50, 14, 'Эксцентриковая насадка для Gosch AdvancedDril18, черный', '<p>Вытяжка, выполненная из стекла это особенная деталь кухонного интерьера, которая не только выполняет свои прямые функциональные обязанности, но и привносит в дизайн помещения особую эстетику, проявляющуюся в оригинальной форме и исходном материале высокого качества. Вытяжки из стекла очень удобны в очистке.</p>', '<p>Электронное кнопочное управление<br />Управление вытяжкой происходит при нажатии кнопок, которые активируют выбранную вами скорость. Также вытяжка оснащена электронным дисплеем, который отражает на табло выбранный режим.<br />3 режима<br />Данная модель имеет 3 режима работы, что оптимально для приготовления самых разных блюд. Например, когда готовятся несколько блюд одновременно, стоит включить максимальную скорость, то есть третий режим, а при приготовлении омлета или каши установите свою вытяжку на среднюю или минимальную скорость.</p>', 1200, 0, '0', '1', '1', '344556', '1', '49,47', 'i11-34ii12-39ii12-45ii13-42i', 0x613a333a7b693a31313b613a313a7b693a303b733a323a223334223b7d693a31323b613a323a7b693a303b733a323a223339223b693a313b733a323a223435223b7d693a31333b613a313a7b693a303b733a323a223432223b7d7d, '1', 0, '1', '', '0', 1574793289, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img50_30692s.jpg', '/UserFiles/Image/Trial/img50_30692.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 10, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'ekscentrikovaya-nasadka-dlya-gosch-advanceddril18-chernyy', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(51, 14, 'Дрель-шуруповерт аккумуляторная Gosch Advanced Impact', '<p>Сочетание стекла, стали и прямых, горизонтальных линий, используемых в кухонных вытяжках современного дизайна, объединяет внешний вид кухонной мебели и бытовой техники, создавая полноценное решение в стиле хай-тек.</p>', '<p>Следует учитывать, что размеры вытяжки должны соответствовать размерам рабочей поверхности, над которой она устанавливается. Вытяжка будет работать эффективно лишь при условии, что ее воздухоприемный зонт полностью закрывает плоскость варочной поверхности. Вытяжка, размером 60 сантиметров это идеальное решение для варочных поверхностей шириной 60 и 45 см.<br />Низкий уровень шума<br />Данная вытяжка разработана из качественных материалов и с использованием самых современных технологий, что позволяет снизить уровень шума, издаваемый вытяжкой в процессе работы.</p>', 6000, 0, '0', '1', '1', '455654', '0', '47,49', 'i11-36ii12-38ii13-42i', 0x613a333a7b693a31313b613a313a7b693a303b733a323a223336223b7d693a31323b613a313a7b693a303b733a323a223338223b7d693a31333b613a313a7b693a303b733a323a223432223b7d7d, '1', 0, '1', '', '0', 1574793171, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img51_26484s.jpg', '/UserFiles/Image/Trial/img51_26484.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 123, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '#15#16#', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'drel-shurupovert-akkumulyatornaya-gosch-advanced-impact', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(52, 14, 'Ручная машина Baimeder для оштукатуривания', '<p>Сочетание стекла, стали и прямых, горизонтальных линий, используемых в кухонных вытяжках современного дизайна, объединяет внешний вид кухонной мебели и бытовой техники, создавая полноценное решение в стиле хай-тек.</p>', '<p>Электронное кнопочное управление<br />Управление вытяжкой происходит при нажатии кнопок, которые активируют выбранную вами скорость. Также вытяжка оснащена электронным дисплеем, который отражает на табло выбранный режим.<br />3 режима<br />Данная модель имеет 3 режима работы, что оптимально для приготовления самых разных блюд. Например, когда готовятся несколько блюд одновременно, стоит включить максимальную скорость, то есть третий режим, а при приготовлении омлета или каши установите свою вытяжку на среднюю или минимальную скорость.</p>', 5000, 0, '0', '1', '1', '244536345', '1', '47,49', 'i11-34ii12-38ii12-40ii12-45ii13-48i', 0x613a333a7b693a31313b613a313a7b693a303b733a323a223334223b7d693a31323b613a333a7b693a303b733a323a223338223b693a313b733a323a223430223b693a323b733a323a223435223b7d693a31333b613a313a7b693a303b733a323a223438223b7d7d, '1', 0, '1', '', '0', 1574793272, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img52_37358s.jpg', '/UserFiles/Image/Trial/img52_37358.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 0, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 0, NULL, NULL, '0', '', '', '', '0', '0', '', '', NULL, NULL, NULL, 'ruchnaya-mashina-baimeder-dlya-oshtukaturivaniya', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(82, 26, 'Пуловер Springfold 37 ', NULL, NULL, 3800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846475, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(53, 26, 'Брюки Mangoff 36 ', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844610, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img11_78833s.jpg', '/UserFiles/Image/Trial/img11_78833.jpg', 0x4e3b, '1', '36', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'белый', '#c0c0c0', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(54, 26, 'Брюки Mangoff 37 ', NULL, NULL, 5000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844317, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'красный', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(55, 26, 'Брюки Mangoff 38 ', NULL, NULL, 5500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844053, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'красный', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(57, 26, 'Брюки Mangoff 38 голубой', NULL, NULL, 5200, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844562, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img11_33069.jpg', '/UserFiles/Image/Trial/img11_33069.jpg', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'голубой', '#0075ff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(58, 26, 'Брюки Mangoff 37 черный', NULL, NULL, 9000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844482, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img11_25850s.jpg', '/UserFiles/Image/Trial/img11_25850.jpg', 0x4e3b, '1', '37', 0, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'черный', '#000000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(60, 26, 'Брюки Oodjim Ultra 38 ', NULL, NULL, 5590, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844980, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img5_47325.jpg', '/UserFiles/Image/Trial/img5_47325.jpg', 0x4e3b, '1', '38', 0, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(61, 26, 'Брюки Oodjim Ultra 39 ', NULL, NULL, 6590, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574844863, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img5_23962s.jpg', '/UserFiles/Image/Trial/img5_23962.jpg', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(62, 26, 'Джинсы 1001 Dressyy 37 синий', NULL, NULL, 1200, 1350, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847384, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'синий', '#0000ff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(63, 26, 'Джинсы 1001 Dressyy 37 голубой', NULL, NULL, 1300, 1350, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847389, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'голубой', '#00ffff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(64, 26, 'Джинсы 1001 Dressyy 38 синий', NULL, NULL, 1300, 1350, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847379, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'синий', '#0000ff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(66, 26, 'Джинсы Befreedom 37 синий', NULL, NULL, 2000, 2500, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847427, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img2_96038.jpg', '/UserFiles/Image/Trial/img2_96038.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'синий', '#0000ff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(65, 26, 'Джинсы 1001 Dressyy 39 синий', NULL, NULL, 1300, 1350, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847372, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'синий', '#0000ff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(67, 26, 'Джинсы Befreedom 37 голубой', NULL, NULL, 2000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574845785, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img2_95509s.jpg', '/UserFiles/Image/Trial/img2_95509.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'голубой', '#00FFFF', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(68, 26, 'Джинсы Befreedom 37 красный', NULL, NULL, 2000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574845787, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img2_19797s.jpg', '/UserFiles/Image/Trial/img2_19797.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'красный', '#FF0000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(69, 26, 'Джинсы Befreedom 38 синий', NULL, NULL, 2000, 2500, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847423, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img2_64554.jpg', '/UserFiles/Image/Trial/img2_64554.jpg', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'синий', '#0000ff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(70, 26, 'Джинсы Befreedom 39 синий', NULL, NULL, 2000, 2500, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847418, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img2_27973.jpg', '/UserFiles/Image/Trial/img2_27973.jpg', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'синий', '#0000ff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(71, 26, 'Джинсы Concepted Clubs красный 37', NULL, NULL, 3500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574845997, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img3_56614s.jpg', '/UserFiles/Image/Trial/img3_56614.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'фуксия', '#f7109b', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(72, 26, 'Джинсы Concepted Clubs синий 38', NULL, NULL, 3500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574845992, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img3_37243s.jpg', '/UserFiles/Image/Trial/img3_37243.jpg', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'фуксия', '#f7109b', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(73, 26, 'Джинсы Concepted Clubs 37 синий', NULL, NULL, 5000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846000, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img3_83164s.jpg', '/UserFiles/Image/Trial/img3_83164.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'синий', '#0000FF', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(74, 26, 'Джинсы Concepted Clubs 38 синий', NULL, NULL, 5000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574845952, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'синий', '#0000FF', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(75, 26, 'Джинсы Concepted Clubs 39 голубой', NULL, NULL, 3700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846002, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img3_12202s.jpg', '/UserFiles/Image/Trial/img3_12202.jpg', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'голубой', '#00FFFF', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(76, 26, 'Джинсы Concepted Clubs 37 желтый', NULL, NULL, 3900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846003, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img3_93384s.jpg', '/UserFiles/Image/Trial/img3_93384.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'желтый', '#FFFF00', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(77, 26, 'Майка Oodjim 37 зеленый', NULL, NULL, 600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846369, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img13_59800s.jpg', '/UserFiles/Image/Trial/img13_59800.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'зеленый', '#008000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(78, 26, 'Майка Oodjim 37 розовый', NULL, NULL, 600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846382, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img13_67547s.jpg', '/UserFiles/Image/Trial/img13_67547.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'розовый', '#FFC0CB', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(79, 26, 'Майка Oodjim 37 пепельный', NULL, NULL, 600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846391, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img13_34252s.jpg', '/UserFiles/Image/Trial/img13_34252.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'пепельный', '#c4e6eb', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(87, 26, 'Футболка Mangoff 38 песочный', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846560, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'бледно-розовый', '#fcdae7', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(81, 26, 'Майка Oodjim 39 песочный', NULL, NULL, 600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846395, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img13_86638s.jpg', '/UserFiles/Image/Trial/img13_86638.jpg', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'песочный', '#f5cc7e', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(89, 26, 'Футболка Mangoff 38 голубой', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846581, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'голубой', '#b6f7f7', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(90, 26, 'Футболка Mangoff 39 голубой', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846601, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'голубой', '#b6f7f7', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(91, 26, 'Футболка Springfold 37 бледно-голубой', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846801, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img9_63883s.jpg', '/UserFiles/Image/Trial/img9_63883.jpg', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'бледно-голубой', '#c1e2f0', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(92, 26, 'Футболка Springfold 38 бледно-голубой', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846800, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img9_97932s.jpg', '/UserFiles/Image/Trial/img9_97932.jpg', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'бледно-голубой', '#c1e2f0', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(93, 26, 'Футболка Springfold 39 бледно-розовый', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574846803, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img9_22416s.jpg', '/UserFiles/Image/Trial/img9_22416.jpg', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'бледно-розовый', '#e7c1f0', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(94, 26, 'Юбка Mangoff 37 ', NULL, NULL, 5000, 5600, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847301, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(95, 26, 'Юбка Mangoff 38 ', NULL, NULL, 5000, 5700, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847296, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(96, 26, 'Юбка Mangoff 39 ', NULL, NULL, 5000, 5600, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847289, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(97, 26, 'Юбка Mangoff 40 ', NULL, NULL, 5000, 5500, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574847284, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', '40', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(101, 27, 'Polo Crocsby 38 черный', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849717, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img20_54474s.jpg', '/UserFiles/Image/Trial/img20_54474.jpg', 0x4e3b, '1', 'L', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'черный', '#000000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(100, 27, 'Polo Crocsby 37 черный', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849718, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img20_27253s.jpg', '/UserFiles/Image/Trial/img20_27253.jpg', 0x4e3b, '1', 'XL', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'черный', '#000000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(102, 27, 'Брюки мужские Oliverty M черный', NULL, NULL, 3000, 3700, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849602, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'черный', '#000000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(103, 27, 'Брюки мужские Oliverty M белый', NULL, NULL, 3000, 3700, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849699, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img6_42150s.jpg', '/UserFiles/Image/Trial/img6_42150.jpg', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'белый', '#ffffff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(104, 27, 'Брюки мужские Oliverty L черный', NULL, NULL, 3000, 3700, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849617, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'L', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'черный', '#000000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(105, 27, 'Брюки мужские Oliverty XL черный', NULL, NULL, 3000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849702, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img6_51944s.jpg', '/UserFiles/Image/Trial/img6_51944.jpg', 0x4e3b, '1', 'XL', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'черный', '#000000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(106, 27, 'Джинсы Modizy S ', NULL, NULL, 5600, 6000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849682, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(107, 27, 'Джинсы Modizy M ', NULL, NULL, 5600, 6000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849686, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'M', 10, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(108, 27, 'Джинсы Modizy L ', NULL, NULL, 5600, 6000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849692, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'L', 12, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(109, 27, 'Поло S.Oliverty S ', NULL, NULL, 3590, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849855, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 10, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(110, 27, 'Поло S.Oliverty M ', NULL, NULL, 3590, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849859, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 12, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(111, 27, 'Поло S.Oliverty L ', NULL, NULL, 3590, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849864, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 11, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(112, 27, 'Поло S.Oliverty XL ', NULL, NULL, 3590, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574849870, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'XL', 34, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(113, 27, 'Пуловер Kustang S черный', NULL, NULL, 6700, 11000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850036, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img21_35310s.jpg', '/UserFiles/Image/Trial/img21_35310.jpg', 0x4e3b, '1', 'S', 41, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'черный', '#000000', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(114, 27, 'Пуловер Kustang M белый', NULL, NULL, 6700, 11000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574969522, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img21_18874s.jpg', '/UserFiles/Image/Trial/img21_18874.jpg', 0x4e3b, '1', 'M', 23, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'белый', '#ffffff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(115, 27, 'Пуловер Kustang L белый', NULL, NULL, 6700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574969526, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img21_52213s.jpg', '/UserFiles/Image/Trial/img21_52213.jpg', 0x4e3b, '1', 'L', 22, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, 'белый', '#ffffff', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(119, 27, 'Рубашка KUSTANG S ', NULL, NULL, 6000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850093, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 3, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(116, 27, 'Пуловер Oliverty M ', NULL, NULL, 8000, 13000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850008, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'M', 15, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(117, 27, 'Пуловер Oliverty L ', NULL, NULL, 8000, 13000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850013, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'L', 19, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(118, 27, 'Пуловер Oliverty XL ', NULL, NULL, 8000, 13000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850018, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'XL', 34, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(120, 27, 'Рубашка KUSTANG M ', NULL, NULL, 6000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850097, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 9, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(121, 27, 'Рубашка KUSTANG L ', NULL, NULL, 6000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850102, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 93, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(122, 27, 'Рубашка Oliverty S ', NULL, NULL, 2500, 3000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850172, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'S', 15, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(123, 27, 'Рубашка Oliverty M ', NULL, NULL, 2500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850152, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 24, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(124, 27, 'Рубашка Oliverty L ', NULL, NULL, 2500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850157, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 23, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(125, 27, 'Рубашка Polambia M ', NULL, NULL, 4690, 7000, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850297, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'M', 19, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(126, 27, 'Рубашка Polambia L ', NULL, NULL, 4690, 6700, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850306, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'L', 67, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(127, 27, 'Рубашка Polambia XL ', NULL, NULL, 4500, 4900, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850324, '', 1, '', '0', '', '', '', '0', '', '0', '0', 0x4e3b, '1', 'XL', 89, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', '', '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(128, 30, 'Кеды Gans 39 ', NULL, NULL, 8000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850721, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '39', 45, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(129, 30, 'Кеды Gans 40 ', NULL, NULL, 8000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850727, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '40', 34, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(130, 30, 'Кеды Gans 41 ', NULL, NULL, 8000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850733, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '41', 12, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(131, 30, 'Кеды Abibas 39 ', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850802, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '39', 18, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(132, 30, 'Кеды Abibas 40 ', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850807, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '40', 2, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(133, 30, 'Кеды Abibas 41 ', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850811, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '41', 7, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(134, 30, 'Слипоны Gans 42 ', NULL, NULL, 8000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850821, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '42', 8, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(135, 30, 'Слипоны Gans 43 ', NULL, NULL, 8000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850827, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '43', 67, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(136, 30, 'Слипоны Luma 38 ', NULL, NULL, 6700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850841, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 19, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(137, 30, 'Слипоны Luma 39 ', NULL, NULL, 6700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850846, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '39', 0, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(138, 30, 'Слипоны Luma 40 ', NULL, NULL, 6700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574850850, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '40', 5, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(139, 28, 'Очки Mangoff Пластик ', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574851260, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'Пластик', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(140, 28, 'Очки Mangoff Металл ', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574851312, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'Металл', 91, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL),
(141, 28, 'Очки Springfold пластик ', NULL, NULL, 3590, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1574851337, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'пластик', 18, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, 0, '', NULL, '0', '', '', '', '0', '0', NULL, NULL, NULL, NULL, NULL, '', '', '2', '', '', '2', '1', '2', '2', 0, 0, '1', NULL);

DROP TABLE IF EXISTS phpshop_promotions;
CREATE TABLE phpshop_promotions (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `enabled` enum('0','1') DEFAULT '0',
  `description` text NOT NULL,
  `label` text NOT NULL,
  `active_check` enum('0','1') NOT NULL,
  `active_date_ot` varchar(255) NOT NULL,
  `active_date_do` varchar(255) NOT NULL,
  `discount_check` enum('0','1') NOT NULL,
  `discount_tip` enum('0','1') NOT NULL,
  `discount` int(11) NOT NULL,
  `num_check` int(11) DEFAULT '0',
  `categories_check` enum('0','1') NOT NULL,
  `categories` text NOT NULL,
  `status_check` enum('0','1') NOT NULL DEFAULT '0',
  `statuses` text NOT NULL,
  `products_check` enum('0','1') NOT NULL,
  `products` text NOT NULL,
  `sum_order_check` enum('0','1') NOT NULL,
  `sum_order` int(11) NOT NULL,
  `delivery_method_check` enum('0','1') NOT NULL,
  `delivery_method` int(11) NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `block_old_price` enum('0','1') DEFAULT '0',
  `hide_old_price` enum('0','1') DEFAULT '0',
  `action` ENUM('1', '2') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_rating_categories;
CREATE TABLE phpshop_rating_categories (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `ids_dir` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '0',
  `revoting` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_rating_categories VALUES ('1', ',2,,3,,4,,6,,7,,8,,10,,11,,12,', 'Товары', '1', '1');
DROP TABLE IF EXISTS phpshop_rating_charact;
CREATE TABLE phpshop_rating_charact (
  `id_charact` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `num` int(11) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id_charact`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_rating_charact VALUES ('1', '1', 'Внешний вид', '1', '1');
INSERT INTO phpshop_rating_charact VALUES ('2', '1', 'Функциональность', '2', '1');
INSERT INTO phpshop_rating_charact VALUES ('3', '1', 'Качество', '3', '1');
DROP TABLE IF EXISTS phpshop_rating_votes;
CREATE TABLE phpshop_rating_votes (
  `id_vote` int(11) NOT NULL AUTO_INCREMENT,
  `id_charact` int(11) DEFAULT '0',
  `id_good` int(11) DEFAULT '0',
  `id_user` int(11) DEFAULT '0',
  `userip` varchar(16) DEFAULT '',
  `rate` tinyint(4) DEFAULT '0',
  `date` int(11) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id_vote`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_rssgraber;
CREATE TABLE phpshop_rssgraber (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link` text,
  `day_num` int(1) DEFAULT '1',
  `news_num` mediumint(8) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '1',
  `start_date` int(16) unsigned DEFAULT '0',
  `end_date` int(16) unsigned DEFAULT '0',
  `last_load` int(16) unsigned DEFAULT '0',
  `servers` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_rssgraber_jurnal;
CREATE TABLE phpshop_rssgraber_jurnal (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(15) unsigned DEFAULT '0',
  `link_id` int(11) DEFAULT '0',
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_search_base;
CREATE TABLE phpshop_search_base (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `uid` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '1',
  `category` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_search_jurnal;
CREATE TABLE phpshop_search_jurnal (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `num` tinyint(32) DEFAULT '0',
  `datas` varchar(11) DEFAULT '',
  `dir` varchar(255) DEFAULT '',
  `cat` tinyint(11) DEFAULT '0',
  `set` tinyint(2) DEFAULT '0',
  `ip` VARCHAR(64),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_servers;
CREATE TABLE phpshop_servers (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `host` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '0',
  `code` varchar(64) DEFAULT '',
  `skin` varchar(64) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `descrip` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `company` varchar(255) DEFAULT '',
  `adres` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `adminmail` varchar(255) DEFAULT '',
  `currency` int(11) DEFAULT NULL,
  `lang` varchar(32) DEFAULT NULL,
  `admoption` blob,
  `warehouse` int(11) DEFAULT '0',
  `price` enum('1','2','3','4','5') DEFAULT '1',
  `admin` int(11) default 0,
  `icon` VARCHAR(255),
  `company_id` INT(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_shopusers;
CREATE TABLE phpshop_shopusers (
  `id` int(64) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(64) DEFAULT '',
  `password` varchar(64) DEFAULT '',
  `datas` varchar(64) DEFAULT '',
  `mail` varchar(64) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `company` varchar(255) DEFAULT '',
  `inn` varchar(64) DEFAULT '',
  `tel` varchar(64) DEFAULT '',
  `adres` text,
  `enabled` enum('0','1') DEFAULT '0',
  `status` varchar(64) DEFAULT '0',
  `kpp` varchar(64) DEFAULT '',
  `tel_code` varchar(64) DEFAULT '',
  `wishlist` blob,
  `data_adres` blob,
  `cumulative_discount` int(11) DEFAULT '0',
  `sendmail` enum('0','1') DEFAULT '1',
  `servers` int(11) default 0,
  `bonus` int(11) DEFAULT '0',
  `token` INT(11),
  `token_time` INT(11),
  `bot` VARCHAR(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_shopusers VALUES ('31', 'test@mail.ru', 'eXF2bDI5MGQ=', '1574888495', 'test@mail.ru', 'Иван Иванов', '', '', '+7 (098) 700-00-00', '', '1', '0', '', '', 'a:0:{}', 'a:2:{s:4:"list";a:1:{i:0;a:2:{s:7:"tel_new";s:18:"+7 (098) 709-86-09";s:13:"delivtime_new";s:6:"765765";}}s:4:"main";i:0;}', '0', '1','','0','','','');
INSERT INTO phpshop_shopusers VALUES ('32', 'test@gmail.com', 'NHg1eWU4dGw=', '1575019417', 'test@gmail.com', 'Мария Волкова', '', '', '+7 (098) 700-22-09', '', '1', '0', '', '', 'a:0:{}', 'a:2:{s:4:"list";a:1:{i:0;a:2:{s:7:"tel_new";s:18:"+7 (098) 709-86-09";s:13:"delivtime_new";s:4:"с 13";}}s:4:"main";i:0;}', '0', '1','','0','','','');
DROP TABLE IF EXISTS phpshop_shopusers_status;
CREATE TABLE phpshop_shopusers_status (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `discount` float DEFAULT '0',
  `price` enum('1','2','3','4','5') DEFAULT '1',
  `enabled` enum('0','1') DEFAULT '1',
  `cumulative_discount_check` enum('0','1') DEFAULT '0',
  `cumulative_discount` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_shopusers_status VALUES ('1', 'Оптовик', '5', '1', '1', '1', 'a:2:{i:0;a:4:{s:17:"cumulative_sum_ot";s:4:"1000";s:17:"cumulative_sum_do";s:4:"5000";s:19:"cumulative_discount";s:1:"5";s:18:"cumulative_enabled";i:1;}i:1;a:4:{s:17:"cumulative_sum_ot";s:4:"2000";s:17:"cumulative_sum_do";s:5:"10000";s:19:"cumulative_discount";s:2:"10";s:18:"cumulative_enabled";i:1;}}');
INSERT INTO phpshop_shopusers_status VALUES ('2', 'Оптовик 3', '6', '1', '1', '1', 'a:1:{i:1;a:4:{s:17:"cumulative_sum_ot";s:3:"500";s:17:"cumulative_sum_do";s:4:"2000";s:19:"cumulative_discount";s:2:"40";s:18:"cumulative_enabled";i:1;}}');
INSERT INTO phpshop_shopusers_status VALUES ('3', 'Оптовик 2', '30', '1', '0', '0', 'a:2:{i:1;a:4:{s:17:"cumulative_sum_ot";s:3:"500";s:17:"cumulative_sum_do";s:4:"2000";s:19:"cumulative_discount";s:2:"40";s:18:"cumulative_enabled";i:1;}i:2;a:4:{s:17:"cumulative_sum_ot";s:3:"600";s:17:"cumulative_sum_do";s:5:"30000";s:19:"cumulative_discount";s:2:"70";s:18:"cumulative_enabled";i:1;}}');
DROP TABLE IF EXISTS phpshop_slider;
CREATE TABLE phpshop_slider (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '0',
  `num` smallint(6) DEFAULT '0',
  `link` varchar(255) DEFAULT '',
  `alt` varchar(255) DEFAULT '',
  `servers` varchar(64) DEFAULT '',
  `mobile` enum('0','1') default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_slider VALUES ('13', '/UserFiles/Image/Trial/slider2.jpg', '1', '0', '', '', '', '0');
INSERT INTO phpshop_slider VALUES ('16', '/UserFiles/Image/Trial/slider1.jpg', '1', '0', '', '', '', '0');
DROP TABLE IF EXISTS phpshop_sort;
CREATE TABLE phpshop_sort (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `category` int(11) unsigned DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `page` varchar(255) DEFAULT '',
  `icon` varchar(255) DEFAULT '',
  `description` text,
  `sort_seo_name` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_sort VALUES ('1', 'нейлон', '2', '1', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('2', 'хлопок', '2', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('3', 'лен', '2', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('4', 'спортивный', '3', '1', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('5', 'повседневный', '3', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('6', 'вечеринка', '3', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('7', 'женский', '4', '1', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('8', 'мужской', '4', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('9', 'девочки', '4', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('10', 'мальчики', '4', '4', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('11', 'Mangoff', '5', '1', '', '/UserFiles/Image/Trial/brand-1.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-1.png" alt="" width="210" height="70" /></p>', 'mangoff', '');
INSERT INTO phpshop_sort VALUES ('12', 'Oodjim', '5', '2', '', '/UserFiles/Image/Trial/brand-2.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-2.png" alt="" width="210" height="70" /></p>', 'oodjim', '');
INSERT INTO phpshop_sort VALUES ('13', '1001 Dressyy', '5', '3', '', '/UserFiles/Image/Trial/brand-3.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-3.png" alt="" width="210" height="70" /></p>', '-1001-dressyy', '');
INSERT INTO phpshop_sort VALUES ('14', 'Befreedom', '5', '4', '', '/UserFiles/Image/Trial/brand-4.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-4.png" alt="" width="210" height="70" /></p>', 'befreedom', '');
INSERT INTO phpshop_sort VALUES ('15', 'Concepted Clubs', '5', '5', '', '/UserFiles/Image/Trial/brand-5.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-5.png" alt="" width="210" height="70" /></p>', 'concepted-clubs', '');
INSERT INTO phpshop_sort VALUES ('16', 'Springfold', '5', '6', '', '/UserFiles/Image/Trial/brand-6.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-6.png" alt="" width="210" height="70" /></p>', 'springfold', '');
INSERT INTO phpshop_sort VALUES ('17', 'Crocsby', '5', '7', '', '/UserFiles/Image/Trial/brand-7.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-7.png" alt="" width="210" height="70" /></p>', 'crocsby', '');
INSERT INTO phpshop_sort VALUES ('18', 'Oliverty', '5', '8', '', '/UserFiles/Image/Trial/brand-8.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-8.png" alt="" width="210" height="70" /></p>', 'oliverty', '');
INSERT INTO phpshop_sort VALUES ('19', 'Modizy', '5', '9', '', '/UserFiles/Image/Trial/brand-9.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-9.png" alt="" width="210" height="70" /></p>', 'modizy', '');
INSERT INTO phpshop_sort VALUES ('20', 'Kustang', '5', '10', '', '/UserFiles/Image/Trial/brand-10.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-10.png" alt="" width="210" height="70" /></p>', 'kustang', '');
INSERT INTO phpshop_sort VALUES ('21', 'Polambia', '5', '11', '', '/UserFiles/Image/Trial/brand-11.png', '<p>Описание бренда заполняется в меню Товары - Характеристики - Характеристика с галочкой "бренд". В карточке редактирования характеристики, наведите на строку Значения, и нажмите на иконку Редактировать справа.</p>\n<p><img src="/UserFiles/Image/Trial/brand-11.png" alt="" width="210" height="61" /></p>', 'polambia', '');
INSERT INTO phpshop_sort VALUES ('22', 'Для всех типов кожи', '7', '1', '', '', '', '', '');
INSERT INTO phpshop_sort VALUES ('23', 'Для комбинированной кожи', '7', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('24', 'Для сухой и чувствительной кожи', '7', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('25', 'Лицо', '8', '1', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('26', 'Глаза', '8', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('27', 'Шея', '8', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('28', 'Волосы', '8', '4', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('29', 'Россия', '9', '1', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('30', 'Франция', '9', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('31', 'Корея', '9', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('32', 'США', '9', '4', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('33', 'Сталь', '11', '1', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('34', 'Пластик', '11', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('35', 'Абразив', '11', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('36', 'Металл', '11', '4', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('37', 'Электроинструменты', '12', '1', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('38', 'Ручной инструмент', '12', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('39', 'Измерительные инструменты', '12', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('40', 'Силовые инструменты', '12', '4', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('41', 'белый', '13', '1', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('42', 'черный', '13', '2', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('43', 'желтый', '13', '3', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('44', 'зеленый', '13', '4', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('45', 'Это виртуальный каталог', '12', '5', '', '', '', '', '');
INSERT INTO phpshop_sort VALUES ('46', 'Это виртуальный каталог', '7', '4', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('47', 'Руки', '8', '0', '', '',  NULL, '', '');
INSERT INTO phpshop_sort VALUES ('48', 'синий', '13', '0', '', '',  NULL, '', '');
ALTER TABLE `phpshop_sort` ADD `meta_description` varchar(255) DEFAULT '';

DROP TABLE IF EXISTS phpshop_sort_categories;
CREATE TABLE `phpshop_sort_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `num` int(11) DEFAULT '0',
  `category` int(11) DEFAULT '0',
  `filtr` enum('0','1') DEFAULT '0',
  `description` varchar(255) DEFAULT '',
  `goodoption` enum('0','1') DEFAULT '0',
  `optionname` enum('0','1') DEFAULT '0',
  `page` varchar(255) DEFAULT '',
  `brand` enum('0','1') DEFAULT '0',
  `product` enum('0','1') DEFAULT '0',
  `virtual` enum('0','1') DEFAULT '0',
  `yandex_param` enum('1','2') DEFAULT '1',
  `yandex_param_unit` varchar(64) DEFAULT '',
  `servers` varchar(64) DEFAULT '',
  `show_preview` ENUM('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=14 ;


INSERT INTO `phpshop_sort_categories` (`id`, `name`, `num`, `category`, `filtr`, `description`, `goodoption`, `optionname`, `page`, `brand`, `product`, `virtual`, `yandex_param`, `yandex_param_unit`, `servers`) VALUES
(1, 'Одежда', 0, 0, '0', '', '0', '0', '', '0', '0', '0', '1', '', ''),
(2, 'Материал', 4, 1, '1', '', '1', '0', '', '0', '0', '0', '1', '', ''),
(3, 'В таком же стиле', 2, 1, '0', 'В этой хар-ке стоит галка "Переключение". Вы можете показывать Рекомендуемые товары в зоне характеристик на витрине, вместо значения хар-ки будут выводиться рекомендуемые товары, указанные в карточке товара.', '0', '0', '', '0', '1', '0', '1', '', ''),
(4, 'Пол', 3, 1, '1', '', '1', '0', '', '0', '0', '0', '1', '', ''),
(5, 'Бренд', 1, 1, '1', 'Если у вас нет брендов, и вы не хотите видеть их в меню сайта, просто снимите галку Бренд и меню пропадет.', '0', '0', '', '1', '0', '1', '1', '', ''),
(6, 'Косметика', 2, 0, '0', '', '0', '0', '', '0', '0', '0', '1', '', ''),
(7, 'Тип кожи', 1, 6, '1', '', '0', '0', '', '0', '0', '1', '1', '', ''),
(8, 'Зона применения', 0, 6, '0', '', '0', '0', '', '0', '0', '0', '1', '', ''),
(9, 'Страна-изготовитель', 3, 6, '1', '', '0', '0', '', '0', '0', '0', '1', '', ''),
(10, 'Инструменты', 4, 0, '0', '', '0', '0', '', '0', '0', '0', '1', '', ''),
(11, 'Материал', 1, 10, '1', '', '1', '0', '', '0', '0', '0', '1', '', ''),
(12, 'Назначение', 2, 10, '1', '', '0', '0', '', '0', '0', '1', '1', '', ''),
(13, 'Цвет', 3, 10, '1', '', '1', '0', '', '0', '0', '0', '1', '', '');


DROP TABLE IF EXISTS phpshop_system;
CREATE TABLE `phpshop_system` (
  `id` int(32) NOT NULL,
  `name` text,
  `company` text,
  `num_row` int(10) DEFAULT NULL,
  `num_row_adm` int(10) DEFAULT NULL,
  `dengi` tinyint(11) DEFAULT NULL,
  `percent` varchar(16) DEFAULT '',
  `skin` varchar(32) DEFAULT NULL,
  `adminmail2` varchar(64) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `keywords` varchar(255) DEFAULT '',
  `kurs` float DEFAULT '0',
  `spec_num` tinyint(5) DEFAULT '0',
  `new_num` tinyint(11) DEFAULT '0',
  `tel` text,
  `bank` blob,
  `num_vitrina` enum('1','2','3','4') DEFAULT '3',
  `icon` varchar(255) DEFAULT '',
  `updateU` varchar(32) DEFAULT '',
  `nds` varchar(64) DEFAULT '',
  `nds_enabled` enum('0','1') DEFAULT '1',
  `admoption` blob,
  `kurs_beznal` tinyint(11) DEFAULT '0',
  `descrip` varchar(255) DEFAULT '',
  `descrip_shablon` varchar(255) DEFAULT '',
  `title_shablon` varchar(255) DEFAULT '',
  `keywords_shablon` varchar(255) DEFAULT '',
  `title_shablon2` varchar(255) DEFAULT '',
  `descrip_shablon2` varchar(255) DEFAULT '',
  `keywords_shablon2` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `title_shablon3` varchar(255) DEFAULT '',
  `descrip_shablon3` varchar(255) DEFAULT '',
  `keywords_shablon3` varchar(255) DEFAULT '',
  `rss_use` int(1) UNSIGNED DEFAULT '1',
  `1c_load_accounts` enum('0','1') DEFAULT '1',
  `1c_load_invoice` enum('0','1') DEFAULT '1',
  `1c_option` blob,
  `sort_title_shablon` varchar(255) DEFAULT '',
  `sort_description_shablon` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_system`
--

INSERT INTO `phpshop_system` (`id`, `name`, `company`, `num_row`, `num_row_adm`, `dengi`, `percent`, `skin`, `adminmail2`, `title`, `keywords`, `kurs`, `spec_num`, `new_num`, `tel`, `bank`, `num_vitrina`, `icon`, `updateU`, `nds`, `nds_enabled`, `admoption`, `kurs_beznal`, `descrip`, `descrip_shablon`, `title_shablon`, `keywords_shablon`, `title_shablon2`, `descrip_shablon2`, `keywords_shablon2`, `logo`, `title_shablon3`, `descrip_shablon3`, `keywords_shablon3`, `rss_use`, `1c_load_accounts`, `1c_load_invoice`, `1c_option`, `sort_title_shablon`, `sort_description_shablon`) VALUES
(1, 'Название интернет-магазина', 'Продавец', 8, 3, 6, '0', 'unit', 'admin@localhost', 'Демо-версия скрипта интернет-магазина PHPShop', 'скрипт магазина, купить интернет-магазин', 6, 8, 8, '&#43;7(495)111-22-33', 0x613a31323a7b733a383a226f72675f6e616d65223b733a31343a22cecece2022cff0eee4e0e2e5f622223b733a31323a226f72675f75725f6164726573223b733a34313a2230303030303020e32e20cceef1eae2e02c20f3eb2e20def0e8e4e8f7e5f1eae0ff2c20e4eeec20312e223b733a393a226f72675f6164726573223b733a33303a22cceef1eae2e02c20f3eb2e20d4e8e7e8f7e5f1eae0ff2c20e4eeec20312e223b733a373a226f72675f696e6e223b733a393a22373737373737373737223b733a373a226f72675f6b7070223b733a31303a2238383838383838383838223b733a393a226f72675f7363686574223b733a31363a2231313131313131313131313131313131223b733a383a226f72675f62616e6b223b733a32333a22cec0ce2022c2e0f820f2e5f1f2eee2fbe920e1e0edea22223b733a373a226f72675f626963223b733a383a223436373738383838223b733a31343a226f72675f62616e6b5f7363686574223b733a31353a22323232323232323232323232323232223b733a393a226f72675f7374616d70223b733a33323a222f5573657246696c65732f496d6167652f547269616c2f7374616d702e706e67223b733a373a226f72675f736967223b733a33363a222f5573657246696c65732f496d6167652f547269616c2f66616373696d696c652e706e67223b733a31313a226f72675f7369675f627568223b733a33363a222f5573657246696c65732f496d6167652f547269616c2f66616373696d696c652e706e67223b7d, '4', '', '1409661405', '20', '1', 0x613a3134333a7b733a31323a22736b6c61645f737461747573223b733a313a2231223b733a31333a22636c6f75645f656e61626c6564223b693a303b733a32333a226469676974616c5f70726f647563745f656e61626c6564223b693a303b733a31333a22757365725f63616c656e646172223b693a303b733a31393a22757365725f70726963655f6163746976617465223b693a303b733a32323a22757365725f6d61696c5f61637469766174655f707265223b693a303b733a31383a227273735f6772616265725f656e61626c6564223b693a303b733a31373a22696d6167655f736176655f736f75726365223b693a303b733a363a22696d675f776d223b4e3b733a353a22696d675f77223b733a343a2231303030223b733a353a22696d675f68223b733a343a2231303030223b733a363a22696d675f7477223b733a333a22333030223b733a363a22696d675f7468223b733a333a22333030223b733a31343a2277696474685f706f64726f626e6f223b733a333a22313030223b733a31323a2277696474685f6b7261746b6f223b733a333a22313030223b733a31323a22626173655f656e61626c6564223b4e3b733a31313a22736d735f656e61626c6564223b693a303b733a31343a226e6f746963655f656e61626c6564223b693a303b733a373a22626173655f6964223b733a303a22223b733a393a22626173655f686f7374223b733a303a22223b733a31333a22736b6c61645f656e61626c6564223b733a313a2231223b733a31303a2270726963655f7a6e616b223b733a313a2230223b733a31383a22757365725f6d61696c5f6163746976617465223b693a303b733a31313a22757365725f737461747573223b733a313a2230223b733a393a22757365725f736b696e223b733a313a2231223b733a31323a22636172745f6d696e696d756d223b733a313a2230223b733a31333a2277617465726d61726b5f626967223b613a32313a7b733a31343a226269675f6d657267654c6576656c223b693a37303b733a31313a226269675f656e61626c6564223b733a313a2231223b733a383a226269675f74797065223b733a333a22706e67223b733a31323a226269675f706e675f66696c65223b733a33303a222f5573657246696c65732f496d6167652f73686f705f6c6f676f2e706e67223b733a31323a226269675f636f7079466c6167223b733a313a2230223b733a363a226269675f736d223b693a303b733a31363a226269675f706f736974696f6e466c6167223b733a313a2234223b733a31333a226269675f706f736974696f6e58223b693a303b733a31333a226269675f706f736974696f6e59223b693a303b733a393a226269675f616c706861223b693a37303b733a383a226269675f74657874223b733a303a22223b733a32313a226269675f746578745f706f736974696f6e466c6167223b693a303b733a383a226269675f73697a65223b693a303b733a393a226269675f616e676c65223b693a303b733a31383a226269675f746578745f706f736974696f6e58223b693a303b733a31383a226269675f746578745f706f736974696f6e59223b693a303b733a31303a226269675f636f6c6f7252223b693a303b733a31303a226269675f636f6c6f7247223b693a303b733a31303a226269675f636f6c6f7242223b693a303b733a31343a226269675f746578745f616c706861223b693a303b733a383a226269675f666f6e74223b733a31363a226e6f726f626f745f666f6e742e747466223b7d733a31353a2277617465726d61726b5f736d616c6c223b613a32313a7b733a31363a22736d616c6c5f6d657267654c6576656c223b693a3130303b733a31333a22736d616c6c5f656e61626c6564223b733a313a2231223b733a31303a22736d616c6c5f74797065223b733a333a22706e67223b733a31343a22736d616c6c5f706e675f66696c65223b733a32353a222f5573657246696c65732f496d6167652f6c6f676f2e706e67223b733a31343a22736d616c6c5f636f7079466c6167223b733a313a2230223b733a383a22736d616c6c5f736d223b693a303b733a31383a22736d616c6c5f706f736974696f6e466c6167223b733a313a2231223b733a31353a22736d616c6c5f706f736974696f6e58223b693a303b733a31353a22736d616c6c5f706f736974696f6e59223b693a303b733a31313a22736d616c6c5f616c706861223b693a35303b733a31303a22736d616c6c5f74657874223b733a303a22223b733a32333a22736d616c6c5f746578745f706f736974696f6e466c6167223b693a303b733a31303a22736d616c6c5f73697a65223b693a303b733a31313a22736d616c6c5f616e676c65223b693a303b733a32303a22736d616c6c5f746578745f706f736974696f6e58223b693a303b733a32303a22736d616c6c5f746578745f706f736974696f6e59223b693a303b733a31323a22736d616c6c5f636f6c6f7252223b693a303b733a31323a22736d616c6c5f636f6c6f7247223b693a303b733a31323a22736d616c6c5f636f6c6f7242223b693a303b733a31363a22736d616c6c5f746578745f616c706861223b693a303b733a31303a22736d616c6c5f666f6e74223b733a31363a226e6f726f626f745f666f6e742e747466223b7d733a31353a2277617465726d61726b5f6973686f64223b613a32313a7b733a31363a226973686f645f6d657267654c6576656c223b693a3130303b733a31333a226973686f645f656e61626c6564223b4e3b733a31303a226973686f645f74797065223b733a333a22706e67223b733a31343a226973686f645f706e675f66696c65223b733a303a22223b733a31343a226973686f645f636f7079466c6167223b733a313a2230223b733a383a226973686f645f736d223b693a303b733a31383a226973686f645f706f736974696f6e466c6167223b733a313a2231223b733a31353a226973686f645f706f736974696f6e58223b693a303b733a31353a226973686f645f706f736974696f6e59223b693a303b733a31313a226973686f645f616c706861223b693a303b733a31303a226973686f645f74657874223b733a303a22223b733a32333a226973686f645f746578745f706f736974696f6e466c6167223b693a303b733a31303a226973686f645f73697a65223b693a303b733a31313a226973686f645f616e676c65223b693a303b733a32303a226973686f645f746578745f706f736974696f6e58223b693a303b733a32303a226973686f645f746578745f706f736974696f6e59223b693a303b733a31323a226973686f645f636f6c6f7252223b693a303b733a31323a226973686f645f636f6c6f7247223b693a303b733a31323a226973686f645f636f6c6f7242223b693a303b733a31363a226973686f645f746578745f616c706861223b693a303b733a31303a226973686f645f666f6e74223b733a31363a226e6f726f626f745f666f6e742e747466223b7d733a31343a226e6f776275795f656e61626c6564223b733a313a2232223b733a363a22656469746f72223b733a373a2274696e796d6365223b733a353a227468656d65223b733a373a2264656661756c74223b733a32343a22736d735f7374617475735f6f726465725f656e61626c6564223b693a303b733a31373a226d61696c5f736d74705f7265706c79746f223b733a303a22223b733a393a22736d735f70686f6e65223b733a303a22223b733a383a22736d735f75736572223b733a303a22223b733a383a22736d735f70617373223b733a303a22223b733a383a22736d735f6e616d65223b733a303a22223b733a393a226163655f7468656d65223b733a343a226461776e223b733a393a2261646d5f7469746c65223b733a303a22223b733a31343a227365617263685f656e61626c6564223b733a313a2233223b733a31343a226d61696c5f736d74705f686f7374223b733a303a22223b733a31343a226d61696c5f736d74705f706f7274223b733a303a22223b733a31343a226d61696c5f736d74705f75736572223b733a303a22223b733a31343a226d61696c5f736d74705f70617373223b733a303a22223b733a32303a22706172656e745f70726963655f656e61626c6564223b693a303b733a31373a226d61696c5f736d74705f656e61626c6564223b693a303b733a31353a226d61696c5f736d74705f6465627567223b693a303b733a31343a226d61696c5f736d74705f61757468223b693a303b733a31323a2272756c655f656e61626c6564223b693a303b733a31353a226361746c6973745f656e61626c6564223b733a313a2231223b733a31373a227265636170746368615f656e61626c6564223b733a313a2231223b733a31343a227265636170746368615f706b6579223b733a303a22223b733a31343a227265636170746368615f736b6579223b733a303a22223b733a31343a226461646174615f656e61626c6564223b733a313a2231223b733a31323a226461646174615f746f6b656e223b733a303a22223b733a32313a226d756c74695f63757272656e63795f736561726368223b693a303b733a31373a22696d6167655f726573756c745f70617468223b733a303a22223b733a31343a2277617465726d61726b5f74657874223b733a383a22594f55524c4f474f223b733a32303a2277617465726d61726b5f746578745f636f6c6f72223b733a373a2223636363636363223b733a31393a2277617465726d61726b5f746578745f73697a65223b733a323a223230223b733a31393a2277617465726d61726b5f746578745f666f6e74223b733a343a2256657261223b733a31353a2277617465726d61726b5f7269676874223b733a323a223230223b733a31363a2277617465726d61726b5f626f74746f6d223b733a323a223330223b733a32303a2277617465726d61726b5f746578745f616c706861223b733a323a223430223b733a31353a2277617465726d61726b5f696d616765223b733a303a22223b733a32313a22696d6167655f61646170746976655f726573697a65223b693a303b733a31353a22696d6167655f736176655f6e616d65223b693a303b733a32313a2277617465726d61726b5f6269675f656e61626c6564223b733a313a2231223b733a32343a2277617465726d61726b5f736f757263655f656e61626c6564223b693a303b733a31373a2279616e6465786d61705f656e61626c6564223b733a313a2231223b733a393a226875625f7468656d65223b733a32333a22626f6f7473747261702d7468656d652d64656661756c74223b733a31353a226875625f666c7569645f7468656d65223b733a32333a22626f6f7473747261702d7468656d652d64656661756c74223b733a32343a2277617465726d61726b5f63656e7465725f656e61626c6564223b693a303b733a31393a2266696c7465725f63616368655f706572696f64223b733a303a22223b733a32303a2266696c7465725f63616368655f656e61626c6564223b693a303b733a32313a2266696c7465725f70726f64756374735f636f756e74223b733a313a2231223b733a31323a2270726f6d6f5f6e6f74696365223b623a313b733a31353a22696d6167655f736176655f70617468223b693a303b733a31313a226e65775f656e61626c6564223b693a303b733a31323a22636861745f656e61626c6564223b693a303b733a31383a22696d6167655f736176655f636174616c6f67223b693a313b733a32333a2277617465726d61726b5f736d616c6c5f656e61626c6564223b693a303b733a31323a2261737465726f5f7468656d65223b733a32303a22626f6f7473747261702d7468656d652d626c7565223b733a31383a2261737465726f5f666c7569645f7468656d65223b733a32303a22626f6f7473747261702d7468656d652d626c7565223b733a31333a2261737465726f5f656469746f72223b4e3b733a31303a226c65676f5f7468656d65223b733a32333a22626f6f7473747261702d7468656d652d64656661756c74223b733a31363a226c65676f5f666c7569645f7468656d65223b733a32333a22626f6f7473747261702d7468656d652d64656661756c74223b733a31313a226c65676f5f656469746f72223b613a353a7b733a313a2268223b693a313b733a313a2266223b693a313b733a313a2263223b693a313b733a313a2270223b693a323b733a313a2273223b693a323b7d733a31333a226d6574726963615f746f6b656e223b733a303a22223b733a31303a226d6574726963615f6964223b733a303a22223b733a31333a2279616e6465785f6170696b6579223b733a303a22223b733a393a22676f6f676c655f6964223b733a303a22223b733a31353a226d6574726963615f656e61626c6564223b693a303b733a31343a226d6574726963615f776964676574223b693a303b733a31373a226d6574726963615f65636f6d6d65726365223b693a303b733a31343a22676f6f676c655f656e61626c6564223b693a303b733a31363a22676f6f676c655f616e616c6974696373223b693a303b733a343a226c616e67223b733a373a227275737369616e223b733a31373a22736b6c61645f73756d5f656e61626c6564223b693a303b733a31373a22696d6167655f6469616c6f675f70617468223b733a303a22223b733a31313a22636861745f6469616c6f67223b733a313a2231223b733a31323a227469746c655f6469616c6f67223b733a303a22223b733a31313a22746578745f6469616c6f67223b733a303a22223b733a31363a2274696d655f66726f6d5f6469616c6f67223b733a313a2238223b733a31373a2274696d655f756e74696c5f6469616c6f67223b733a323a223230223b733a31303a226461795f6469616c6f67223b733a313a2231223b733a31323a22636f6c6f725f6469616c6f67223b733a373a2223343261356635223b733a31333a226d617267696e5f6469616c6f67223b733a313a2230223b733a31313a2273697a655f6469616c6f67223b733a323a223536223b733a31323a2273697a656d5f6469616c6f67223b733a323a223536223b733a31323a2274656c656772616d5f626f74223b733a303a22223b733a31343a2274656c656772616d5f61646d696e223b733a303a22223b733a31343a2274656c656772616d5f746f6b656e223b733a303a22223b733a363a22766b5f626f74223b733a303a22223b733a31353a22766b5f636f6e6669726d6174696f6e223b733a303a22223b733a393a22766b5f736563726574223b733a303a22223b733a383a22766b5f61646d696e223b733a303a22223b733a383a22766b5f746f6b656e223b733a303a22223b733a31363a2274656c656772616d5f656e61626c6564223b693a303b733a31303a22766b5f656e61626c6564223b693a303b733a31353a2274656c656772616d5f6469616c6f67223b693a303b733a393a22766b5f6469616c6f67223b693a303b733a31313a226d61696c5f6469616c6f67223b693a303b733a31313a22707573685f6469616c6f67223b693a303b733a31343a2274656c656772616d5f6f72646572223b693a303b733a383a22766b5f6f72646572223b693a303b733a31323a226d6f62696c5f6469616c6f67223b693a303b733a31303a2274656c5f6469616c6f67223b693a303b733a31353a2274696d655f6f66665f6469616c6f67223b693a303b733a31333a226176617461725f6469616c6f67223b733a33383a222f70687073686f702f6c69622f74656d706c617465732f636861742f6176617461722e706e67223b733a31333a226361746c6973745f6465707468223b733a313a2233223b733a31313a227365617263685f706f6c65223b733a313a2231223b733a383a2274696d657a6f6e65223b733a303a22223b733a31353a22757365725f70686f6e655f6d61736b223b733a31343a222839393929203939392d39393939223b733a353a22626f6e7573223b733a313a2230223b733a31313a226f726465725f626f6e7573223b733a313a2230223b733a383a226c616e675f61646d223b733a373a227275737369616e223b733a32303a22757365725f736572766572735f636f6e74726f6c223b693a303b733a32333a22757365725f70686f6e655f6d61736b5f656e61626c6564223b693a303b733a31393a22757365725f6974656d735f6163746976617465223b693a303b733a31313a22616a61785f7363726f6c6c223b693a303b733a32313a22616a61785f7363726f6c6c5f706167696e61746f72223b693a303b7d, 6, 'PHPShop – это готовое решение для быстрого создания интернет-магазина.', '@Podcatalog@, @Catalog@, @System@', '@Podcatalog@ - @Catalog@ - @System@', '@Podcatalog@, @Catalog@, @Generator@', '@Product@ - @Podcatalog@ - @Catalog@', '@Product@, @Podcatalog@, @Catalog@', '@Product@,@System@', '/UserFiles/Image/Trial/php_logo.svg', '@Catalog@ - @System@', '@Catalog@', '@Catalog@', 0, '0', '0', 0x613a373a7b733a31313a227570646174655f6e616d65223b733a313a2231223b733a31343a227570646174655f636f6e74656e74223b733a313a2231223b733a31383a227570646174655f6465736372697074696f6e223b733a313a2231223b733a31353a227570646174655f63617465676f7279223b733a313a2231223b733a31313a227570646174655f736f7274223b733a313a2231223b733a31323a227570646174655f7072696365223b733a313a2231223b733a31313a227570646174655f6974656d223b733a313a2231223b7d, '', '');

DROP TABLE IF EXISTS phpshop_templates_key;
CREATE TABLE phpshop_templates_key (
  `path` varchar(64) NOT NULL DEFAULT '',
  `date` int(11) DEFAULT '0',
  `key` text,
  `verification` varchar(32) DEFAULT '',
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_users;
CREATE TABLE phpshop_users (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` blob,
  `login` varchar(64) DEFAULT '',
  `password` varchar(64) DEFAULT '',
  `mail` varchar(64) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '1',
  `name` varchar(255) DEFAULT '',
  `hash` varchar(255) DEFAULT '',
  `token` VARCHAR(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS phpshop_valuta;
CREATE TABLE phpshop_valuta (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `code` varchar(64) DEFAULT '',
  `iso` varchar(64) DEFAULT '',
  `kurs` varchar(64) DEFAULT '0',
  `num` tinyint(11) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

INSERT INTO phpshop_valuta VALUES ('4', 'Гривны', 'грн', 'UAH', '0.061', '4', '1');
INSERT INTO phpshop_valuta VALUES ('5', 'Доллары', '$', 'USD', '0.016', '0', '1');
INSERT INTO phpshop_valuta VALUES ('6', 'Рубли', 'руб.', 'RUB', '1', '1', '1');
DROP TABLE IF EXISTS phpshop_warehouses;
CREATE TABLE phpshop_warehouses (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `uid` varchar(64) DEFAULT NULL,
  `enabled` enum('0','1') DEFAULT '1',
  `num` int(11) DEFAULT NULL,
  `servers` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS `phpshop_modules_yandexkassa_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexkassa_system` (
  `id` int(11) NOT NULL auto_increment,
  `status` int(11) NOT NULL,
  `title` text NOT NULL,
  `title_end` text NOT NULL,
  `shop_id` varchar(64) NOT NULL default '',
  `api_key` varchar(255) NOT NULL default '',
  `version` varchar(64) DEFAULT '1.5' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_yandexkassa_system` (`id`, `status`, `title`, `title_end`, `shop_id`, `api_key`, `version`) VALUES
(1, 0, 'Оплатить сейчас', 'Оплатите пожалуйста свой заказ', '665601', 'test_IBkYJDzgL1-gaz04YTHNxQekxtaGz6z-7_40u0rRlYs', 1.5);

INSERT INTO `phpshop_payment_systems` (`id`, `name`, `path`, `enabled`, `num`, `message`, `message_header`, `yur_data_flag`, `icon`) VALUES
(10004, 'ЮKassa', 'modules', '0', 0, '', '', '', '/UserFiles/Image/Payments/yandex-money.png');

CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexkassa_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `yandex_id` varchar(255) NULL,
  `status_code` varchar(255) NULL,
  `type` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

CREATE TABLE `phpshop_push` (
  `token` text,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `phpshop_bonus` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `comment` varchar(255) default '',
  `user_id` int(11),
  `order_id` int(11),
  `bonus_operation` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `phpshop_exchanges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `option` blob,
  `type` varchar(64),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

CREATE TABLE `phpshop_notes` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `message` text ,
  `status` int(11) default '0',
  `name` VARCHAR(64), 
  `tel` VARCHAR(64),
  `mail` VARCHAR(64),
  `content` TEXT,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `phpshop_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `bank` blob,
  `enabled` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `phpshop_dialog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11),
  `name` varchar(64),
  `message` text,
  `chat_id` int(11),
  `time` int(11),
  `bot` varchar(64),
  `staffid` enum('0','1') DEFAULT '1',
  `isview` enum('0','1') DEFAULT '1',
  `order_id` INT(11) DEFAULT '0',
  `attachments` VARCHAR(255),
  `isview_user` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

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
