
--
-- Структура таблицы `phpshop_1c_docs`
--

CREATE TABLE `phpshop_1c_docs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `cid` varchar(64) DEFAULT '',
  `datas` int(11) DEFAULT '0',
  `datas_f` int(11) DEFAULT '0',
  `year` int(11) DEFAULT '2018',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_1c_jurnal`
--

CREATE TABLE `phpshop_1c_jurnal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datas` varchar(64) DEFAULT '0',
  `p_name` varchar(64) DEFAULT '',
  `f_name` varchar(64) DEFAULT '',
  `time` float DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_baners`
--

CREATE TABLE `phpshop_baners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `content` text,
  `count_all` int(11) DEFAULT '0',
  `count_today` int(11) DEFAULT '0',
  `flag` enum('0','1') DEFAULT '0',
  `datas` varchar(32) DEFAULT '',
  `limit_all` int(11) DEFAULT '0',
  `dir` varchar(255) DEFAULT '',
  `dop_cat` varchar(255) default '',
  `servers` varchar(64) default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_baners`
--

INSERT INTO `phpshop_baners` (`id`, `name`, `content`, `count_all`, `count_today`, `flag`, `datas`, `limit_all`, `dir`) VALUES
(1, 'Одежда', '<div><img src="/UserFiles/Image/Trial/slider/banner-1.jpg" alt="Баннер сайта" /></div>', 0, 0, '1', '', 0, 'odezhda,obuv-i-aksessuary'),
(2, 'Косметика', '<p><img src="/UserFiles/Image/Trial/slider/banner-2.jpg" alt="" width="1170" height="200" /></p>', 0, 0, '1', '', 0, 'kosmetika,lico,glaza,guby'),
(3, 'Техника', '<p><img src="/UserFiles/Image/Trial/slider/banner-3.jpg" alt="" width="1170" height="200" /></p>', 0, 0, '1', '', 0, 'high-tech');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_black_list`
--

CREATE TABLE `phpshop_black_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT '',
  `datas` varchar(32) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_categories`
--

CREATE TABLE `phpshop_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `num` int(11) DEFAULT '0',
  `parent_to` int(11) NOT NULL DEFAULT '0',
  `yml` enum('0','1') DEFAULT '1',
  `num_row` enum('1','2','3','4') DEFAULT '2',
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
  `count` int(11) DEFAULT '0',
  `cat_seo_name` varchar(255) DEFAULT '',
  `dop_cat` varchar(255) DEFAULT '',
  `parent_title` int(11) DEFAULT '0',
  `sort_cache` blob,
  `sort_cache_created_at` int(11),
  `cat_seo_name_old` VARCHAR(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent_to` (`parent_to`),
  KEY `servers` (`servers`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_categories`
--

INSERT INTO `phpshop_categories` (`id`, `name`, `num`, `parent_to`, `yml`, `num_row`, `num_cow`, `sort`, `content`, `vid`, `name_rambler`, `servers`, `title`, `title_enabled`, `title_shablon`, `descrip`, `descrip_enabled`, `descrip_shablon`, `keywords`, `keywords_enabled`, `keywords_shablon`, `skin`, `skin_enabled`, `order_by`, `order_to`, `secure_groups`, `icon`, `icon_description`, `count`, `dop_cat`, `parent_title`, `cat_seo_name`) VALUES
(1, 'Одежда', 5, 0, '0', '4', 0, 0x613a333a7b693a303b733a313a2236223b693a313b733a313a2237223b693a323b733a313a2238223b7d, '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/catalog-11.jpg', '', 0, '', 0, ''),
(2, 'Косметика', 1, 0, '0', '3', 0, 0x4e3b, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/catalog-10.jpg', '', 0, '', 0, ''),
(3, 'Спорт', 2, 0, '0', '3', 0, 0x4e3b, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/catalog-1.png', '', 0, '', 0, ''),
(4, 'Обувь и аксессуары', 3, 0, '0', '3', 0, 0x613a333a7b693a303b733a313a2236223b693a313b733a313a2237223b693a323b733a313a2238223b7d, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/catalog-9.jpg', '', 0, '', 0, ''),
(5, 'Мебель', 6, 0, '0', '3', 0, 0x613a323a7b693a303b733a323a223135223b693a313b733a323a223134223b7d, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/catalog-8.jpg', '', 0, '', 0, ''),
(6, 'High-tech', 4, 0, '0', '3', 0, 0x613a323a7b693a303b733a323a223133223b693a313b733a323a223132223b7d, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '3', '1', '', '/UserFiles/Image/Trial/catalog-7.jpg', '', 0, '', 2, ''),
(7, 'Лицо', 1, 2, '0', '3', 0, 0x613a323a7b693a303b733a313a2231223b693a313b733a313a2233223b7d, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/catalog-6.png', '', 0, '', 0, ''),
(8, 'Глаза', 1, 2, '0', '3', 0, 0x613a323a7b693a303b733a313a2231223b693a313b733a313a2233223b7d, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/catalog-5.jpg', '', 0, '', 0, ''),
(9, 'Губы', 1, 2, '0', '3', 0, 0x613a323a7b693a303b733a313a2231223b693a313b733a313a2233223b7d, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/catalog-4.jpg', '', 0, '', 0, ''),
(10, 'Спортивное питание', 1, 3, '0', '4', 0, 0x4e3b, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/catalog-3.jpg', '', 0, '', 0, ''),
(11, 'Кроссовки', 1, 3, '0', '3', 0, 0x613a313a7b693a303b733a313a2236223b7d, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/catalog-2.jpg', '', 0, '', 0, ''),
(12, 'Инвентарь', 1, 3, '0', '3', 0, 0x4e3b, '', '0', '', '', '', '0', '', '', '0', '', '', '0', '', '', '0', '1', '1', '', '/UserFiles/Image/Trial/catalog-1.png', '', 0, '', 1, '');


-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_citylist_city`
--

CREATE TABLE `phpshop_citylist_city` (
  `city_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(11) unsigned DEFAULT '0',
  `region_id` int(10) unsigned DEFAULT '0',
  `name` varchar(128) DEFAULT '',
  PRIMARY KEY (`city_id`),
  KEY `country_id` (`country_id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_citylist_country`
--

CREATE TABLE `phpshop_citylist_country` (
  `country_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT '0',
  `name` varchar(128) DEFAULT '',
  PRIMARY KEY (`country_id`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_citylist_region`
--

CREATE TABLE `phpshop_citylist_region` (
  `region_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10) unsigned DEFAULT '0',
  `city_id` int(10) unsigned DEFAULT '0',
  `name` varchar(64) DEFAULT '',
  PRIMARY KEY (`region_id`),
  KEY `country_id` (`country_id`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_comment`
--

CREATE TABLE `phpshop_comment` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_comment`
--

INSERT INTO `phpshop_comment` (`id`, `datas`, `name`, `parent_id`, `content`, `user_id`, `enabled`, `rate`) VALUES
(1, '1523371255', 'Руслан', 16, 'Прекрасная помада, ухаживает хорошо, губы теперь довольны-) ', 19, '1', 4),
(2, '1523371303', 'Руслан', 17, 'Хорошая тушь', 19, '1', 4),
(3, '1523371313', 'Руслан', 1, 'Отлично!', 19, '1', 5),
(4, '1523371326', 'Руслан', 61, 'удобный стул', 19, '1', 4),
(5, '1523371341', 'Руслан', 58, 'неудобное кресло', 19, '1', 5),
(6, '1523371356', 'Руслан', 57, 'Хорошая кровать', 19, '1', 5),
(7, '1523371367', 'Руслан', 60, 'Прекрасные часы', 19, '1', 5),
(8, '1523371380', 'Руслан', 59, 'Лампа нравится', 19, '1', 5),
(9, '1523371394', 'Руслан', 45, 'отлично!', 19, '1', 5),
(10, '1523371404', 'Руслан', 53, 'Удобные!', 19, '1', 5),
(11, '1523371417', 'Руслан', 44, 'классный протеин', 19, '1', 5),
(12, '1523371431', 'Руслан', 52, 'удобная обувь', 19, '1', 5),
(13, '1523371440', 'Руслан', 50, 'хорошее качество', 19, '1', 5),
(14, '1523371455', 'Руслан', 2, 'ухаживает ', 19, '1', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_delivery`
--

CREATE TABLE `phpshop_delivery` (
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
  `servers` varchar(64) default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_delivery`
--

INSERT INTO `phpshop_delivery` (`id`, `city`, `price`, `enabled`, `flag`, `price_null`, `price_null_enabled`, `PID`, `taxa`, `is_folder`, `city_select`, `data_fields`, `num`, `icon`, `payment`, `ofd_nds`) VALUES
(1, 'Москва', 0, '1', '1', 0, '0', 0, 0, '1', '0', '', 0, '/UserFiles/Image/Payments/city.png', '', ''),
(3, 'Доставка курьером в пределах МКАД', 180, '1', '1', 2000, '1', 1, 1, '0', '0', 0x613a323a7b733a373a22656e61626c6564223b613a31323a7b733a373a22636f756e747279223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a353a227374617465223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a343a2263697479223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a353a22696e646578223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a333a2266696f223b613a323a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a333a22d4c8ce223b7d733a333a2274656c223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a373a22d2e5ebe5f4eeed223b733a333a22726571223b733a313a2231223b7d733a363a22737472656574223b613a313a7b733a343a226e616d65223b733a353a22d3ebe8f6e0223b7d733a353a22686f757365223b613a313a7b733a343a226e616d65223b733a333a22c4eeec223b7d733a353a22706f726368223b613a313a7b733a343a226e616d65223b733a373a22cfeee4fae5e7e4223b7d733a31303a22646f6f725f70686f6e65223b613a313a7b733a343a226e616d65223b733a31323a22caeee420e4eeeceef4eeede0223b7d733a343a22666c6174223b613a313a7b733a343a226e616d65223b733a383a22cae2e0f0f2e8f0e0223b7d733a393a2264656c697674696d65223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31363a22caeee3e4e020e4eef1f2e0e2e8f2fc3f223b733a333a22726571223b733a313a2231223b7d7d733a333a226e756d223b613a31323a7b733a373a22636f756e747279223b733a303a22223b733a353a227374617465223b733a303a22223b733a343a2263697479223b733a303a22223b733a353a22696e646578223b733a303a22223b733a333a2266696f223b733a313a2231223b733a333a2274656c223b733a313a2232223b733a363a22737472656574223b733a313a2233223b733a353a22686f757365223b733a313a2234223b733a353a22706f726368223b733a313a2235223b733a31303a22646f6f725f70686f6e65223b733a313a2236223b733a343a22666c6174223b733a313a2237223b733a393a2264656c697674696d65223b733a313a2238223b7d7d, 0, '/UserFiles/Image/Payments/courier.png', '', ''),
(4, 'Доставка курьером за пределами МКАД', 300, '1', '0', 0, '0', 1, 0, '0', '0', 0x613a323a7b733a373a22656e61626c6564223b613a31323a7b733a373a22636f756e747279223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a353a227374617465223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a343a2263697479223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a353a22696e646578223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a333a2266696f223b613a313a7b733a343a226e616d65223b733a333a22d4c8ce223b7d733a333a2274656c223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a373a22d2e5ebe5f4eeed223b733a333a22726571223b733a313a2231223b7d733a363a22737472656574223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a353a22d3ebe8f6e0223b733a333a22726571223b733a313a2231223b7d733a353a22686f757365223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a333a22c4eeec223b733a333a22726571223b733a313a2231223b7d733a353a22706f726368223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a373a22cfeee4fae5e7e4223b733a333a22726571223b733a313a2231223b7d733a31303a22646f6f725f70686f6e65223b613a323a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31323a22caeee420e4eeeceef4eeede0223b7d733a343a22666c6174223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a383a22cae2e0f0f2e8f0e0223b733a333a22726571223b733a313a2231223b7d733a393a2264656c697674696d65223b613a313a7b733a343a226e616d65223b733a303a22223b7d7d733a333a226e756d223b613a31323a7b733a373a22636f756e747279223b733a303a22223b733a353a227374617465223b733a303a22223b733a343a2263697479223b733a303a22223b733a353a22696e646578223b733a303a22223b733a333a2266696f223b733a313a2231223b733a333a2274656c223b733a313a2232223b733a363a22737472656574223b733a313a2233223b733a353a22686f757365223b733a313a2234223b733a353a22706f726368223b733a313a2235223b733a31303a22646f6f725f70686f6e65223b733a313a2236223b733a343a22666c6174223b733a313a2237223b733a393a2264656c697674696d65223b733a303a22223b7d7d, 0, '/UserFiles/Image/Payments/courier-za-mkad.png', '', ''),
(7, 'Россия', 0, '1', '', 0, '0', 0, 0, '1', '0', '', 1, '/UserFiles/Image/Payments/russia.png', '', ''),
(8, 'EMS', 500, '1', '0', 5000, '1', 7, 50, '0', '1', 0x613a323a7b733a373a22656e61626c6564223b613a31323a7b733a373a22636f756e747279223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a353a227374617465223b613a313a7b733a343a226e616d65223b733a303a22223b7d733a343a2263697479223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a353a22c3eef0eee4223b733a333a22726571223b733a313a2231223b7d733a353a22696e646578223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a363a22c8ede4e5eaf1223b733a333a22726571223b733a313a2231223b7d733a333a2266696f223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31343a22d4c8ce20efeeebf3f7e0f2e5ebff223b733a333a22726571223b733a313a2231223b7d733a333a2274656c223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31383a22d2e5ebe5f4eeed20efeeebf3f7e0f2e5ebff223b733a333a22726571223b733a313a2231223b7d733a363a22737472656574223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a353a22d3ebe8f6e0223b733a333a22726571223b733a313a2231223b7d733a353a22686f757365223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a333a22c4eeec223b733a333a22726571223b733a313a2231223b7d733a353a22706f726368223b613a323a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a373a22cfeee4fae5e7e4223b7d733a31303a22646f6f725f70686f6e65223b613a323a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31323a22caeee420e4eeeceef4eeede0223b7d733a343a22666c6174223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a383a22cae2e0f0f2e8f0e0223b733a333a22726571223b733a313a2231223b7d733a393a2264656c697674696d65223b613a313a7b733a343a226e616d65223b733a303a22223b7d7d733a333a226e756d223b613a31323a7b733a373a22636f756e747279223b733a303a22223b733a353a227374617465223b733a303a22223b733a343a2263697479223b733a313a2231223b733a353a22696e646578223b733a313a2232223b733a333a2266696f223b733a313a2233223b733a333a2274656c223b733a313a2234223b733a363a22737472656574223b733a313a2235223b733a353a22686f757365223b733a313a2236223b733a353a22706f726368223b733a313a2237223b733a31303a22646f6f725f70686f6e65223b733a313a2238223b733a343a22666c6174223b733a313a2239223b733a393a2264656c697674696d65223b733a303a22223b7d7d, 1, '/UserFiles/Image/Payments/ems.png', '', ''),
(9, 'Почта России', 900, '1', '0', 5000, '1', 7, 60, '0', '1', 0x613a323a7b733a373a22656e61626c6564223b613a31323a7b733a373a22636f756e747279223b613a313a7b733a343a226e616d65223b733a363a22d1f2f0e0ede0223b7d733a353a227374617465223b613a313a7b733a343a226e616d65223b733a31313a22d0e5e3e8eeed2ff8f2e0f2223b7d733a343a2263697479223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a353a22c3eef0eee4223b733a333a22726571223b733a313a2231223b7d733a353a22696e646578223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a363a22c8ede4e5eaf1223b733a333a22726571223b733a313a2231223b7d733a333a2266696f223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31343a22d4c8ce20efeeebf3f7e0f2e5ebff223b733a333a22726571223b733a313a2231223b7d733a333a2274656c223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31383a22d2e5ebe5f4eeed20efeeebf3f7e0f2e5ebff223b733a333a22726571223b733a313a2231223b7d733a363a22737472656574223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a353a22d3ebe8f6e0223b733a333a22726571223b733a313a2231223b7d733a353a22686f757365223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a333a22c4eeec223b733a333a22726571223b733a313a2231223b7d733a353a22706f726368223b613a323a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a373a22cfeee4fae5e7e4223b7d733a31303a22646f6f725f70686f6e65223b613a323a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31323a22caeee420e4eeeceef4eeede0223b7d733a343a22666c6174223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a383a22cae2e0f0f2e8f0e0223b733a333a22726571223b733a313a2231223b7d733a393a2264656c697674696d65223b613a313a7b733a343a226e616d65223b733a31343a22c2f0e5ecff20e4eef1f2e0e2eae8223b7d7d733a333a226e756d223b613a31323a7b733a373a22636f756e747279223b733a303a22223b733a353a227374617465223b733a313a2232223b733a343a2263697479223b733a313a2231223b733a353a22696e646578223b733a313a2232223b733a333a2266696f223b733a313a2233223b733a333a2274656c223b733a313a2234223b733a363a22737472656574223b733a313a2235223b733a353a22686f757365223b733a313a2236223b733a353a22706f726368223b733a313a2237223b733a31303a22646f6f725f70686f6e65223b733a313a2238223b733a343a22666c6174223b733a313a2239223b733a393a2264656c697674696d65223b733a323a223132223b7d7d, 2, '/UserFiles/Image/Payments/pochta-rf.png', 'null', '18'),
(12, 'За пределы России', 0, '1', '0', 0, '', 0, 0, '1', '0', '', 3, '/UserFiles/Image/Payments/world.png', '', ''),
(13, 'DHL', 0, '1', '0', 0, '0', 12, 0, '0', '2', 0x613a323a7b733a373a22656e61626c6564223b613a31323a7b733a373a22636f756e747279223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a363a22d1f2f0e0ede0223b733a333a22726571223b733a313a2231223b7d733a353a227374617465223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a363a22d0e5e3e8eeed223b733a333a22726571223b733a313a2231223b7d733a343a2263697479223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a353a22c3eef0eee4223b733a333a22726571223b733a313a2231223b7d733a353a22696e646578223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a363a22c8ede4e5eaf1223b733a333a22726571223b733a313a2231223b7d733a333a2266696f223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31343a22d4c8ce20efeeebf3f7e0f2e5ebff223b733a333a22726571223b733a313a2231223b7d733a333a2274656c223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31383a22d2e5ebe5f4eeed20efeeebf3f7e0f2e5ebff223b733a333a22726571223b733a313a2231223b7d733a363a22737472656574223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a353a22d3ebe8f6e0223b733a333a22726571223b733a313a2231223b7d733a353a22686f757365223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a333a22c4eeec223b733a333a22726571223b733a313a2231223b7d733a353a22706f726368223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a373a22cfeee4fae5e7e4223b733a333a22726571223b733a313a2231223b7d733a31303a22646f6f725f70686f6e65223b613a323a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a31323a22caeee420e4eeeceef4eeede0223b7d733a343a22666c6174223b613a333a7b733a373a22656e61626c6564223b733a313a2231223b733a343a226e616d65223b733a383a22cae2e0f0f2e8f0e0223b733a333a22726571223b733a313a2231223b7d733a393a2264656c697674696d65223b613a313a7b733a343a226e616d65223b733a303a22223b7d7d733a333a226e756d223b613a31323a7b733a373a22636f756e747279223b733a313a2231223b733a353a227374617465223b733a313a2232223b733a343a2263697479223b733a313a2233223b733a353a22696e646578223b733a313a2234223b733a333a2266696f223b733a313a2235223b733a333a2274656c223b733a313a2236223b733a363a22737472656574223b733a313a2237223b733a353a22686f757365223b733a313a2238223b733a353a22706f726368223b733a313a2239223b733a31303a22646f6f725f70686f6e65223b733a323a223130223b733a343a22666c6174223b733a323a223131223b733a393a2264656c697674696d65223b733a303a22223b7d7d, 0, '/UserFiles/Image/Payments/dhl.png', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_discount`
--

CREATE TABLE `phpshop_discount` (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `sum` int(255) DEFAULT '0',
  `discount` float DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_foto`
--

CREATE TABLE `phpshop_foto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `num` tinyint(11) DEFAULT '0',
  `info` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_foto`
--

INSERT INTO `phpshop_foto` (`id`, `parent`, `name`, `num`, `info`) VALUES
(15, 1, '/UserFiles/Image/Trial/img1_90545.png', 0, ''),
(13, 1, '/UserFiles/Image/Trial/img1_22472.png', 0, ''),
(16, 1, '/UserFiles/Image/Trial/img1_36459.png', 0, ''),
(9, 2, '/UserFiles/Image/Trial/img2_78894.png', 0, ''),
(10, 2, '/UserFiles/Image/Trial/img2_54984.png', 0, ''),
(11, 2, '/UserFiles/Image/Trial/img2_35744.png', 0, ''),
(17, 3, '/UserFiles/Image/Trial/img3_16362.png', 0, ''),
(18, 3, '/UserFiles/Image/Trial/img3_72208.png', 0, ''),
(19, 3, '/UserFiles/Image/Trial/img3_17672.png', 0, ''),
(20, 4, '/UserFiles/Image/Trial/img4_42389.png', 0, ''),
(21, 4, '/UserFiles/Image/Trial/img4_20386.png', 0, ''),
(22, 4, '/UserFiles/Image/Trial/img4_10290.png', 0, ''),
(24, 5, '/UserFiles/Image/Trial/img5_19433.png', 0, ''),
(25, 5, '/UserFiles/Image/Trial/img5_38508.jpg', 0, ''),
(26, 5, '/UserFiles/Image/Trial/img5_18142.jpg', 0, ''),
(27, 6, '/UserFiles/Image/Trial/img6_66962.jpg', 0, ''),
(31, 7, '/UserFiles/Image/Trial/img7_29009.png', 0, ''),
(29, 6, '/UserFiles/Image/Trial/img6_36814.png', 0, ''),
(32, 7, '/UserFiles/Image/Trial/img7_11718.png', 0, ''),
(33, 7, '/UserFiles/Image/Trial/img7_29787.png', 0, ''),
(34, 8, '/UserFiles/Image/Trial/img8_36236.png', 0, ''),
(35, 8, '/UserFiles/Image/Trial/img8_79720.png', 0, ''),
(36, 8, '/UserFiles/Image/Trial/img8_38982.png', 0, ''),
(37, 9, '/UserFiles/Image/Trial/img9_60887.png', 0, ''),
(38, 9, '/UserFiles/Image/Trial/img9_10863.jpg', 0, ''),
(39, 9, '/UserFiles/Image/Trial/img9_13210.jpg', 0, ''),
(40, 10, '/UserFiles/Image/Trial/img10_31391.jpg', 0, ''),
(41, 10, '/UserFiles/Image/Trial/img10_91260.png', 0, ''),
(42, 11, '/UserFiles/Image/Trial/img11_37520.png', 0, ''),
(43, 11, '/UserFiles/Image/Trial/img11_26365.png', 0, ''),
(44, 11, '/UserFiles/Image/Trial/img11_28823.png', 0, ''),
(45, 12, '/UserFiles/Image/Trial/img12_26231.png', 0, ''),
(46, 12, '/UserFiles/Image/Trial/img12_35324.png', 0, ''),
(47, 12, '/UserFiles/Image/Trial/img12_70307.png', 0, ''),
(48, 13, '/UserFiles/Image/Trial/img13_22815.png', 0, ''),
(49, 13, '/UserFiles/Image/Trial/img13_79095.png', 0, ''),
(50, 13, '/UserFiles/Image/Trial/img13_21459.png', 0, ''),
(51, 14, '/UserFiles/Image/Trial/img14_30791.png', 0, ''),
(52, 14, '/UserFiles/Image/Trial/img14_23300.png', 0, ''),
(53, 14, '/UserFiles/Image/Trial/img14_27432.png', 0, ''),
(54, 14, '/UserFiles/Image/Trial/img14_34523.png', 0, ''),
(55, 14, '/UserFiles/Image/Trial/img14_44194.png', 0, ''),
(56, 14, '/UserFiles/Image/Trial/img14_42250.png', 0, ''),
(57, 15, '/UserFiles/Image/Trial/img15_29803.png', 0, ''),
(58, 15, '/UserFiles/Image/Trial/img15_39077.jpg', 0, ''),
(59, 15, '/UserFiles/Image/Trial/img15_38381.jpg', 0, ''),
(60, 16, '/UserFiles/Image/Trial/img16_18757.jpg', 0, ''),
(61, 16, '/UserFiles/Image/Trial/img16_18997.png', 0, ''),
(62, 17, '/UserFiles/Image/Trial/img17_42014.png', 0, ''),
(63, 17, '/UserFiles/Image/Trial/img17_15588.png', 0, ''),
(64, 17, '/UserFiles/Image/Trial/img17_26915.png', 0, ''),
(65, 18, '/UserFiles/Image/Trial/img18_30994.png', 0, ''),
(66, 18, '/UserFiles/Image/Trial/img18_22108.png', 0, ''),
(67, 18, '/UserFiles/Image/Trial/img18_31371.png', 0, ''),
(74, 19, '/UserFiles/Image/Trial/img19_71648.jpg', 0, ''),
(75, 19, '/UserFiles/Image/Trial/img19_78131.jpg', 0, ''),
(73, 19, '/UserFiles/Image/Trial/img19_70097.jpg', 0, ''),
(76, 20, '/UserFiles/Image/Trial/img20_48320.jpg', 0, ''),
(77, 20, '/UserFiles/Image/Trial/img20_87409.jpg', 0, ''),
(78, 20, '/UserFiles/Image/Trial/img20_11241.jpg', 0, ''),
(79, 21, '/UserFiles/Image/Trial/img21_15578.jpg', 0, ''),
(80, 21, '/UserFiles/Image/Trial/img21_81261.jpg', 0, ''),
(81, 21, '/UserFiles/Image/Trial/img21_96204.jpg', 0, ''),
(82, 22, '/UserFiles/Image/Trial/img22_32410.jpg', 0, ''),
(83, 22, '/UserFiles/Image/Trial/img22_26871.jpg', 0, ''),
(84, 22, '/UserFiles/Image/Trial/img22_73532.jpg', 0, ''),
(85, 23, '/UserFiles/Image/Trial/img23_89786.jpg', 0, ''),
(86, 23, '/UserFiles/Image/Trial/img23_29368.jpg', 0, ''),
(89, 24, '/UserFiles/Image/Trial/img24_16757.jpg', 0, ''),
(88, 23, '/UserFiles/Image/Trial/img23_26114.jpg', 0, ''),
(90, 24, '/UserFiles/Image/Trial/img24_17045.jpg', 0, ''),
(91, 24, '/UserFiles/Image/Trial/img24_54220.jpg', 0, ''),
(92, 25, '/UserFiles/Image/Trial/img25_64682.jpg', 0, ''),
(93, 25, '/UserFiles/Image/Trial/img25_53495.jpg', 0, ''),
(94, 25, '/UserFiles/Image/Trial/img25_66005.jpg', 0, ''),
(95, 26, '/UserFiles/Image/Trial/img26_53995.jpg', 0, ''),
(96, 26, '/UserFiles/Image/Trial/img26_56539.jpg', 0, ''),
(97, 26, '/UserFiles/Image/Trial/img26_44824.jpg', 0, ''),
(98, 27, '/UserFiles/Image/Trial/img27_18062.jpg', 0, ''),
(99, 27, '/UserFiles/Image/Trial/img27_31130.jpg', 0, ''),
(100, 27, '/UserFiles/Image/Trial/img27_55301.jpg', 0, ''),
(101, 28, '/UserFiles/Image/Trial/img28_13935.jpg', 0, ''),
(102, 28, '/UserFiles/Image/Trial/img28_40283.jpg', 0, ''),
(103, 28, '/UserFiles/Image/Trial/img28_54487.jpg', 0, ''),
(104, 28, '/UserFiles/Image/Trial/img28_39845.jpg', 0, ''),
(105, 29, '/UserFiles/Image/Trial/img29_12250.jpg', 0, ''),
(106, 29, '/UserFiles/Image/Trial/img29_28701.jpg', 0, ''),
(107, 29, '/UserFiles/Image/Trial/img29_34655.jpg', 0, ''),
(108, 29, '/UserFiles/Image/Trial/img29_38525.jpg', 0, ''),
(109, 29, '/UserFiles/Image/Trial/img29_20915.jpg', 0, ''),
(110, 30, '/UserFiles/Image/Trial/img30_33310.jpg', 0, ''),
(111, 30, '/UserFiles/Image/Trial/img30_29678.jpg', 0, ''),
(112, 30, '/UserFiles/Image/Trial/img30_32460.jpg', 0, ''),
(113, 31, '/UserFiles/Image/Trial/img31_22065.jpg', 0, ''),
(114, 31, '/UserFiles/Image/Trial/img31_41341.jpg', 0, ''),
(115, 31, '/UserFiles/Image/Trial/img31_23535.jpg', 0, ''),
(116, 32, '/UserFiles/Image/Trial/img32_23840.jpg', 0, ''),
(117, 32, '/UserFiles/Image/Trial/img32_25817.jpg', 0, ''),
(118, 32, '/UserFiles/Image/Trial/img32_29755.jpg', 0, ''),
(119, 33, '/UserFiles/Image/Trial/img33_61292.jpg', 0, ''),
(120, 33, '/UserFiles/Image/Trial/img33_53487.jpg', 0, ''),
(121, 33, '/UserFiles/Image/Trial/img33_32374.jpg', 0, ''),
(122, 34, '/UserFiles/Image/Trial/img34_60925.jpg', 0, ''),
(123, 34, '/UserFiles/Image/Trial/img34_62276.jpg', 0, ''),
(124, 34, '/UserFiles/Image/Trial/img34_57469.jpg', 0, ''),
(125, 35, '/UserFiles/Image/Trial/img35_21932.jpg', 0, ''),
(126, 35, '/UserFiles/Image/Trial/img35_98263.jpg', 0, ''),
(127, 35, '/UserFiles/Image/Trial/img35_99318.jpg', 0, ''),
(128, 36, '/UserFiles/Image/Trial/img36_62837.jpg', 0, ''),
(129, 36, '/UserFiles/Image/Trial/img36_52701.jpg', 0, ''),
(130, 36, '/UserFiles/Image/Trial/img36_93859.jpg', 0, ''),
(131, 37, '/UserFiles/Image/Trial/img37_19544.jpg', 0, ''),
(132, 37, '/UserFiles/Image/Trial/img37_32039.jpg', 0, ''),
(133, 37, '/UserFiles/Image/Trial/img37_62079.jpg', 0, ''),
(134, 38, '/UserFiles/Image/Trial/img38_10928.jpg', 0, ''),
(135, 38, '/UserFiles/Image/Trial/img38_22872.jpg', 0, ''),
(136, 38, '/UserFiles/Image/Trial/img38_20950.jpg', 0, ''),
(137, 39, '/UserFiles/Image/Trial/img39_86997.jpg', 0, ''),
(138, 39, '/UserFiles/Image/Trial/img39_50960.jpg', 0, ''),
(139, 39, '/UserFiles/Image/Trial/img39_43964.jpg', 0, ''),
(140, 39, '/UserFiles/Image/Trial/img39_16581.jpg', 0, ''),
(141, 40, '/UserFiles/Image/Trial/img40_42498.jpg', 0, ''),
(142, 40, '/UserFiles/Image/Trial/img40_35924.jpg', 0, ''),
(143, 40, '/UserFiles/Image/Trial/img40_60229.jpg', 0, ''),
(144, 41, '/UserFiles/Image/Trial/img41_86121.png', 0, ''),
(145, 41, '/UserFiles/Image/Trial/img41_13435.jpg', 0, ''),
(146, 41, '/UserFiles/Image/Trial/img41_27112.jpg', 0, ''),
(147, 42, '/UserFiles/Image/Trial/img42_76518.jpg', 0, ''),
(148, 42, '/UserFiles/Image/Trial/img42_63520.jpg', 0, ''),
(149, 42, '/UserFiles/Image/Trial/img42_89023.jpg', 0, ''),
(150, 43, '/UserFiles/Image/Trial/img43_25431.jpg', 0, ''),
(151, 43, '/UserFiles/Image/Trial/img43_33566.jpg', 0, ''),
(152, 43, '/UserFiles/Image/Trial/img43_40758.jpg', 0, ''),
(153, 44, '/UserFiles/Image/Trial/img44_66993.jpg', 0, ''),
(154, 44, '/UserFiles/Image/Trial/img44_43519.jpg', 0, ''),
(155, 44, '/UserFiles/Image/Trial/img44_80880.jpg', 0, ''),
(156, 45, '/UserFiles/Image/Trial/img45_47678.jpg', 0, ''),
(157, 45, '/UserFiles/Image/Trial/img45_89361.jpg', 0, ''),
(158, 45, '/UserFiles/Image/Trial/img45_70583.jpg', 0, ''),
(159, 46, '/UserFiles/Image/Trial/img46_42405.jpg', 0, ''),
(160, 46, '/UserFiles/Image/Trial/img46_39677.jpg', 0, ''),
(161, 46, '/UserFiles/Image/Trial/img46_47593.jpg', 0, ''),
(162, 47, '/UserFiles/Image/Trial/img47_21051.jpg', 0, ''),
(163, 47, '/UserFiles/Image/Trial/img47_33713.jpg', 0, ''),
(164, 47, '/UserFiles/Image/Trial/img47_27834.jpg', 0, ''),
(165, 48, '/UserFiles/Image/Trial/img48_89410.jpg', 0, ''),
(166, 48, '/UserFiles/Image/Trial/img48_41999.jpg', 0, ''),
(167, 48, '/UserFiles/Image/Trial/img48_85509.jpg', 0, ''),
(168, 49, '/UserFiles/Image/Trial/img49_77689.jpg', 0, ''),
(169, 49, '/UserFiles/Image/Trial/img49_10243.jpg', 0, ''),
(170, 49, '/UserFiles/Image/Trial/img49_10863.jpg', 0, ''),
(171, 50, '/UserFiles/Image/Trial/img50_22110.jpg', 0, ''),
(172, 50, '/UserFiles/Image/Trial/img50_42880.jpg', 0, ''),
(173, 50, '/UserFiles/Image/Trial/img50_30952.jpg', 0, ''),
(174, 51, '/UserFiles/Image/Trial/img51_77278.jpg', 0, ''),
(175, 51, '/UserFiles/Image/Trial/img51_93577.jpg', 0, ''),
(176, 51, '/UserFiles/Image/Trial/img51_34326.jpg', 0, ''),
(177, 52, '/UserFiles/Image/Trial/img52_39446.jpg', 0, ''),
(178, 52, '/UserFiles/Image/Trial/img52_14179.jpg', 0, ''),
(179, 52, '/UserFiles/Image/Trial/img52_63499.jpg', 0, ''),
(180, 53, '/UserFiles/Image/Trial/img53_12129.jpg', 0, ''),
(181, 53, '/UserFiles/Image/Trial/img53_26404.jpg', 0, ''),
(182, 53, '/UserFiles/Image/Trial/img53_34575.jpg', 0, ''),
(183, 54, '/UserFiles/Image/Trial/img54_56298.jpg', 0, ''),
(184, 54, '/UserFiles/Image/Trial/img54_16718.jpg', 0, ''),
(185, 54, '/UserFiles/Image/Trial/img54_66653.jpg', 0, ''),
(186, 55, '/UserFiles/Image/Trial/img55_54757.jpg', 0, ''),
(187, 55, '/UserFiles/Image/Trial/img55_13369.jpg', 0, ''),
(188, 55, '/UserFiles/Image/Trial/img55_23381.jpg', 0, ''),
(189, 56, '/UserFiles/Image/Trial/img56_67285.jpg', 0, ''),
(190, 56, '/UserFiles/Image/Trial/img56_72601.jpg', 0, ''),
(191, 56, '/UserFiles/Image/Trial/img56_81573.jpg', 0, ''),
(192, 57, '/UserFiles/Image/Trial/img57_61348.jpg', 0, ''),
(193, 57, '/UserFiles/Image/Trial/img57_34525.jpg', 0, ''),
(194, 57, '/UserFiles/Image/Trial/img57_75016.jpg', 0, ''),
(195, 58, '/UserFiles/Image/Trial/img58_79133.jpg', 0, ''),
(196, 58, '/UserFiles/Image/Trial/img58_56520.jpg', 0, ''),
(197, 58, '/UserFiles/Image/Trial/img58_29188.jpg', 0, ''),
(198, 59, '/UserFiles/Image/Trial/img59_20971.jpg', 0, ''),
(199, 59, '/UserFiles/Image/Trial/img59_92292.jpg', 0, ''),
(200, 59, '/UserFiles/Image/Trial/img59_61956.jpg', 0, ''),
(201, 60, '/UserFiles/Image/Trial/img60_75028.jpg', 0, ''),
(202, 60, '/UserFiles/Image/Trial/img60_95557.jpg', 0, ''),
(203, 60, '/UserFiles/Image/Trial/img60_27601.jpg', 0, ''),
(204, 61, '/UserFiles/Image/Trial/img61_26143.jpg', 0, ''),
(205, 61, '/UserFiles/Image/Trial/img61_39043.jpg', 0, ''),
(206, 61, '/UserFiles/Image/Trial/img61_78367.jpg', 0, ''),
(207, 62, '/UserFiles/Image/Trial/img62_51251.jpg', 0, ''),
(208, 62, '/UserFiles/Image/Trial/img62_12965.jpg', 0, ''),
(209, 62, '/UserFiles/Image/Trial/img62_92938.jpg', 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_gbook`
--

CREATE TABLE `phpshop_gbook` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `datas` int(11) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `mail` varchar(32) DEFAULT NULL,
  `tema` text,
  `otsiv` text,
  `otvet` text,
  `flag` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_gbook`
--

INSERT INTO `phpshop_gbook` (`id`, `datas`, `name`, `mail`, `tema`, `otsiv`, `otvet`, `flag`) VALUES
(1, 1409740328, 'Елена', '', 'Отзыв от 03-09-2014', 'Приятно былоработать с таким магазином! И акции со скидкой есть и сама продукция разнообразная) Сначала было немного трудно правда разобраться с формой заказа, но консультанты помогли. Сначала хотела забирать самовывозом, но мне сказали, что доставка будет бесплатно, так как сумма заказа больше 2000 рублей) это приятно порадовало.', 'Спасибо, Елена! Рады стараться!', '1'),
(3, 1409731200, 'Ольга', 'mail@test.ru', 'хороший магазин!', 'Хотелось бы тоже отписаться поповоду работы магазина. Ребята во-первых ответственные и порядочные.', 'Здравствуйте, Ольга.</p><p>Благодарим Вас за положительную оценку!', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_jurnal`
--

CREATE TABLE `phpshop_jurnal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(64) NOT NULL DEFAULT '',
  `datas` varchar(32) NOT NULL DEFAULT '',
  `flag` enum('0','1') NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_links`
--

CREATE TABLE `phpshop_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `image` text,
  `opis` text,
  `link` text,
  `num` int(11) DEFAULT NULL,
  `enabled` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_links`
--

INSERT INTO `phpshop_links` (`id`, `name`, `image`, `opis`, `link`, `num`, `enabled`) VALUES
(1, 'PHPShop Software', '', 'Создание интернет-магазина, скрипт интернет-магазина PHPShop.', 'https://www.phpshop.ru', 5, '1'),
(2, 'PHPShop CMS Free', '', 'Бесплатная сиcтема управления сайтом PHPShop CMS Free.', 'https://www.phpshopcms.ru', 3, '1'),
(3, 'Аренда интернет-магазина', '', 'Shopbuilder - сервис аренды интернет-магазина, позволяющий пользователям за считанные минуты создать полноценный сайт интернет-магазина за 599 руб.', 'https://www.shopbuilder.ru', 1, '1');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_menu`
--

CREATE TABLE `phpshop_menu` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_menu`
--

INSERT INTO `phpshop_menu` (`id`, `name`, `content`, `flag`, `num`, `dir`, `element`, `servers`) VALUES
(1, 'Обновление', 'Постоянное обновление функционала - cкрипт PHPShop постоянно развивается, вы получаете все преимущества обновлений, находясь на активной техподдержке.', '1', 4, '/', '1', ''),
(2, 'Личный менеджер', 'Личный менеджер проекта при заказе дизайна интернет-магазина, вам не придется разбираться в тонкостях верстки, программирования - эту работу выполняет личный менеджер проекта.', '1', 2, '/', '0', ''),
(3, 'Cкидки и бонусы', 'Накопительные скидки и бонусы - вы можете сэкономить, получив 10% от покупок приведенных друзей. Также действуют накопительные скидки, которые суммируются с бонусами.', '1', 3, '', '1', ''),
(4, 'Программирование', 'Быстрая доработка под ваши нужды. Адаптировать скрипт под вас можем как мы, так и любой грамотный программист, с помощью нашего IDE с подсказками переменных.', '1', 3, '', '0', '');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_messages`
--

CREATE TABLE `phpshop_messages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PID` int(11) DEFAULT '0',
  `UID` int(11) DEFAULT '0',
  `AID` int(11) DEFAULT '0',
  `DateTime` datetime DEFAULT '1970-01-01 00:00:00',
  `Subject` text,
  `Message` text,
  `enabled` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules`
--

CREATE TABLE `phpshop_modules` (
  `path` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `date` int(11) DEFAULT '0',
  `servers` varchar(64) default '',
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules`
--

INSERT INTO `phpshop_modules` (`path`, `name`, `date`) VALUES
('returncall', 'Return Call', 1512653689),
('visualcart', 'Visual Cart', 1512653689),
('oneclick', 'One Click', 1512653689),
('promotions', 'Promotions', 1512653689),
('seourlpro', 'SeoUrlPro', 1512653689),
('sticker', 'Sticker', 1521908948),
('tinkoff', 'Tinkoff', 1512653689),
('productlastview', 'Product Last View', 1521908948);


-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_key`
--

CREATE TABLE `phpshop_modules_key` (
  `path` varchar(64) NOT NULL DEFAULT '',
  `date` int(11) DEFAULT '0',
  `key` text,
  `verification` varchar(32) DEFAULT '',
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_oneclick_jurnal`
--

CREATE TABLE `phpshop_modules_oneclick_jurnal` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `name` varchar(64) default '',
  `tel` varchar(64) default '',
  `message` text,
  `product_name` varchar(64) default '',
  `product_id` int(11),
  `product_price` varchar(64) default '',
  `product_image` varchar(64) default '',
  `status` enum('1','2','3','4') default '1',
  `ip` varchar(64) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_oneclick_system`

CREATE TABLE IF NOT EXISTS `phpshop_modules_oneclick_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1','2') default '1',
  `title` text,
  `title_end` text,
  `serial` varchar(64) default '',
  `windows` enum('0','1') default '0',
  `display` enum('0','1') default '0',
  `version` varchar(64) DEFAULT '1.1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_oneclick_system` VALUES (1,'0','Спасибо, Ваш заказ принят!','Наши менеджеры свяжутся с Вами для уточнения деталей.','','1','0','1.4');
--

CREATE TABLE IF NOT EXISTS `phpshop_modules_tinkoff_system` (
  `id` int(11) NOT NULL auto_increment,
  `status` int(11),
  `title` text,
  `title_end` text,
  `terminal` varchar(64) default '',
  `secret_key` varchar(64) default '',
  `gateway` varchar(64) default '',
  `version` varchar(64) DEFAULT '1.0',
  `enabled_taxation` int DEFAULT 0,
  `taxation` varchar(64),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_tinkoff_system` VALUES (1, 0, 'Платежная система Тинькофф Банка', 'Оплатите пожалуйста свой заказ','TinkoffBankTest', 'TinkoffBankTest
', 'https://securepay.tinkoff.ru/v2', '2.2', '0', 'osn');

CREATE TABLE IF NOT EXISTS `phpshop_modules_promotions_system` (
  `id` int(11) NOT NULL auto_increment,
  `version` varchar(64) DEFAULT '1.0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_oneclick_system`
--

INSERT INTO `phpshop_modules_promotions_system` VALUES (1,'2.6');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_promotions_forms`
--

CREATE TABLE IF NOT EXISTS `phpshop_modules_promotions_forms` (
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
  `free_delivery` enum('0','1') NOT NULL,
  `categories_check` enum('0','1') NOT NULL,
  `categories` text NOT NULL,
  `status_check` enum('0','1') NOT NULL DEFAULT '0',
  `statuses` text NOT NULL DEFAULT '',
  `products_check` enum('0','1') NOT NULL,
  `products` text NOT NULL,
  `sum_order_check` enum('0','1') NOT NULL,
  `sum_order` int(11) NOT NULL,
  `delivery_method_check` enum('0','1') NOT NULL,
  `delivery_method` int(11) NOT NULL,
  `code_check` enum('0','1') NOT NULL,
  `code` varchar(255) NOT NULL,
  `code_tip` enum('0','1') NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `header_mail` varchar(255) NOT NULL,
  `content_mail` text NOT NULL,
  `block_old_price` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_promotions_forms`
--

INSERT INTO `phpshop_modules_promotions_forms` (`id`, `name`, `enabled`, `description`, `active_check`, `active_date_ot`, `active_date_do`, `discount_check`, `discount_tip`, `discount`, `free_delivery`, `categories_check`, `categories`, `products_check`, `products`, `sum_order_check`, `sum_order`, `delivery_method_check`, `delivery_method`, `code_check`, `code`, `code_tip`, `date_create`) VALUES
(1, 'Скидка 30%', '1', '<p><span class="s1"><a href="/order/"><img src="/UserFiles/Image/Trial/gift.png" alt="" width="100" height="92" /></a></span></p>\r\n<p><span class="s1">Промо-код <strong>gift2018</strong></span></p>\r\n<p><span class="s1">Введите в корзине и получите скидку 30%!</span></p>', '1', '27-11-2017', '31-05-2019', '1', '1', 30, '0', '1', '8,9,7,1,4,6,3,5,', '0', '', '0', 0, '0', 10017, '1', 'gift2018', '0', '2018-03-25 05:28:57');


CREATE TABLE IF NOT EXISTS `phpshop_modules_promotions_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promo_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL UNIQUE,
  `enabled` ENUM( '0', '1' ) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_returncall_jurnal`
--

CREATE TABLE `phpshop_modules_returncall_jurnal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) DEFAULT '0',
  `time_start` float DEFAULT '10',
  `time_end` float DEFAULT '18',
  `name` varchar(64) DEFAULT '',
  `tel` varchar(64) DEFAULT '',
  `message` text,
  `status` enum('1','2','3','4') DEFAULT '1',
  `ip` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_returncall_system`
--

CREATE TABLE `phpshop_modules_returncall_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` enum('0','1','2') DEFAULT '1',
  `title` varchar(64) DEFAULT '',
  `title_end` text,
  `serial` varchar(64) DEFAULT '',
  `windows` enum('0','1') DEFAULT '0',
  `captcha_enabled` enum('1','2') DEFAULT '1',
  `version` float DEFAULT '1.4',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_returncall_system`
--

INSERT INTO `phpshop_modules_returncall_system` (`id`, `enabled`, `title`, `title_end`, `serial`, `windows`, `captcha_enabled`, `version`) VALUES
(1, '0', 'Обратный звонок', 'Заявка на обратный звонок принята, ждите', '', '1', '1', 1.4);

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_seourlpro_system`
--

CREATE TABLE IF NOT EXISTS `phpshop_modules_seourlpro_system` (
  `id` int(11)  auto_increment,
  `paginator` enum('1','2') default '1',
  `seo_brands_enabled` enum('1','2') default '1',
  `cat_content_enabled` enum('1','2') default '1',
  `seo_news_enabled` enum('1','2') default '1',
  `seo_page_enabled` enum('1','2') default '1',
  `redirect_enabled` enum('1','2') default '1',
  `version` VARCHAR(64) DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_seourlpro_system`
--

INSERT INTO `phpshop_modules_seourlpro_system` (`id`, `paginator`, `seo_brands_enabled`, `cat_content_enabled`, `version`) VALUES
(1, '1', '2', '1', '2.1');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_sticker_forms`
--

CREATE TABLE `phpshop_modules_sticker_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `path` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `mail` varchar(64) NOT NULL DEFAULT '',
  `enabled` enum('0','1') NOT NULL DEFAULT '1',
  `dir` text NOT NULL,
  `skin` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_sticker_forms`
--

INSERT INTO `phpshop_modules_sticker_forms` (`id`, `name`, `path`, `content`, `mail`, `enabled`, `dir`, `skin`) VALUES
(1, 'Консультации', 'three', '<p class="icon">&nbsp;</p>\r\n<h6 class="no-margin text-uppercase">Помощь и консультации</h6>\r\n<p>поможем с выбором</p>', '', '1', '', 'hub'),
(2, 'Гарантия возврата денег', 'two', '<p class="icon">&nbsp;</p>\r\n<h6 class="no-margin text-uppercase">Гарантия возврата денег</h6>\r\n<p>в течении 14 дней с момента покупки</p>', '', '1', '', 'hub'),
(3, 'Бесплатная доставка', 'one', '<p class="icon">&nbsp;</p>\r\n<h6 class="no-margin text-uppercase">Бесплатная доставка</h6>\r\n<p>при заказе от 5000 руб.</p>', '', '1', '', 'hub');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_sticker_system`
--

CREATE TABLE `phpshop_modules_sticker_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial` varchar(64) NOT NULL DEFAULT '',
  `version` varchar(64) DEFAULT '1.0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_sticker_system`
--

INSERT INTO `phpshop_modules_sticker_system` (`id`, `serial`, `version`) VALUES
(1, '', '1.2');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_visualcart_memory`
--

CREATE TABLE `phpshop_modules_visualcart_memory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memory` varchar(64) DEFAULT '',
  `cart` text,
  `date` int(11) DEFAULT '0',
  `user` int(11) DEFAULT '0',
  `ip` varchar(64) DEFAULT '',
  `referal` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_modules_visualcart_system`
--

CREATE TABLE `phpshop_modules_visualcart_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` enum('0','1','2') DEFAULT '1',
  `flag` enum('1','2') DEFAULT '1',
  `title` varchar(64) DEFAULT '',
  `pic_width` tinyint(100) DEFAULT '0',
  `memory` enum('0','1') DEFAULT '1',
  `serial` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_visualcart_system`
--

INSERT INTO `phpshop_modules_visualcart_system` (`id`, `enabled`, `flag`, `title`, `pic_width`, `memory`, `serial`) VALUES
(1, '0', '1', 'Корзина', 50, '1', '');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_news`
--

CREATE TABLE `phpshop_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datas` varchar(32) DEFAULT '',
  `zag` varchar(255) DEFAULT '',
  `kratko` text,
  `podrob` text,
  `datau` int(11) DEFAULT '0',
  `odnotip` text,
  `news_seo_name` VARCHAR(255) DEFAULT '',
  `servers` varchar(64) default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_newsletter`
--

CREATE TABLE `phpshop_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `content` text,
  `template` int(11) DEFAULT '0',
  `date` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_notice`
--

CREATE TABLE `phpshop_notice` (
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

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_opros`
--

CREATE TABLE `phpshop_opros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) unsigned DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `total` int(11) DEFAULT '0',
  `num` tinyint(32) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_opros`
--

INSERT INTO `phpshop_opros` (`id`, `category`, `name`, `total`, `num`) VALUES
(1, 1, 'Да', 23, 0),
(2, 1, 'Нормально', 8, 0),
(3, 1, 'Не очень', 8, 0),
(4, 2, 'Да, не удобно ехать к вам', 67, 0),
(5, 2, 'Да, надо, чтоб специалист показал все лично', 63, 0),
(6, 2, 'Нет, не вижу смысла', 116, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_opros_categories`
--

CREATE TABLE `phpshop_opros_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `dir` varchar(32) DEFAULT '',
  `flag` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_opros_categories`
--

INSERT INTO `phpshop_opros_categories` (`id`, `name`, `dir`, `flag`) VALUES
(1, 'Вам нравится новый дизайн?', '', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_orders`
--

CREATE TABLE `phpshop_orders` (
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
  `tracking` varchar(64) default '',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_orders`
--

INSERT INTO `phpshop_orders` (`id`, `datas`, `uid`, `orders`, `status`, `user`, `seller`, `statusi`, `country`, `state`, `city`, `index`, `fio`, `tel`, `street`, `house`, `porch`, `door_phone`, `flat`, `delivtime`, `org_name`, `org_inn`, `org_kpp`, `org_yur_adres`, `org_fakt_adres`, `org_ras`, `org_bank`, `org_kor`, `org_bik`, `org_city`, `dop_info`, `sum`, `files`) VALUES
(1, '1523370976', '1-73', 0x613a323a7b733a343a2243617274223b613a353a7b733a343a2263617274223b613a333a7b693a31333b613a383a7b733a323a226964223b733a323a223133223b733a343a226e616d65223b733a31333a22cfeeece0e4e0204c6f7265616c223b733a353a227072696365223b733a343a2232333030223b733a333a22756964223b733a363a22333435343634223b733a333a226e756d223b693a313b733a363a2265645f697a6d223b733a333a22f8f22e223b733a393a227069635f736d616c6c223b733a33393a222f5573657246696c65732f496d6167652f547269616c2f696d6731335f3739303935732e706e67223b733a363a22776569676874223b733a323a223130223b7d693a31343b613a383a7b733a323a226964223b733a323a223134223b733a343a226e616d65223b733a31353a22d2f3f8fc20e4ebff20f0e5f1ede8f6223b733a353a227072696365223b733a343a2234303030223b733a333a22756964223b733a363a22333536353432223b733a333a226e756d223b693a313b733a363a2265645f697a6d223b733a333a22f8f22e223b733a393a227069635f736d616c6c223b733a33393a222f5573657246696c65732f496d6167652f547269616c2f696d6731345f3330373931732e706e67223b733a363a22776569676874223b733a323a223430223b7d693a35373b613a383a7b733a323a226964223b733a323a223537223b733a343a226e616d65223b733a31323a22caf0eee2e0f2fc20cbe8e4f1223b733a353a227072696365223b733a353a223930303030223b733a333a22756964223b4e3b733a333a226e756d223b693a313b733a363a2265645f697a6d223b733a333a22f8f22e223b733a393a227069635f736d616c6c223b733a33393a222f5573657246696c65732f496d6167652f547269616c2f696d6735375f3334353235732e6a7067223b733a363a22776569676874223b4e3b7d7d733a333a226e756d223b693a333b733a333a2273756d223b733a353a223936333030223b733a363a22776569676874223b693a35303b733a383a22646f737461766b61223b693a303b7d733a363a22506572736f6e223b613a32303a7b733a343a226f756964223b733a343a22312d3733223b733a343a2264617461223b733a31303a2231353233333730393736223b733a343a2274696d65223b733a383a2231373a313620706d223b733a343a226d61696c223b733a31323a2274657374406d61696c2e7275223b733a31313a226e616d655f706572736f6e223b733a303a22223b733a383a226f72675f6e616d65223b733a303a22223b733a373a226f72675f696e6e223b733a303a22223b733a373a226f72675f6b7070223b733a303a22223b733a383a2274656c5f636f6465223b733a303a22223b733a383a2274656c5f6e616d65223b733a303a22223b733a383a226164725f6e616d65223b733a303a22223b733a31343a22646f737461766b615f6d65746f64223b733a313a2233223b733a383a22646973636f756e74223b693a303b733a373a22757365725f6964223b693a31383b733a363a22646f735f6f74223b733a303a22223b733a363a22646f735f646f223b733a303a22223b733a31313a226f726465725f6d65746f64223b733a313a2233223b733a393a2270726f6d6f636f6465223b733a303a22223b733a31343a22646973636f756e745f70726f6d6f223b4e3b733a383a227469705f64697363223b4e3b7d7d, 'a:2:{s:7:"maneger";s:0:"";s:4:"time";s:16:"12-04-2018 13:35";}', 18, '0', 3, '', '', '', '', 'Юлия', '(111) 224-1241', '', '', '', '', '', 'с 10 утра', '', '', '', '', '', '', '', '', '', '', '', 96300, 'N;'),
(2, '1523371029', '2-51', 0x613a323a7b733a343a2243617274223b613a353a7b733a343a2263617274223b613a333a7b693a36313b613a383a7b733a323a226964223b733a323a223631223b733a343a226e616d65223b733a343a22d1f2f3eb223b733a353a227072696365223b733a353a223234303030223b733a333a22756964223b733a373a2230393830393837223b733a333a226e756d223b693a313b733a363a2265645f697a6d223b733a333a22f8f22e223b733a393a227069635f736d616c6c223b733a33393a222f5573657246696c65732f496d6167652f547269616c2f696d6736315f3236313433732e6a7067223b733a363a22776569676874223b4e3b7d693a36303b613a383a7b733a323a226964223b733a323a223630223b733a343a226e616d65223b733a343a22d7e0f1fb223b733a353a227072696365223b733a343a2239303030223b733a333a22756964223b733a383a223039383938373938223b733a333a226e756d223b693a313b733a363a2265645f697a6d223b733a333a22f8f22e223b733a393a227069635f736d616c6c223b733a33393a222f5573657246696c65732f496d6167652f547269616c2f696d6736305f3237363031732e6a7067223b733a363a22776569676874223b4e3b7d693a35393b613a383a7b733a323a226964223b733a323a223539223b733a343a226e616d65223b733a353a22cbe0ecefe0223b733a353a227072696365223b733a343a2232333030223b733a333a22756964223b733a373a2239373837373635223b733a333a226e756d223b693a313b733a363a2265645f697a6d223b733a333a22f8f22e223b733a393a227069635f736d616c6c223b733a33393a222f5573657246696c65732f496d6167652f547269616c2f696d6735395f3631393536732e6a7067223b733a363a22776569676874223b4e3b7d7d733a333a226e756d223b693a333b733a333a2273756d223b693a33353330303b733a363a22776569676874223b693a303b733a383a22646f737461766b61223b693a303b7d733a363a22506572736f6e223b613a32303a7b733a343a226f756964223b733a343a22322d3531223b733a343a2264617461223b733a31303a2231353233333731303239223b733a343a2274696d65223b733a383a2231373a303920706d223b733a343a226d61696c223b733a31353a22746573743240676d61696c2e636f6d223b733a31313a226e616d655f706572736f6e223b733a303a22223b733a383a226f72675f6e616d65223b733a303a22223b733a373a226f72675f696e6e223b733a303a22223b733a373a226f72675f6b7070223b733a303a22223b733a383a2274656c5f636f6465223b733a303a22223b733a383a2274656c5f6e616d65223b733a303a22223b733a383a226164725f6e616d65223b733a303a22223b733a31343a22646f737461766b615f6d65746f64223b733a313a2233223b733a383a22646973636f756e74223b693a303b733a373a22757365725f6964223b693a31393b733a363a22646f735f6f74223b733a303a22223b733a363a22646f735f646f223b733a303a22223b733a31313a226f726465725f6d65746f64223b733a353a223130303137223b733a393a2270726f6d6f636f6465223b733a303a22223b733a31343a22646973636f756e745f70726f6d6f223b4e3b733a383a227469705f64697363223b4e3b7d7d, 'a:2:{s:7:"maneger";s:0:"";s:4:"time";s:16:"12-04-2018 13:35";}', 19, '0', 2, '', '', '', '', 'Руслан', '(888) 888-8888', '', '', '', '', '', 'с 17 вечера', '', '', '', '', '', '', '', '', '', '', '', 35300, 'N;'),
(3, '1523524420', '3-90', 0x613a323a7b733a343a2243617274223b613a353a7b733a343a2263617274223b613a313a7b693a3131313b613a31303a7b733a323a226964223b733a333a22313131223b733a343a226e616d65223b733a32323a2253616d73756e67204765617220565220e1e5ebfbe920223b733a353a227072696365223b733a353a223637303030223b733a333a22756964223b4e3b733a333a226e756d223b693a313b733a363a2265645f697a6d223b4e3b733a393a227069635f736d616c6c223b733a33393a222f5573657246696c65732f496d6167652f547269616c2f696d6733365f3632383337732e6a7067223b733a363a22776569676874223b4e3b733a363a22706172656e74223b693a33363b733a31303a22706172656e745f756964223b733a373a2231383832323334223b7d7d733a333a226e756d223b693a313b733a333a2273756d223b693a36373030303b733a363a22776569676874223b693a303b733a383a22646f737461766b61223b693a303b7d733a363a22506572736f6e223b613a32303a7b733a343a226f756964223b733a343a22332d3930223b733a343a2264617461223b733a31303a2231353233353234343230223b733a343a2274696d65223b733a383a2231333a343020706d223b733a343a226d61696c223b733a31333a227465737433406d61696c2e7275223b733a31313a226e616d655f706572736f6e223b733a303a22223b733a383a226f72675f6e616d65223b733a303a22223b733a373a226f72675f696e6e223b733a303a22223b733a373a226f72675f6b7070223b733a303a22223b733a383a2274656c5f636f6465223b733a303a22223b733a383a2274656c5f6e616d65223b733a303a22223b733a383a226164725f6e616d65223b733a303a22223b733a31343a22646f737461766b615f6d65746f64223b693a333b733a383a22646973636f756e74223b693a303b733a373a22757365725f6964223b733a323a223137223b733a363a22646f735f6f74223b733a303a22223b733a363a22646f735f646f223b733a303a22223b733a31313a226f726465725f6d65746f64223b693a333b733a393a2270726f6d6f636f6465223b733a303a22223b733a31343a22646973636f756e745f70726f6d6f223b4e3b733a383a227469705f64697363223b4e3b7d7d, 'a:2:{s:7:"maneger";N;s:4:"time";s:0:"";}', 17, '0', 0, '', '', '', '', 'Екатерина', '(888) 999-8888', '', '', '', '', '', '098098', '', '', '', '', '', '', '', '', '', '', '', 67000, NULL);


-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_order_status`
--

CREATE TABLE `phpshop_order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `color` varchar(64) DEFAULT '',
  `sklad_action` enum('0','1') DEFAULT '0',
  `cumulative_action` enum('0','1') DEFAULT '0',
  `mail_action` enum('0','1') DEFAULT '1',
  `mail_message` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_order_status`
--

INSERT INTO `phpshop_order_status` (`id`, `name`, `color`, `sklad_action`, `cumulative_action`, `mail_action`, `mail_message`) VALUES
(1, 'Аннулирован', 'red', '0', '0', '1', NULL),
(2, 'Выполняется', '#99cccc', '0', '0', '1', NULL),
(3, 'Доставляется', '#ff9900', '0', '0', '1', NULL),
(4, 'Выполнен', '#ccffcc', '1', '0', '1', NULL),
(100, 'Передано в бухгалтерию', '#ffff33', '0', '0', '1', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_page`
--

CREATE TABLE `phpshop_page` (
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
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `link` (`link`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_page`
--

INSERT INTO `phpshop_page` (`id`, `name`, `link`, `category`, `keywords`, `description`, `content`, `servers`, `num`, `datas`, `odnotip`, `title`, `enabled`, `secure`, `secure_groups`) VALUES
(1, 'Благодарим вас за установку PHPShop', 'index', 2000, '', '', '<p><img style="float: left; margin: 0px 10px 10px 0px;" src="/UserFiles/Image/Trial/box.png" alt="" />Представляем PHPShop @version@ - новую версию интернет-магазина, в которой мы соединили современные веб-технологии и наш многолетний опыт создания и поддержки интернет-магазинов. Начинка PHPShop 5 - это HTML5, Bootstrap, jQuery, позволяющие создавать качественные, функциональные проекты с современным и адаптивным дизайном.</p>\n<p> </p>\n<p>PHPShop - это целый <strong>программный комплекс</strong> для создания и управления интернет-магазином. Кроме самого PHP-скрипта для продажи товара и обработки заказов на сервере, существует специальный набор дополнительных <strong>бесплатных Windows утилит</strong>, объединенных в пакет <a href="https://www.phpshop.ru/page/downloads.html" target="_blank" rel="noopener">EasyControl</a>. Утилиты делятся на 4 группы по назначению: заполнение товарной базы, управление заказами, настройка дизайна магазина и техническое обслуживание.</p>\n<p>К первой относится мощная, уникальная утилита <a href="http://faq.phpshop.ru/page/batch-loading.html" target="_blank" rel="noopener">PriceLoader</a> для комплексной обработки прайс-листов поставщиков, автоматической загрузки и обновления номенклатуры в ваш магазин. Так же PriceLoader позволяет делать копии товарной базы на основе файл Яндекс.Маркета (YML-прайс), пакетно добавлять и обрабатывать изображения к товарам, удалять с сервера устаревшие изображения, переводить описание товара на любой язык через Яндекс.Перевод. Вам не понадобится часами заполнять описания товаров, достаточно <strong>один раз настроить PriceLoader</strong> на автоматическую обработку и синхронизацию цен.</p>\n<p>Утилиты Monitor, Chat помогают осуществлять <strong>контроль и обработку новых заказов</strong>, получать статистику посещений и общаться с пользователями сайта с помощью <strong>текстового чата</strong> приложения Chat.</p>\n<p>С помощью приложения эмулятора интернет-магазина <b>Мой Магазин</b> можно легко на своем локальном компьютере настроить внешний вид магазина, заполнить его товарами (например, через PriceLoader или 1С), настроить все функции и модули, а затем одним кликом <a href="http://faq.phpshop.ru/page/synch.html" target="_blank" rel="noopener">синхронизировать</a> результат с рабочим сайтом. Это сэкономит ваше время и не потребует постоянного подключения к интернету.</p>\n<p>К последней группе относятся утилиты для обслуживания PHP скриптов на сервере. Installer и <strong>Updater</strong> позволяют установить и обновить PHPShop в 3 клика. После ввода нескольких параметров доступа к сайту утилиты загрузят нужные файлы и обновят данные. Своевременные обновления защищают магазин и расширяют его технические возможности. Для <strong>восстановления потерянного пароля</strong> используется <b>PasswordRestore</b>.</p>\n<p>Для пользователей 1С существует возможность автоматизировать заполнение номенклатурой и обработки заказов с PHPShop. Мощный фирменный функционал <a href="https://www.phpshop.ru/page/1c.html" target="_blank" rel="noopener">синхронизации интернет-магазина с 1С</a> намного повысит эффективность вашего бизнеса. Бесплатная удаленная настройка нашими специалистами такой синхронизации сократит время запуска проекта.</p>\n<p>По любым техническим вопросам или программным доработкам можно обратится в <a href="https://help.phpshop.ru" target="_blank" rel="noopener">службу технической поддержки</a>. Мы оказываем <strong>полный спектр услуг</strong>, в том числе создание уникального <a href="https://www.phpshop.ru/calculation/brifdesign/" target="_blank" rel="noopener">персонального дизайна</a> или доработка существующего.</p>\n<blockquote>Мы делаем прибыльные интернет-магазины уже 15 лет, - доверьте свой бизнес опытным разработчикам!<footer class="text-right"><cite>Команда PHPShop Software</cite></footer></blockquote>', '', 0, 1523285236, '', '', '1', '0', ''),
(24, 'Дизайн', 'design', 1000, '', '', '<p>В комплект интернет-магазина PHPShop @version@ входят несколько современных аддаптивных шаблонов с разными цветовыми оттенками и функцией визуальной настройки через редактор.</p>\n<p><a href="?skin=diggi"><img class="template" title="diggi" src="/UserFiles/Image/Trial/template_icon/diggi.gif" alt="" width="150" height="120" /></a><a href="?skin=spice"><img class="template" title="spice" src="/UserFiles/Image/Trial/template_icon/spice.gif" alt="" width="150" height="120" /></a><a href="?skin=astero"><img class="template" title="astero" src="/UserFiles/Image/Trial/template_icon/astero.gif" alt="" width="150" height="120" /></a> <a href="?skin=bootstrap"><img class="template" title="bootstrap" src="/UserFiles/Image/Trial/template_icon/bootstrap.gif" alt="" width="150" height="120" /></a><a href="?skin=unishop"><img class="template" title="unishop" src="/UserFiles/Image/Trial/template_icon/unishop.gif" alt="" width="150" height="120" /></a><a href="?skin=hub"><img class="template" title="hub" src="/UserFiles/Image/Trial/template_icon/hub.gif" alt="" width="150" height="120" /></a><a href="?skin=terra"><img class="template" title="hub" src="/UserFiles/Image/Trial/template_icon/terra.gif" alt="" width="150" height="120" /></a></p>\n<h2>Изменение дизайна</h2>\n<p>Для редактирования и настройки дизайна в панеле управления используется "Редактор шаблонов", доступный через меню <kbd>Настройки</kbd> - <kbd>Шаблоны дизайнов</kbd> .</p>\n<p><a title="Инструкция Template Edit" href="http://faq.phpshop.ru/page/templating.html" target="_blank" rel="noopener"><img class="template" src="/UserFiles/Image/Trial/template_edit.jpg" alt="" ></a></p>\n\n<h2>Персональный дизайн</h2>\n<p>Наше дизайн-бюро делает дизайны только для PHPShop, а значит, неожиданностей при создании дизайна не произойдет, и вы получите уникальный профессиональный дизайн в срок, отвечающий всем требованиям сегодняшнего дня.</p>\n<ol>\n<li>Мы на 100% знаем свою платформу, а это значит, что Вам не придется переплачивать за часы работы дизайнера, не знакомого с PHPShop.</li>\n<li>Мы стараемся учитывать всю функциональность PHPShop еще на первом этапе его создания, и вы получите работающий интернет-магазин таким, каким Вы его видите на утвержденном Вами макете.</li>\n<li>Большинство доработок, ранее требовавших вмешательства в код платформы, на новой версии PHPShop 5, производятся с помощью "дизайн-хуков", - это значит, что в будущем вы сможете обновляться без потери доработок.</li>\n<li>Мы соблюдаем сроки, и предоставляем гарантии - если после завершения проекта Вы заметите недочет с нашей стороны мы устраним его.</li>\n</ol>\n<p>Для заказа персонального дизайна нужно заполнить бриф, в котором вы формулируете будущий проект, все возникающие вопросы уточнить у наших консультантов. Cрок создания макета дизайна - 15 рабочих дней</p>\n<p><a class="btn btn-sm btn-success" href="https://www.phpshop.ru/calculation/brifdesign/" target="_blank" rel="noopener">Заполнить Бриф на Персональный дизайн сайта</a></p>', 'i1ii1000i', 1, 1537525119, '', 'Дополнительные шаблоны PHPShop', '1', '0', ''),
(26, 'Купить', 'purchase', 1000, '', '', '<p>Ваш тестовый интернет-магазин <strong>@serverName@</strong> на базе платформы PHPShop @version@ будет работать 30 дней. <strong>Вы можете уже сейчас наполнять свой магазин, все данные после покупки сохранятся! </strong>Купив <strong>бессрочную лицензию PHPShop</strong>, вам потребуется <strong>загрузить лишь один файл лицензии</strong>, вся заполненная товарная база останется нетронутой.\n</p><p>Для приобретения программного обеспечения PHPShop, нужно перейти в раздел оформления заказа по кнопке ниже. Далее, вам нужно выбрать удобный тип оплаты - электронный: картами Visa, Mastercard, через банкоматы Qiwi, через Сбербанк, банковским переводом для юридических лиц. После выбора оплаты, в разделе Счета появится счет на оплату в электронном виде. Оригиналы всех документов мы отправляем по почте, указанной в разделе Профиль вашего личного кабинета.</p>\n<p><a class="btn btm-sm btn-primary" target="_blank" href="https://www.phpshop.ru/order/?from=@serverName@&amp;action=order">Перейти к оформлению заказа PHPShop</a></p>', '', 2, 1522156399, '', 'Купить PHPShop', '1', '0', ''),
(23, 'Управление', 'admin', 1000, '', '', '<p>Для доступа к панели управления PHPShop нажмите сочетание клавиш <kbd>Ctrl</kbd> + <kbd>F12</kbd> или используйте кнопку перехода ниже.<br> Логин по умолчанию <strong>demo</strong>, пароль <strong>demouser</strong>. Если вы при установке задали свой логин и пароль, то используйте свои данные при авторизации.\n</p><p><a href="..phpshop/admpanel/" target="_blank" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-share-alt"></span> Переход в панель управления</a></p>\n<h2>Тестовая база</h2>\nПри установке магазина заполняется тестовая товарная база для демонстрации возможностей программы. Для очистки тестовой базы следует в панели управления магазином перейти в меню <kbd>База</kbd> - <kbd>SQL запрос к базе</kbd> выбрать в выпадающем списке опцию <strong>"Очистить базу"</strong>. Обращаем Ваше внимание, что очистится вся товарная база с момента начала работы магазина.\n<h2>Дополнительные утилиты</h2>\nPHPShop EasyControl - <strong>уникальный набор  бесплатных утилит</strong> для создания и управления интернет-магазином PHPShop на локальном компьютере . EasyControl прост в установке и не требует никаких специальных навыков. С помощью EasyControl Вы сможете установить сайт локально на ПК либо на хостинг, обновлять платформу сайта, обрабатывать заказы, заполнять товарную базу и редактировать шаблоны. В состав пакета входят более 10 утилит: <strong>Monitor, Updater, Installer, Chat,  Price Loader, Password Restore</strong> и другие.\n<p><a href="https://www.phpshop.ru/loads/files/setup.exe" target="_blank" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-share-alt"></span> Скачать утилиты EasyControl</a></p>', '', 3, 1522156405, '39,40', 'Администрирование PHPShop', '1', '0', ''),
(27, 'Ресурсы', 'help', 4, '', '', '<h3>Справка</h3> Справочно-информационный сайт (F.A.Q.), описывающий возможности PHPShop и ответы на частые вопросы по управлению интернет-магазином. Снабжен большим количеством скриншотов и видео-уроков.<br>Адрес: <a href="http://faq.phpshop.ru" target="_blank">faq.phpshop.ru</a><h3>Техническая документация</h3> Справочный сайт для разработчиков (WIKI). Содержит большое количество технической документации с примерами по разработке PHPShop. Описание утилит EasyControl и дополнительных модулей.<br>Адрес: <a href="http://wiki.phpshop.ru" target="_blank">wiki.phpshop.ru</a><h3>Описание API</h3> Справочный сайт для разработчиков (PHPDoc). Содержит подробное описание API PHPShop, функций и классов.<br>Адрес: <a href="http://doc.phpshop.ru" target="_blank">doc.phpshop.ru</a><h3>База знаний</h3> Справочный сайт службы технической поддержки. Содержит ответы по наиболее частым вопросам, встречающихся у пользователей PHPShop в поддержке.<br>Адрес: <a href="https://help.phpshop.ru" target="_blank">help.phpshop.ru</a><h3>Социальные сети</h3> Персональные странички в популярных социальный сетях. Содержат много интересных публикаций по возможностям платформы, новостях и акциям.<br>Адрес: <a href="https://www.facebook.com/shopsoft" target="_blank">https://www.facebook.com/shopsoft</a><br><a href="https://twitter.com/PHPShopCMS" target="_blank">https://twitter.com/PHPShopCMS</a><br><a href="https://plus.google.com/+PhpshopRu" target="_blank">https://plus.google.com/+PhpshopRu</a><h3>Видео-уроки</h3> Информационный портал с видео-уроками по работе с PHPShop на портале YouTube. Содержат подробные уроки по настройки и работе с 1С-Синхронизацией, PHPShop и утилитами EasyControl.<br>Адрес: <a href="http://www.youtube.com/user/phpshopsoftware" target="_blank">http://www.youtube.com/user/phpshopsoftware</a>', '1', 1, 0, '', '', '1', '0', ''),
(22, 'Условия оферты при оплате Visa и Mastercard', 'agreement', 0, '', '', '', '1', 1, 0, '', '', '1', '0', '');

INSERT INTO `phpshop_page` (`id`, `name`, `link`, `category`, `keywords`, `description`, `content`, `servers`, `num`, `datas`, `odnotip`, `title`, `enabled`, `secure`, `secure_groups`) VALUES
(43, 'Политика конфиденциальности', 'politika_konfidencialnosti', 0, '', '', '<h2>Политика конфиденциальности для интернет-магазина</h2>\r\n<p>В примере жирным я выделил то, что вам надо подправить для своего проекта.</p>\r\n<ol>\r\n<li>ОПРЕДЕЛЕНИЕ ТЕРМИНОВ\r\n<ol>\r\n<li>Существующая на текущий момент политика конфиденциальности персональных данных (далее &ndash; Политика конфиденциальности) работает со следующими понятиями:\r\n<ol>\r\n<li>&laquo;Администрация сайта Интернет-магазина (далее &ndash; Администрация сайта)&raquo;. Так называют представляющих интересы организации специалистов, в чьи обязанности входит управление сайтом, то есть организация и (или) обработка поступивших на него персональных данных. Для выполнения этих обязанностей они должны чётко представлять, для чего обрабатываются сведения, какие сведения должна быть обработаны, какие действия (операции) должны производиться с полученными сведениями.</li>\r\n<li>&laquo;Персональные данные&raquo; &mdash; сведения, имеющие прямое или косвенное отношение к определённому либо определяемому физическому лицу (также называемому субъектом персональных данных).</li>\r\n<li>&laquo;Обработка персональных данных&raquo; &mdash; любая операция (действие) либо совокупность таковых, которые Администрация производит с персональными данными. Их могут собирать, записывать, систематизировать, накапливать, хранить, уточнять (при необходимости обновлять или изменять), извлекать, использовать, передавать (распространять, предоставлять, открывать к ним доступ), обезличивать, блокировать, удалять и даже уничтожать. Данные операции (действия) могут выполняться как автоматически, так и вручную.</li>\r\n<li>&laquo;Конфиденциальность персональных данных&raquo; &mdash; обязательное требование, предъявляемое к Оператору или иному работающему с данными Пользователя должностному лицу, хранить полученные сведения в тайне, не посвящая в них посторонних, если предоставивший персональные данные Пользователь не изъявил своё согласие, а также отсутствует законное основание для разглашения.</li>\r\n<li>&laquo;Пользователь сайта Интернет-магазина&raquo; (далее &mdash; Пользователь)&raquo; &ndash; человек, посетивший сайт Интернет-магазина, а также пользующийся его программами и продуктами.</li>\r\n<li>&laquo;Cookies&raquo; &mdash; короткий фрагмент данных, пересылаемый веб-браузером или веб-клиентом веб-серверу в HTTP-запросе, всякий раз, когда Пользователь пытается открыть страницу Интернет-магазина. Фрагмент хранится на компьютере Пользователя.</li>\r\n<li>&laquo;IP-адрес&raquo; &mdash; уникальный сетевой адрес узла в компьютерной сети, построенной по протоколу TCP/IP.</li>\r\n</ol>\r\n</li>\r\n</ol>\r\n</li>\r\n<li>ОБЩИЕ ПОЛОЖЕНИЯ\r\n<ol>\r\n<li>Просмотр сайта Интернет-магазина, а также использование его программ и продуктов подразумевают автоматическое согласие с принятой там Политикой конфиденциальности, подразумевающей предоставление Пользователем персональных данных на обработку.</li>\r\n<li>Если Пользователь не принимает существующую Политику конфиденциальности, Пользователь должен покинуть Интернет-магазин.</li>\r\n<li>Имеющаяся Политика конфиденциальности распространяется только на сайт Интернет-магазина. Если по ссылкам, размещённым на сайте последнего, Пользователь зайдёт на ресурсы третьих лиц, Интернет-магазин за его действия ответственности не несёт.</li>\r\n<li>Проверка достоверности персональных данных, которые решил сообщить принявший Политику конфиденциальности Пользователь, не входит в обязанности Администрации сайта.</li>\r\n</ol>\r\n</li>\r\n<li>ПРЕДМЕТ ПОЛИТИКИ КОНФИДЕНЦИАЛЬНОСТИ\r\n<ol>\r\n<li>Согласно проводимой в текущий период Политике конфиденциальности Администрация Интернет-магазина обязана не разглашать персональные данные, сообщаемые Пользователями, регистрирующимися на сайте или оформляющими заказ на покупку товара, а также обеспечивать этим данным абсолютную конфиденциальность.</li>\r\n<li>Чтобы сообщить персональные данные, Пользователь заполняет расположенные на сайте интернет-магазина электронные формы. Персональными данными Пользователя, которые подлежат обработке, являются:\r\n<ol>\r\n<li>его фамилия, имя, отчество;</li>\r\n<li>его контактный телефон;</li>\r\n<li>его электронный адрес (e-mail);</li>\r\n<li>адрес, по которому должен быть доставлен купленный им товар;</li>\r\n<li>адрес проживания Пользователя.</li>\r\n</ol>\r\n</li>\r\n<li>Защита данных, автоматически передаваемых при просмотре рекламных блоков и посещении страниц с установленными на них статистическими скриптами системы (пикселями) осуществляется Интернет-магазином. Вот перечень этих данных:<br />IP-адрес;<br />сведения из cookies;<br />сведения о браузере (либо другой программе, через которую становится доступен показ рекламы);<br />время посещения сайта;<br />адрес страницы, на которой располагается рекламный блок;<br />реферер (адрес предыдущей страницы).</li>\r\n<li>Последствием отключения cookies может стать невозможность доступа к требующим авторизации частям сайта Интернет-магазина.</li>\r\n<li>Интернет-магазин собирает статистику об IP-адресах всех посетителей. Данные сведения нужны, чтобы выявить и решить технические проблемы и проконтролировать, насколько законным будет проведение финансовых платежей.</li>\r\n<li>Любые другие неоговорённые выше персональные сведения (о том, когда и какие покупки были сделаны, какой при этом использовался браузер, какая была установлена операционная система и пр.) надёжно хранятся и не распространяются. Исключение существующая Политика конфиденциальности предусматривает для случаев, описанных в п.п. 5.2 и 5.3.</li>\r\n</ol>\r\n</li>\r\n<li>ЦЕЛИ СБОРА ПЕРСОНАЛЬНОЙ ИНФОРМАЦИИ ПОЛЬЗОВАТЕЛЯ\r\n<ol>\r\n<li>Сбор персональных данных Пользователя Администрацией Интернет-магазина проводится ради того, чтобы:\r\n<ol>\r\n<li>Идентифицировать Пользователя, который прошёл процедуру регистрации на сайте Интернет-магазина, чтобы оформить заказ и (или) приобрести товар данного магазина дистанционно.</li>\r\n<li>Открыть Пользователю доступ к персонализированным ресурсам данного сайта.</li>\r\n<li>Установить с Пользователем обратную связь, под которой подразумевается, в частности, рассылка запросов и уведомлений, касающихся использования сайта Интернет-магазина, обработка пользовательских запросов и заявок, оказание прочих услуг.</li>\r\n<li>Определить местонахождение Пользователя, чтобы обеспечить безопасность платежей и предотвратить мошенничество.</li>\r\n<li>Подтвердить, что данные, которые предоставил Пользователь, полны и достоверны.</li>\r\n<li>Создать учётную запись для совершения Покупок, если Пользователь изъявил на то своё желание.</li>\r\n<li>Уведомить Пользователя о состоянии его заказа в Интернет-магазине.</li>\r\n<li>Обрабатывать и получать платежи, подтверждать налог или налоговые льготы, оспаривать платёж, определять, целесообразно ли предоставить конкретному Пользователю кредитную линию.</li>\r\n<li>Обеспечить Пользователю максимально быстрое решение проблем, встречающихся при использовании Интернет-магазина, за счёт эффективной клиентской и технической поддержки.</li>\r\n<li>Своевременно информировать Пользователя об обновлённой продукции, ознакомлять его с уникальными предложениями, новыми прайсами, новостями о деятельности Интернет-магазина или его партнёров и прочими сведениями, если Пользователь изъявит на то своё согласие.</li>\r\n<li>Рекламировать товары Интернет-магазина, если Пользователь изъявит на то своё согласие.</li>\r\n<li>Предоставить Пользователю доступ на сайты или сервисы Интернет-магазина, помогая ему тем самым получать продукты, обновления и услуги.</li>\r\n</ol>\r\n</li>\r\n</ol>\r\n</li>\r\n<li>СПОСОБЫ И СРОКИ ОБРАБОТКИ ПЕРСОНАЛЬНОЙ ИНФОРМАЦИИ\r\n<ol>\r\n<li>Срок обработки персональных данных Пользователя ничем не ограничен. Процедура обработки может проводиться любым предусмотренным законодательством способом. В частности, с помощью информационных&nbsp;систем&nbsp;персональных данных, которые могут вестись автоматически либо без средств автоматизации.</li>\r\n<li>Обработанные Администрацией сайта персональные данные Пользователя могут передаваться третьим лицам, в число которых входят курьерские службы, организации почтовой связи, операторы электросвязи. Делается это для того, чтобы выполнить заказ Пользователя, оставленный им на сайте Интернет-магазина, и доставить товар по адресу. Согласие Пользователя на подобную передачу предусмотрено правилами политики сайта.</li>\r\n<li>Также обработанные Администрацией сайта персональные данные могут передаваться уполномоченным органов государственной власти Российской Федерации, если это осуществляется на законных основаниях и в предусмотренном российским законодательством порядке.</li>\r\n<li>Если персональные данные будут утрачены или разглашены, Пользователь уведомляется об этом Администрацией сайта.</li>\r\n<li>Все действия Администрации сайта направлены на то, чтобы не допустить к персональным данным Пользователя третьих лиц (за исключением п.п. 5.2, 5.3). Последним эта информация не должна быть доступна даже случайно, дабы те не уничтожили её, не изменили и не блокировали, не копировали и не распространяли, а также не совершали прочие противозаконные действия. Для защиты пользовательских данных Администрация располагает комплексом организационных и технических мер.</li>\r\n<li>Если персональные данные будут утрачены либо разглашены, Администрация сайта совместно с Пользователем готова принять все возможные меры, дабы предотвратить убытки и прочие негативные последствия, вызванные данной ситуацией.</li>\r\n</ol>\r\n</li>\r\n<li>ОБЯЗАТЕЛЬСТВА СТОРОН\r\n<ol>\r\n<li>В обязанности Пользователя входит:\r\n<ol>\r\n<li>Сообщение соответствующих требованиям Интернет-магазина сведений о себе.</li>\r\n<li>Обновление и дополнение предоставляемых им сведений в случае изменения таковых.</li>\r\n</ol>\r\n</li>\r\n<li>В обязанности Администрации сайта входит:\r\n<ol>\r\n<li>Применение полученных сведений исключительно в целях, обозначенных в п. 4 существующей Политики конфиденциальности.</li>\r\n<li>Обеспечение конфиденциальности поступивших от Пользователя сведений. Они не должны разглашаться, если Пользователь не даст на то письменное разрешение. Также Администрация не имеет права продавать, обменивать, публиковать либо разглашать прочими способами переданные Пользователем персональные данные, исключая п.п. 5.2 и 5.3 существующей Политики конфиденциальности.</li>\r\n<li>Принятие мер предосторожности, дабы персональные данные Пользователя оставались строго конфиденциальными, точно также, как остаются конфиденциальными такого рода сведения в современном деловом обороте.</li>\r\n<li>Блокировка персональных пользовательских данных с того момента, с которого Пользователь либо его законный представитель сделает соответствующий запрос. Право сделать запрос на блокировку также предоставляется органу, уполномоченному защищать права Пользователя, предоставившего Администрации сайта свои данные, на период проверки, в случае обнаружения недостоверности сообщённых персональных данных либо неправомерности действий.</li>\r\n</ol>\r\n</li>\r\n</ol>\r\n</li>\r\n<li>ОТВЕТСТВЕННОСТЬ СТОРОН\r\n<ol>\r\n<li>В случае неисполнения Администрацией сайта собственных обязательств и, как следствие, убытков Пользователя, понесённых из-за неправомерного использования предоставленной им информации, ответственность возлагается на неё. Об этом, в частности, утверждает российское законодательство. Исключение существующая в настоящее время Политика конфиденциальности делает для случаев, отражённых в п.п. 5.2, 5.3 и 7.2.</li>\r\n<li>Но существует ряд случаев, когда Администрация сайта ответственности не несёт, если пользовательские данные утрачиваются или разглашаются. Это происходит тогда, когда они:\r\n<ol>\r\n<li>Превратились в достояние общественности до того, как были утрачены или разглашены.</li>\r\n<li>Были предоставлены третьими лицами до того, как их получила Администрация сайта.</li>\r\n<li>Разглашались с согласия Пользователя.</li>\r\n</ol>\r\n</li>\r\n</ol>\r\n</li>\r\n<li>РАЗРЕШЕНИЕ СПОРОВ\r\n<ol>\r\n<li>Если Пользователь недоволен действиями Администрации Интернет-магазина и намерен отстаивать свои права в суде, до того как обратиться с иском, он в обязательном порядке должен предъявить претензию (письменно предложить урегулировать конфликт добровольно).</li>\r\n<li>Получившая претензию Администрация обязана в течение 30 календарных дней с даты её получения письменно уведомить Пользователя о её рассмотрении и принятых мерах.</li>\r\n<li>Если обе стороны так и не смогли договориться, спор передаётся в судебный орган, где его должны рассмотреть согласно действующему российскому законодательству.</li>\r\n<li>Регулирование отношений Пользователя и Администрации сайта в Политике конфиденциальности проводится согласно действующему российскому законодательству.</li>\r\n</ol>\r\n</li>\r\n<li>ДОПОЛНИТЕЛЬНЫЕ УСЛОВИЯ\r\n<ol>\r\n<li>Администрация сайта вправе менять существующую на текущий момент Политику конфиденциальности, не спрашивая согласия у Пользователя.</li>\r\n<li>Вступление в силу новой Политики конфиденциальности начинается после того, как информация о ней будет выложена на сайт Интернет-магазина, если изменившаяся Политика не подразумевает иного варианта размещения.</li>\r\n<li>&nbsp;Все предложения, пожелания, требования или вопросы по настоящей Политике конфиденциальности следует сообщать в раздел обратной связи, расположенный по адресу:&nbsp;<strong>(ссылка)</strong>. Или путем отправки электронного письма по адресу&nbsp;<strong>(тут ваш email)</strong></li>\r\n<li>Прочитать о существующей Политике конфиденциальности можно, зайдя на страницу по&nbsp;<strong>адресу www.адрес магазина.ru</strong></li>\r\n</ol>\r\n</li>\r\n</ol>', '', 1, 0, '', '', '1', '0', ''),
(44, 'Согласие на обработку персональных данных', 'soglasie_na_obrabotku_personalnyh_dannyh', 0, '', '', '<p>Согласие на обработку персональных данных</p>\r\n<p>Настоящим я, далее &ndash; &laquo;Субъект Персональных Данных&raquo;, во исполнение требований Федерального закона от 27.07.2006 г. № 152-ФЗ &laquo;О персональных данных&raquo; (с изменениями и дополнениями) свободно, своей волей и в своем интересе даю свое согласие&nbsp;<strong>Индивидуальном предпринимателю Иванову Ивану Ивановичу</strong>&nbsp;(далее &ndash; &laquo;Интернет-магазин&raquo;, адрес:&nbsp;<strong>(тут ваш адрес)&nbsp;</strong>) на обработку своих персональных данных, указанных при регистрации путем заполнения веб-формы на сайте Интернет-магазина&nbsp;<strong>вашдомен.ру</strong>&nbsp;и его поддоменов&nbsp;<strong>*.вашдомен.ру</strong>&nbsp;(далее &ndash; Сайт), направляемой (заполненной) с использованием Сайта.</p>\r\n<p>Под персональными данными я понимаю любую информацию, относящуюся ко мне как к Субъекту Персональных Данных, в том числе мои фамилию, имя, отчество, адрес, образование, профессию, контактные данные (телефон, факс, электронная почта, почтовый адрес), фотографии,&nbsp; иную другую информацию. Под обработкой персональных данных я понимаю сбор, систематизацию, накопление, уточнение, обновление, изменение, использование, распространение, передачу, в том числе трансграничную, обезличивание, блокирование, уничтожение, бессрочное хранение), и любые другие действия (операции) с персональными данными.</p>\r\n<p>Обработка персональных данных Субъекта Персональных Данных осуществляется исключительно в целях регистрации Субъекта Персональных Данных в базе данных Интернет-магазина с последующим направлением Субъекту Персональных Данных почтовых сообщений и смс-уведомлений, в том числе рекламного содержания, от Интернет-магазина, его аффилированных лиц и/или субподрядчиков, информационных и новостных рассылок,&nbsp; приглашений на мероприятия Интернет-магазина и другой информации рекламно-новостного содержания, а также с целью подтверждения личности Субъекта Персональных Данных при посещении мероприятий Интернет-магазина.</p>\r\n<p>Датой выдачи согласия на обработку персональных данных Субъекта Персональных Данных является дата отправки регистрационной веб-формы с Сайта Интернет-магазина.</p>\r\n<p>Обработка персональных данных Субъекта Персональных Данных может осуществляться с помощью средств автоматизации и/или без использования средств автоматизации в соответствии с действующим законодательством РФ и внутренними положениями Интернет-магазина.</p>\r\n<p>Интернет-магазин принимает необходимые правовые, организационные и технические меры или обеспечивает их принятие для защиты персональных данных от неправомерного или случайного доступа к ним, уничтожения, изменения, блокирования, копирования, предоставления, распространения персональных данных, а также от иных неправомерных действий в отношении персональных данных, а также принимает на себя обязательство сохранения конфиденциальности персональных данных Субъекта Персональных Данных. Интернет-магазин вправе привлекать для обработки персональных данных Субъекта Персональных Данных субподрядчиков, а также вправе передавать персональные данные для обработки своим аффилированным лицам, обеспечивая при этом принятие такими субподрядчиками и аффилированными лицами соответствующих обязательств в части конфиденциальности персональных данных.</p>\r\n<p>Я ознакомлен(а), что:</p>\r\n<ol>\r\n<li>настоящее согласие на обработку моих персональных данных, указанных при регистрации на Сайте Интернет-магазина, направляемых (заполненных) с использованием Cайта, действует в течение 20 (двадцати) лет с момента регистрации на Cайте Интернет-магазина;</li>\r\n<li>согласие может быть отозвано мною на основании письменного заявления в произвольной форме;</li>\r\n<li>предоставление персональных данных третьих лиц без их согласия влечет ответственность в соответствии с действующим законодательством Российской Федерации.</li>\r\n</ol>', '', 1, 0, '', '', '1', '0', '');



-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_page_categories`
--

CREATE TABLE `phpshop_page_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `num` int(64) DEFAULT '1',
  `parent_to` int(11) DEFAULT '0',
  `content` text,
  `page_cat_seo_name` VARCHAR(255) DEFAULT '',
  `servers` varchar(64) default '',
  PRIMARY KEY (`id`),
  KEY `parent_to` (`parent_to`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_page_categories`
--

INSERT INTO `phpshop_page_categories` (`id`, `name`, `num`, `parent_to`, `content`) VALUES
(4, 'Учебные материалы', 0, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_parent_name`
--

CREATE TABLE `phpshop_parent_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `enabled` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_parent_name`
--

INSERT INTO `phpshop_parent_name` (`id`, `name`, `enabled`) VALUES
(1, 'Габариты', '1'),
(2, 'Цвет корпуса', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_payment`
--

CREATE TABLE `phpshop_payment` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `sum` float DEFAULT '0',
  `datas` int(11) DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `order` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_payment_systems`
--

CREATE TABLE `phpshop_payment_systems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `path` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '1',
  `num` tinyint(11) DEFAULT '0',
  `message` text,
  `message_header` text,
  `yur_data_flag` enum('0','1') DEFAULT '0',
  `icon` varchar(255) DEFAULT '',
  `color` varchar(64) default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_payment_systems`
--

INSERT INTO `phpshop_payment_systems` (`id`, `name`, `path`, `enabled`, `num`, `message`, `message_header`, `yur_data_flag`, `icon`) VALUES
(1, 'Банковский перевод', 'bank', '1', 4, '<img src="/UserFiles/Image/Trial/rabbit.png" alt="" align="" border="0"><h3>Благодарим Вас за заказ!</h3><p>Счет уже доступен в Вашем&nbsp;<a href="/users/order.html">личном кабинете</a>.&nbsp;</p><p>Пароли доступа от личного кабинета находятся в Вашей почте.</p>', '', '1', '/UserFiles/Image/Payments/beznal.png'),
(3, 'Наличная оплата', 'message', '1', 0, '<img src="/UserFiles/Image/Trial/rabbit.png" alt="" align="" border="0"><h3>Благодарим Вас за заказ!</h3>В ближайшее время с Вами свяжется наш менеджер для уточнения деталей.', '', '', '/UserFiles/Image/Payments/nal.png'),
(10032, 'Visa, Mastercard (Tinkoff)', 'modules', '0', 0, '', '', '', '/UserFiles/Image/Payments/tinkoff.png');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_photo`
--

CREATE TABLE `phpshop_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '0',
  `name` varchar(64) DEFAULT '',
  `num` tinyint(11) DEFAULT '0',
  `info` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_photo_categories`
--

CREATE TABLE `phpshop_photo_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_to` int(11) DEFAULT '0',
  `link` varchar(64) DEFAULT '',
  `name` varchar(64) DEFAULT '',
  `num` tinyint(11) DEFAULT '0',
  `content` text,
  `enabled` enum('0','1') DEFAULT '0',
  `page` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_products`
--

CREATE TABLE `phpshop_products` (
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
  `prod_seo_name` varchar(255) DEFAULT '',
  `price_search` float DEFAULT '0',
  `parent2` text,
  `color` varchar(64) DEFAULT NULL,
  `prod_seo_name_old` VARCHAR(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `enabled` (`enabled`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_products`
--

INSERT INTO `phpshop_products` (`id`, `category`, `name`, `description`, `content`, `price`, `price_n`, `sklad`, `p_enabled`, `enabled`, `uid`, `spec`, `odnotip`, `vendor`, `vendor_array`, `yml`, `num`, `newtip`, `title`, `title_enabled`, `datas`, `page`, `user`, `descrip`, `descrip_enabled`, `title_shablon`, `descrip_shablon`, `keywords`, `keywords_enabled`, `keywords_shablon`, `pic_small`, `pic_big`, `yml_bid_array`, `parent_enabled`, `parent`, `items`, `weight`, `price2`, `price3`, `price4`, `price5`, `files`, `baseinputvaluta`, `ed_izm`, `dop_cat`, `rate`, `rate_count`, `prod_seo_name`, `price_search`, `parent2`, `color`) VALUES
(1, 8, 'Водостойкая тушь для ресниц', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', 4000, 7000, '0', '1', '1', '356542', '1', '1,8,14', 'i1-2ii3-10i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2232223b7d693a333b613a313a7b693a303b733a323a223130223b7d7d, '1', 0, '1', '', '0', 1522079380, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img1_22472s.png', '/UserFiles/Image/Trial/img1_22472.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 30, 40, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(2, 8, 'Бальзам для губ Loreal', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', 2300, 4000, '0', '1', '1', '345464', '0', '13,6,10', 'i1-1ii3-10i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2231223b7d693a333b613a313a7b693a303b733a323a223130223b7d7d, '1', 0, '1', '', '0', 1522079462, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img2_78894s.png', '/UserFiles/Image/Trial/img2_78894.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(3, 8, 'Невесомая пудра', '<p>Модная губная помада Dior Addict приобрела новую вариацию - Extreme с её сияющими цветами, идеально стойкой текстурой с эффектом влажных губ и смелым, утонченным стилем Dior.</p>', '<p>Модная губная помада Dior Addict приобрела новую вариацию - Extreme с её сияющими цветами, идеально стойкой текстурой с эффектом влажных губ и смелым, утонченным стилем Dior.</p>', 6000, 0, '0', '1', '1', '345724', '0', '12,4,10', 'i1-6ii3-10ii3-9i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2236223b7d693a333b613a323a7b693a303b733a323a223130223b693a313b733a313a2239223b7d7d, '1', 0, '1', '', '0', 1522079493, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img3_16362s.png', '/UserFiles/Image/Trial/img3_16362.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 50, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(4, 8, 'Лосьон Vichy', '<p>Тушь для ресниц PhotoReady Revlon</p>', '<p>Созданная на основе стойкой инновационной формулы, она идеально ложится на ресницы, полностью окрашивая их. </p>', 2300, 3000, '0', '1', '1', '456879', '0', '13,6,10', 'i1-7ii3-9i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2237223b7d693a333b613a313a7b693a303b733a313a2239223b7d7d, '1', 0, '1', '', '0', 1522079488, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img4_42389s.png', '/UserFiles/Image/Trial/img4_42389.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(5, 8, 'Компактная пудра Vichy', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', 500, 700, '0', '1', '1', '486837', '0', '10,16,5,3', 'i1-7ii3-8ii3-9i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2237223b7d693a333b613a323a7b693a303b733a313a2238223b693a313b733a313a2239223b7d7d, '1', 0, '1', '', '0', 1522079392, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img5_38508s.jpg', '/UserFiles/Image/Trial/img5_38508.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 2, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(6, 8, 'Помада Maybelline', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', 600, 1000, '0', '1', '1', '486847', '0', '13,6,10', 'i1-3ii3-8i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2233223b7d693a333b613a313a7b693a303b733a313a2238223b7d7d, '1', 0, '1', '', '0', 1522079499, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img6_66962s.jpg', '/UserFiles/Image/Trial/img6_66962.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 10, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(7, 9, 'Бальзам для губ Lip Glow', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', 2300, 4000, '0', '1', '1', '345464', '0', '16,17,13,15,14,18', 'i1-1ii3-8i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2231223b7d693a333b613a313a7b693a303b733a313a2238223b7d7d, '1', 0, '1', '', '0', 1522079521, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img7_29787s.png', '/UserFiles/Image/Trial/img7_29787.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(8, 9, 'Водостойкая тушь для ресниц', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', 4000, 7000, '0', '1', '1', '356542', '0', '16,17,13,15,14,18', 'i1-2ii3-9i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2232223b7d693a333b613a313a7b693a303b733a313a2239223b7d7d, '1', 0, '1', '', '0', 1522079550, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img8_36236s.png', '/UserFiles/Image/Trial/img8_36236.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 30, 40, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(9, 9, 'Кисти для макияжа', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', 500, 700, '0', '1', '1', '486837', '0', '16,17,13,15,14,18', 'i1-2i', 0x613a313a7b693a313b613a313a7b693a303b733a313a2232223b7d7d, '1', 0, '1', '', '0', 1522079564, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img9_13210s.jpg', '/UserFiles/Image/Trial/img9_13210.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 2, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(10, 9, 'Компактная пудра Forever', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', 600, 1000, '0', '1', '1', '486847', '0', '16,17,13,15,14,18', 'i1-3ii3-8ii3-9i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2233223b7d693a333b613a323a7b693a303b733a313a2238223b693a313b733a313a2239223b7d7d, '1', 0, '1', '', '0', 1522079574, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img10_91260s.png', '/UserFiles/Image/Trial/img10_91260.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 10, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(11, 9, 'Праймер Nude Shimmer', '<p>Модная губная помада Dior Addict приобрела новую вариацию - Extreme с её сияющими цветами, идеально стойкой текстурой с эффектом влажных губ и смелым, утонченным стилем Dior.</p>', '<p>Модная губная помада Dior Addict приобрела новую вариацию - Extreme с её сияющими цветами, идеально стойкой текстурой с эффектом влажных губ и смелым, утонченным стилем Dior.</p>', 6000, 0, '0', '1', '1', '345724', '0', '7,8,9,10,12,11', 'i1-6ii3-8i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2236223b7d693a333b613a313a7b693a303b733a313a2238223b7d7d, '1', 0, '1', '', '0', 1522079595, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img11_26365s.png', '/UserFiles/Image/Trial/img11_26365.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 50, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(12, 9, 'Лосьон Maybelline', '<p>Тушь для ресниц PhotoReady Revlon</p>', '<p>Созданная на основе стойкой инновационной формулы, она идеально ложится на ресницы, полностью окрашивая их. </p>', 2300, 3000, '0', '1', '1', '456879', '0', '16,17,13,15,14,18', 'i1-3i', 0x613a313a7b693a313b613a313a7b693a303b733a313a2233223b7d7d, '1', 0, '1', '', '0', 1522079583, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img12_70307s.png', '/UserFiles/Image/Trial/img12_70307.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(13, 7, 'Помада Loreal', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', 2300, 4000, '0', '1', '1', '345464', '0', '7,8,9,10,12,11', 'i1-1ii3-8i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2231223b7d693a333b613a313a7b693a303b733a313a2238223b7d7d, '1', 0, '1', '', '0', 1522079633, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img13_79095s.png', '/UserFiles/Image/Trial/img13_79095.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(14, 7, 'Тушь для ресниц', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', '<p>Обволакивает ресницы удлиняя их и придавая им объем. Гарантирует 12 часов стойкости. Водостойкая формула, защищающая ресницы от от вредного воздействия влаги, соли (морская вода), хлора (вода в бассейне) и солнца.</p>', 4000, 7000, '0', '1', '1', '356542', '0', '7,8,9', 'i1-7ii3-9i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2237223b7d693a333b613a313a7b693a303b733a313a2239223b7d7d, '1', 0, '1', '', '0', 1522079674, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img14_30791s.png', '/UserFiles/Image/Trial/img14_30791.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 30, 40, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(15, 7, 'Тени Loreal', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', 500, 700, '0', '1', '1', '486837', '0', '16,17,13', 'i1-1ii3-10ii3-8ii3-9i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2231223b7d693a333b613a333a7b693a303b733a323a223130223b693a313b733a313a2238223b693a323b733a313a2239223b7d7d, '1', 0, '1', '', '0', 1522079645, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img15_38381s.jpg', '/UserFiles/Image/Trial/img15_38381.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 2, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(16, 7, 'Компактная пудра Forever', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', '<p>Благодаря входящим в ее состав активным питательным и увлажняющим компонентам пудра идеально защищает Вашу кожу, создавая ощущение комфорта.</p>', 600, 1000, '0', '1', '1', '486847', '0', '16,17,13,15,14,18', 'i1-2ii3-10i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2232223b7d693a333b613a313a7b693a303b733a323a223130223b7d7d, '1', 0, '1', '', '0', 1522079607, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img16_18757s.jpg', '/UserFiles/Image/Trial/img16_18757.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 10, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 4, 1, '', 0, NULL, NULL),
(17, 7, 'Масло для ресниц PhotoReady', '<p>Тушь для ресниц PhotoReady Revlon</p>', '<p>Созданная на основе стойкой инновационной формулы, она идеально ложится на ресницы, полностью окрашивая их. </p>', 2300, 3000, '0', '1', '1', '456879', '0', '2,1,5,4,3,6', 'i1-2ii3-8i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2232223b7d693a333b613a313a7b693a303b733a313a2238223b7d7d, '1', 0, '1', '', '0', 1522079617, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img17_15588s.png', '/UserFiles/Image/Trial/img17_15588.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 100, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 4, 1, '', 0, NULL, NULL),
(18, 7, 'Шиммер Nude', '<p>Модная губная помада Dior Addict приобрела новую вариацию - Extreme с её сияющими цветами, идеально стойкой текстурой с эффектом влажных губ и смелым, утонченным стилем Dior.</p>', '<p>Модная губная помада Dior Addict приобрела новую вариацию - Extreme с её сияющими цветами, идеально стойкой текстурой с эффектом влажных губ и смелым, утонченным стилем Dior.</p>', 6000, 0, '0', '1', '1', '345724', '0', '9,10,12', 'i1-6ii3-10i', 0x613a323a7b693a313b613a313a7b693a303b733a313a2236223b7d693a333b613a313a7b693a303b733a323a223130223b7d7d, '1', 0, '1', '', '0', 1522079689, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img18_31371s.png', '/UserFiles/Image/Trial/img18_31371.png', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 50, 10, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(19, 1, 'Платье Mango', '<p>Платье выполнено из приятного на ощупь материала. Силиконовая лента по верхнему внутреннему канту, изящные оборки, пояс декорирован сверкающими кристаллами.</p>', '<p>Платье выполнено из приятного на ощупь материала. Силиконовая лента по верхнему внутреннему канту, изящные оборки, пояс декорирован сверкающими кристаллами.</p>', 3400, 4000, '0', '1', '1', '129699', '1', '52,54,55', 'i6-14ii7-15ii7-16ii7-17ii8-26ii8-22i', 0x613a333a7b693a363b613a313a7b693a303b733a323a223134223b7d693a373b613a333a7b693a303b733a323a223135223b693a313b733a323a223136223b693a323b733a323a223137223b7d693a383b613a323a7b693a303b733a323a223236223b693a313b733a323a223232223b7d7d, '1', 0, '1', '', '0', 1522079712, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img19_78131s.jpg', '/UserFiles/Image/Trial/img19_78131.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '88,87,86,85', 100, 500, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(20, 1, 'Платье из плотного материала', '<p>Платье из плотного слегка тянущегося текстиля молочного цвета. Глубокий круглый вырез декорирован вышивкой крупными бусинами. Детали: застежка на молнию на спинке, вытачки обеспечивают комфортную посадку по фигуре, лаконичный крой.</p>', '<p>Платье из плотного слегка тянущегося текстиля молочного цвета. Глубокий круглый вырез декорирован вышивкой крупными бусинами. Детали: застежка на молнию на спинке, вытачки обеспечивают комфортную посадку по фигуре, лаконичный крой.</p>', 5000, 8000, '0', '1', '1', '8769786', '1', '31,19,24', 'i6-12ii7-17ii7-18ii8-26i', 0x613a333a7b693a363b613a313a7b693a303b733a323a223132223b7d693a373b613a323a7b693a303b733a323a223137223b693a313b733a323a223138223b7d693a383b613a313a7b693a303b733a323a223236223b7d7d, '1', 0, '1', '', '0', 1522079701, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img20_11241s.jpg', '/UserFiles/Image/Trial/img20_11241.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '91,90,89', 33, 200, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(21, 1, 'Жакет серый', '<p>Платье  - идеальный выбор на каждый день. Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. </p>', '<p>Платье  - идеальный выбор на каждый день. Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. </p>', 800, 1000, '0', '1', '1', '0980987', '1', '26,27,30', 'i6-13ii6-13ii7-nullii8-nulli', 0x613a333a7b693a363b613a323a7b693a303b733a323a223133223b693a313b733a323a223133223b7d693a373b613a313a7b693a303b733a343a226e756c6c223b7d693a383b613a313a7b693a303b733a343a226e756c6c223b7d7d, '1', 0, '1', '', '0', 1523282107, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img21_81261s.jpg', '/UserFiles/Image/Trial/img21_81261.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '84,83,82', 99, 200, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 'zhaket-seryy', 0, NULL, NULL),
(22, 1, 'Платье черное', '<div class="product-info-box">\n<div class="content panel-smart">\n<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>\n</div>\n</div>\n<div class="product-info-box empty-check"> </div>', '<div class="product-info-box">\n<div class="content panel-smart">\n<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>\n</div>\n</div>\n<div class="product-info-box empty-check"> </div>', 4500, 7800, '0', '1', '1', '09870986', '1', '30,25,28', 'i6-13ii6-13ii7-nullii8-nulli', 0x613a333a7b693a363b613a323a7b693a303b733a323a223133223b693a313b733a323a223133223b7d693a373b613a313a7b693a303b733a343a226e756c6c223b7d693a383b613a313a7b693a303b733a343a226e756c6c223b7d7d, '1', 0, '1', '', '0', 1523371558, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img22_73532s.jpg', '/UserFiles/Image/Trial/img22_73532.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '81,80,79,78', 70, 300, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, 'plate-chernoe', 0, NULL, NULL),
(23, 1, 'Жакет из легкого струящегося материала', '<p>Платье из легкого струящегося материала. Детали: полуприлегающий крой, круглый вырез, эффектно располосованный рукав 3/4, цветочный принт, мягкая подкладка, вырез капелька на спинке.</p>', '<p>Платье из легкого струящегося материала. Детали: полуприлегающий крой, круглый вырез, эффектно располосованный рукав 3/4, цветочный принт, мягкая подкладка, вырез капелька на спинке.</p>', 5600, 7800, '0', '1', '1', '0987089', '1', '19,24,22', 'i6-12i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223132223b7d7d, '1', 0, '1', '', '0', 1522079779, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img23_26114s.jpg', '/UserFiles/Image/Trial/img23_26114.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '73,72,71,70', 80, 300, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(24, 1, 'Платье из мягкого материала', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета.</p>', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', 9900, 11000, '0', '1', '1', '900897', '1', '26,27,30', 'i6-14i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223134223b7d7d, '1', 0, '1', '', '0', 1522079758, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img24_16757s.jpg', '/UserFiles/Image/Trial/img24_16757.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '77,76,75,74', 70, 560, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(25, 4, 'Очки', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', 15000, 0, '0', '1', '1', '', '0', '23,21,31', 'i6-14i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223134223b7d7d, '1', 0, '1', '', '0', 1522079879, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img25_66005s.jpg', '/UserFiles/Image/Trial/img25_66005.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '93,92', 23, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(26, 4, 'Ботинки мужские', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', 12000, 0, '0', '1', '1', '9878790', '0', '21,19,24', '', 0x4e3b, '1', 0, '1', '', '0', 1522079826, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img26_53995s.jpg', '/UserFiles/Image/Trial/img26_53995.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '104,103,102', 66, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(27, 4, 'Кроссовки спортивные', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', 13000, 0, '0', '1', '1', '', '0', '24,20,22', 'i6-11i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223131223b7d7d, '1', 0, '1', '', '0', 1522079871, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img27_18062s.jpg', '/UserFiles/Image/Trial/img27_18062.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '95,94', 55, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(28, 4, 'Ремень', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', 6000, 6500, '1', '1', '1', '567850', '0', '26,27,30', 'i6-14i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223134223b7d7d, '1', 0, '1', '', '0', 1522079838, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img28_54487s.jpg', '/UserFiles/Image/Trial/img28_54487.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '101,100', 60, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(29, 4, 'Сумка женская кожаная', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', '<p>Вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', 15500, 0, '0', '1', '1', '09860', '0', '31,19,24', 'i6-13i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223133223b7d7d, '1', 0, '1', '', '0', 1522079860, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img29_38525s.jpg', '/UserFiles/Image/Trial/img29_38525.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '97,96', 50, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(30, 4, 'Кроссовки спортивные Boss', '<p>Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', 16000, 0, '0', '1', '1', '098097', '0', '21,31,20', 'i6-14i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223134223b7d7d, '1', 0, '1', '', '0', 1522079846, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img30_32460s.jpg', '/UserFiles/Image/Trial/img30_32460.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '99,98', 50, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(31, 1, 'Комбинезон', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета.</p>', '<p>Модель приталенного кроя выполнена из мягкого хлопкового материала синего цвета. Детали: вырез-каре на груди, вытачки для комфортной посадки, тонкая подкладка, фактурное оформление ткани, функциональная молния на спинке.</p>', 11000, 11000, '1', '1', '1', '900897', '1', '31,24,20', 'i6-14i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223134223b7d7d, '1', 0, '1', '', '0', 1522079803, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img31_22065s.jpg', '/UserFiles/Image/Trial/img31_22065.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '69,68,67', 70, 560, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(32, 1, 'Шорты', '<p>Платье из легкого струящегося материала. Детали: полуприлегающий крой, круглый вырез, эффектно располосованный рукав 3/4, цветочный принт, мягкая подкладка, вырез капелька на спинке.</p>', '<p>Платье из легкого струящегося материала. Детали: полуприлегающий крой, круглый вырез, эффектно располосованный рукав 3/4, цветочный принт, мягкая подкладка, вырез капелька на спинке.</p>', 7800, 7800, '0', '1', '1', '0987089', '1', '26,30,28', 'i6-12i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223132223b7d7d, '1', 0, '1', '', '0', 1522079794, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img32_25817s.jpg', '/UserFiles/Image/Trial/img32_25817.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '66,65,64,63', 80, 300, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(33, 6, 'Телефон Apple', '<p>байонет Canon EF/EF-S</p>\r\n<p>объектив в комплекте, модель уточняйте у продавца</p>\r\n<p>матрица 18.5 МП (APS-C)</p>\r\n<p>съемка видео Full HD</p>\r\n<p>поворотный сенсорный экран 3</p>\r\n<p>вес камеры без объектива 575г</p>', '<p>байонет Canon EF/EF-S</p>\r\n<p>объектив в комплекте, модель уточняйте у продавца</p>\r\n<p>матрица 18.5 МП (APS-C)</p>\r\n<p>съемка видео Full HD</p>\r\n<p>поворотный сенсорный экран 3</p>\r\n<p>вес камеры без объектива 575г</p>', 46000, 7000, '0', '1', '1', '9809000', '0', '37,35,34', 'i12-28i', 0x613a313a7b693a31323b613a313a7b693a303b733a323a223238223b7d7d, '1', 0, '1', '', '0', 1522079894, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img33_61292s.jpg', '/UserFiles/Image/Trial/img33_61292.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '115', 70, 200, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(34, 6, 'Samsung', '<p>съемка видео Full HD</p>\r\n<p>поворотный сенсорный экран 3</p>\r\n<p>вес камеры без объектива 575г</p>', '<p>съемка видео Full HD</p>\r\n<p>поворотный сенсорный экран 3</p>\r\n<p>вес камеры без объектива 575г</p>', 60000, 65000, '0', '1', '1', '', '0', '38,34,36', 'i12-29i', 0x613a313a7b693a31323b613a313a7b693a303b733a323a223239223b7d7d, '1', 0, '1', '', '0', 1522079938, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img34_60925s.jpg', '/UserFiles/Image/Trial/img34_60925.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '110,109', 77, 60, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(35, 6, 'HP ProBook 450', '<p>байонет Canon EF/EF-S</p>\r\n<p>объектив в комплекте, модель уточняйте у продавца</p>\r\n<p>матрица 18.5 МП (APS-C)</p>', '<p>байонет Canon EF/EF-S</p>\r\n<p>объектив в комплекте, модель уточняйте у продавца</p>\r\n<p>матрица 18.5 МП (APS-C)</p>', 125000, 127000, '1', '1', '1', '0789876', '0', '38,35,34', 'i12-28i', 0x613a313a7b693a31323b613a313a7b693a303b733a323a223238223b7d7d, '1', 0, '1', '', '0', 1522079909, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img35_98263s.jpg', '/UserFiles/Image/Trial/img35_98263.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '114,113', 45, 500, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(36, 6, 'Samsung Gear VR', '<p>съемка видео Full HD</p>\r\n<p>поворотный сенсорный экран 3</p>\r\n<p>вес камеры без объектива 575г</p>', '<p>съемка видео Full HD</p>\r\n<p>поворотный сенсорный экран 3</p>\r\n<p>вес камеры без объектива 575г</p>', 67000, 0, '0', '1', '1', '1882234', '0', '37,38,35', 'i12-28i', 0x613a313a7b693a31323b613a313a7b693a303b733a323a223238223b7d7d, '1', 0, '1', '', '0', 1522079923, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img36_62837s.jpg', '/UserFiles/Image/Trial/img36_62837.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '112,111', 56, 700, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(37, 6, ' Lenovo IdeaPad 110', '<p>съемка видео Full HD</p>\r\n<p>поворотный сенсорный экран 3</p>\r\n<p>вес камеры без объектива 575г</p>', '<p>съемка видео Full HD</p>\r\n<p>поворотный сенсорный экран 3</p>\r\n<p>вес камеры без объектива 575г</p>', 64000, 0, '0', '1', '1', '45674567', '0', '38,35,34', 'i12-29i', 0x613a313a7b693a31323b613a313a7b693a303b733a323a223239223b7d7d, '1', 0, '1', '', '0', 1522079949, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img37_19544s.jpg', '/UserFiles/Image/Trial/img37_19544.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '108,107', 45, 1200, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(38, 6, ' Vivo V5', '<p>байонет Canon EF/EF-S</p>\r\n<p>объектив в комплекте, модель уточняйте у продавца</p>\r\n<p>матрица 18.5 МП (APS-C)</p>', '<p>байонет Canon EF/EF-S</p>\r\n<p>объектив в комплекте, модель уточняйте у продавца</p>\r\n<p>матрица 18.5 МП (APS-C)</p>', 35000, 0, '0', '1', '1', '9879087', '0', '38,35,34', 'i12-28i', 0x613a313a7b693a31323b613a313a7b693a303b733a323a223238223b7d7d, '1', 0, '1', '', '0', 1522079962, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img38_22872s.jpg', '/UserFiles/Image/Trial/img38_22872.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '106,105', 0, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(39, 12, 'Беговая дорожка', '<p class="pp">Вес товара с упаковкой (г): 0.45 г</p>\r\n<p class="pp">Длина окружности: 23 см</p>\r\n<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>\r\n<p class="pp">Комплектация: мяч</p>', '<p class="pp">Вес товара с упаковкой (г): 0.45 г</p>\r\n<p class="pp">Длина окружности: 23 см</p>\r\n<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>\r\n<p class="pp">Комплектация: мяч</p>', 45000, 0, '0', '1', '1', '098089', '0', '47,48,45', '', 0x4e3b, '1', 0, '1', '', '0', 1522079978, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img39_86997s.jpg', '/UserFiles/Image/Trial/img39_86997.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '117,116', 5, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(40, 12, 'Шлем', '<p class="pp">Вес товара с упаковкой (г): 0.45 г</p>\r\n<p class="pp">Длина окружности: 23 см</p>\r\n<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>\r\n<p class="pp">Комплектация: мяч</p>', '<p class="pp">Вес товара с упаковкой (г): 0.45 г</p>\r\n<p class="pp">Длина окружности: 23 см</p>', 5000, 0, '0', '1', '1', '0798098', '0', '38,35,34', '', 0x4e3b, '1', 0, '1', '', '0', 1522080030, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img40_42498s.jpg', '/UserFiles/Image/Trial/img40_42498.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '127,126', 0, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(41, 12, 'Зашита на колено', '<p class="pp">Вес товара с упаковкой (г): 0.45 г</p>\r\n<p class="pp">Длина окружности: 23 см</p>\r\n<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>\r\n<p class="pp">Комплектация: мяч</p>', '<p class="pp">Вес товара с упаковкой (г): 0.45 г</p>\r\n<p class="pp">Длина окружности: 23 см</p>\r\n<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>\r\n<p class="pp">Комплектация: мяч</p>', 600, 0, '0', '1', '1', '253458', '0', '39,41,50', '', 0x4e3b, '1', 0, '1', '', '0', 1522079990, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img41_13435s.jpg', '/UserFiles/Image/Trial/img41_13435.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '119,118', 8, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(42, 12, 'Сумка спортивная', '<p class="pp">Вид застежки: Молния</p>\r\n<p class="pp">Фактура материала: кожаный</p>\r\n<p class="pp">Назначение ремня: На плечо</p>\r\n<p class="pp">Карманы: Внутренний на молнии</p>\r\n<p class="pp">Количество отделений: 3 шт.</p>\r\n<p class="pp">Модель сумки: через плечо</p>', '<p class="pp">Вид застежки: Молния</p>\r\n<p class="pp">Фактура материала: кожаный</p>\r\n<p class="pp">Назначение ремня: На плечо</p>\r\n<p class="pp">Карманы: Внутренний на молнии</p>\r\n<p class="pp">Количество отделений: 3 шт.</p>\r\n<p class="pp">Модель сумки: через плечо</p>', 3500, 0, '0', '1', '1', '857732', '0', '56,52,55', '', 0x4e3b, '1', 0, '1', '', '0', 1522080019, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img42_63520s.jpg', '/UserFiles/Image/Trial/img42_63520.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '125,124', 4, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(43, 12, 'Очки для плавания', '<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>', '<p class="pp">Вид застежки: Карабин</p>\r\n<p class="pp">Вес товара с упаковкой (г): 100 г</p>\r\n<p class="pp">Высота предмета: 4.5 см</p>\r\n<p class="pp">Ширина предмета: 17 см</p>\r\n<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>', 1200, 1500, '0', '1', '1', '9900007', '0', '38,35,34', '', 0x4e3b, '1', 0, '1', '', '0', 1522080010, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img43_25431s.jpg', '/UserFiles/Image/Trial/img43_25431.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '123,122', 5, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL);
INSERT INTO `phpshop_products` (`id`, `category`, `name`, `description`, `content`, `price`, `price_n`, `sklad`, `p_enabled`, `enabled`, `uid`, `spec`, `odnotip`, `vendor`, `vendor_array`, `yml`, `num`, `newtip`, `title`, `title_enabled`, `datas`, `page`, `user`, `descrip`, `descrip_enabled`, `title_shablon`, `descrip_shablon`, `keywords`, `keywords_enabled`, `keywords_shablon`, `pic_small`, `pic_big`, `yml_bid_array`, `parent_enabled`, `parent`, `items`, `weight`, `price2`, `price3`, `price4`, `price5`, `files`, `baseinputvaluta`, `ed_izm`, `dop_cat`, `rate`, `rate_count`, `prod_seo_name`, `price_search`, `parent2`, `color`) VALUES
(44, 10, 'Протеин Dynamic Whey', '<p class="pp">Форма выпуска: порошок</p>\r\n<p class="pp">Тип протеина: Сывороточный</p>\r\n<p class="pp">Пищевая ценность углеводы: 4.3 г/100г</p>\r\n<p class="pp">Пищевая ценность белки: 75 г/100г</p>\r\n<p class="pp">Пищевая ценность жиры: 5.7 г/100г</p>', '<p class="pp">Форма выпуска: порошок</p>\r\n<p class="pp">Тип протеина: Сывороточный</p>\r\n<p class="pp">Пищевая ценность углеводы: 4.3 г/100г</p>\r\n<p class="pp">Пищевая ценность белки: 75 г/100г</p>\r\n<p class="pp">Пищевая ценность жиры: 5.7 г/100г</p>', 2500, 0, '0', '1', '1', '', '0', '56,53,52', '', 0x4e3b, '1', 0, '1', '', '0', 1522080143, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img44_66993s.jpg', '/UserFiles/Image/Trial/img44_66993.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 12, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(45, 10, 'Sportein Enriched PROTEIN', '<p class="pp">Тип протеина: Сывороточный</p>\r\n', '<p class="pp">Тип протеина: Сывороточный</p>\r\n<p class="pp">Пищевая ценность углеводы: 15 г/100г</p>\r\n<p class="pp">Пищевая ценность белки: 73.3 г/100г</p>\r\n<p class="pp">Пищевая ценность жиры: 0.4 г/100г</p>', 4500, 0, '0', '1', '1', '97878686', '0', '56,53,52', '', 0x4e3b, '1', 0, '1', '', '0', 1522080135, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img45_70583s.jpg', '/UserFiles/Image/Trial/img45_70583.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 6, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(46, 10, 'Протеин Whey Protein, шоколад', '<p>Состав: загуститель,витаминный комплекс,ароматизатор идентичный натуральному,концентрат сывороточного.</p>', '<p>Состав: загуститель,витаминный комплекс,ароматизатор идентичный натуральному,концентрат сывороточного.</p>', 5600, 0, '0', '1', '1', '087087', '0', '56,53,52', '', 0x4e3b, '1', 0, '1', '', '0', 1522080165, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img46_42405s.jpg', '/UserFiles/Image/Trial/img46_42405.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 1, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(47, 10, 'Enriched PROTEIN', '<p class="pp">Тип протеина: Сывороточный</p>\r\n', '<p class="pp">Тип протеина: Сывороточный</p>\r\n<p class="pp">Пищевая ценность углеводы: 15 г/100г</p>\r\n<p class="pp">Пищевая ценность белки: 73.3 г/100г</p>\r\n<p class="pp">Пищевая ценность жиры: 0.4 г/100г</p>', 4500, 0, '0', '1', '1', '97878686', '0', '56,53,52', '', 0x4e3b, '1', 0, '1', '', '0', 1522080118, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img47_27834s.jpg', '/UserFiles/Image/Trial/img47_27834.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 6, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(48, 10, 'Enriched PROTEIN', '<p class="pp">Тип протеина: Сывороточный</p>\r\n', '<p class="pp">Тип протеина: Сывороточный</p>\r\n<p class="pp">Пищевая ценность углеводы: 15 г/100г</p>\r\n<p class="pp">Пищевая ценность белки: 73.3 г/100г</p>\r\n<p class="pp">Пищевая ценность жиры: 0.4 г/100г</p>', 4500, 0, '0', '1', '1', '97878686', '0', '56,53,52', '', 0x4e3b, '1', 0, '1', '', '0', 1522080128, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img45_47678s.jpg', '/UserFiles/Image/Trial/img45_47678.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 6, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(49, 10, 'Протеин Whey Protein', '<p>Состав: загуститель,витаминный комплекс,ароматизатор идентичный натуральному,концентрат сывороточного.</p>', '<p>Состав: загуститель,витаминный комплекс,ароматизатор идентичный натуральному,концентрат сывороточного.</p>', 5600, 0, '0', '1', '1', '087087', '0', '56,53,52', '', 0x4e3b, '1', 0, '1', '', '0', 1522080156, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img49_10863s.jpg', '/UserFiles/Image/Trial/img49_10863.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 1, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(50, 12, 'Маска для плавания', '<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>', '<p class="pp">Вид застежки: Карабин</p>\r\n<p class="pp">Вес товара с упаковкой (г): 100 г</p>\r\n<p class="pp">Высота предмета: 4.5 см</p>\r\n<p class="pp">Ширина предмета: 17 см</p>\r\n<p class="pp">Страна бренда: Россия</p>\r\n<p class="pp">Страна производитель: Китай</p>', 1200, 1500, '0', '1', '1', '9900007', '0', '41,50,43', '', 0x4e3b, '1', 0, '1', '', '0', 1522080000, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img50_42880s.jpg', '/UserFiles/Image/Trial/img50_42880.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '121,120', 5, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(51, 11, 'Кроссовки женские CROCS', '<p class="pp color colorpicker-element">Цвет: <span class="color j-color-name colorpicker-element">белый</span></p>\r\n<p class="pp composition">Состав: искусственная кожа 100%</p>', '<p class="pp color colorpicker-element">Цвет: <span class="color j-color-name colorpicker-element">белый</span></p>\r\n<p class="pp composition">Состав: искусственная кожа 100%</p>', 3400, 0, '0', '1', '1', '08708707', '0', '41,50,43', 'i6-12i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223132223b7d7d, '1', 0, '1', '', '0', 1522080100, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img51_77278s.jpg', '/UserFiles/Image/Trial/img51_77278.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '133,132', 17, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(52, 11, 'Кроссовки ASICS', '<p class="pp color colorpicker-element">Цвет: <span class="color j-color-name colorpicker-element">черный, красный, белый</span></p>\r\n<p class="pp composition">Состав: текстиль,синтетический материал</p>', '<p class="pp color colorpicker-element">Цвет: <span class="color j-color-name colorpicker-element">черный, красный, белый</span></p>\r\n<p class="pp composition">Состав: текстиль,синтетический материал</p>', 5100, 0, '0', '1', '1', '7777443', '0', '41,50,43', 'i6-11i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223131223b7d7d, '1', 0, '1', '', '0', 1522080078, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img52_39446s.jpg', '/UserFiles/Image/Trial/img52_39446.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '131', 23, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(53, 11, 'Кроссовки 574 Classic', '<p>Состав: натуральная кожа 75%,нейлон 19%,полиуретан 6%</p>', '<p>Состав: натуральная кожа 75%,нейлон 19%,полиуретан 6%</p>', 5700, 0, '0', '1', '1', '354456', '0', '45,44,46', 'i6-11i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223131223b7d7d, '1', 0, '1', '', '0', 1522080047, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img53_12129s.jpg', '/UserFiles/Image/Trial/img53_12129.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '129,128', 12, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(54, 11, 'Кроссовки Finn-flare', '<p>Состав: натуральная замша,текстиль,резина</p>', '<p>Состав: натуральная замша,текстиль,резина</p>', 6700, 0, '0', '1', '1', '89769876', '0', '41,50,43', 'i6-13i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223133223b7d7d, '1', 0, '1', '', '0', 1522080085, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img54_56298s.jpg', '/UserFiles/Image/Trial/img54_56298.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '136,135,134', 5, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(55, 11, 'Кроссовки Mango', '<p>Состав: синтетический материал 60%,натуральная замша 40%</p>', '<p>Состав: синтетический материал 60%,натуральная замша 40%</p>', 11000, 0, '0', '1', '1', '0987098', '0', '41,50,43', 'i6-14i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223134223b7d7d, '1', 0, '1', '', '0', 1522080093, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img55_13369s.jpg', '/UserFiles/Image/Trial/img55_13369.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '139,138,137', 12, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(56, 11, 'Кроссовки 574', '<p>Состав: натуральная кожа 75%,нейлон 19%,полиуретан 6%</p>', '<p>Состав: натуральная кожа 75%,нейлон 19%,полиуретан 6%</p>', 14000, 0, '0', '1', '1', '23452456', '0', '41,50,43', 'i6-11i', 0x613a313a7b693a363b613a313a7b693a303b733a323a223131223b7d7d, '1', 0, '1', '', '0', 1522080069, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img56_67285s.jpg', '/UserFiles/Image/Trial/img56_67285.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', '130', 0, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(57, 5, 'Кровать Лидс', '<p class="pp composition">Состав: полиэстер 100%</p>', '<p>Состав: полиэстер 100%</p>', 90000, 0, '0', '1', '1', '', '0', '58,57,59', 'i15-36ii15-38ii14-35ii14-33ii14-34i', 0x613a323a7b693a31353b613a323a7b693a303b733a323a223336223b693a313b733a323a223338223b7d693a31343b613a333a7b693a303b733a323a223335223b693a313b733a323a223333223b693a323b733a323a223334223b7d7d, '1', 0, '1', '', '0', 1522080209, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img57_34525s.jpg', '/UserFiles/Image/Trial/img57_34525.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 3, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(58, 5, 'Кресло', '<p>Состав: полиэстер 100%</p>', '<p>Состав: полиэстер 100%</p>', 67000, 0, '0', '1', '1', '09870987', '0', '56,57,58', 'i15-36ii14-35ii14-33i', 0x613a323a7b693a31353b613a313a7b693a303b733a323a223336223b7d693a31343b613a323a7b693a303b733a323a223335223b693a313b733a323a223333223b7d7d, '1', 0, '1', '', '0', 1522080223, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img58_79133s.jpg', '/UserFiles/Image/Trial/img58_79133.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 12, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(59, 5, 'Лампа', '<p>Состав: полиэстер 100%</p>', '<p>Состав: полиэстер 100%</p>', 2300, 0, '0', '1', '1', '9787765', '0', '57,58,59', 'i15-36ii15-38ii15-37i', 0x613a313a7b693a31353b613a333a7b693a303b733a323a223336223b693a313b733a323a223338223b693a323b733a323a223337223b7d7d, '1', 0, '1', '', '0', 1522080216, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img59_61956s.jpg', '/UserFiles/Image/Trial/img59_61956.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 0, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(60, 5, 'Часы', '<p>Состав: полиэстер 100%</p>', '<p>Состав: полиэстер 100%</p>', 9000, 0, '0', '1', '1', '09898798', '0', '57,58,59', 'i15-38ii14-33i', 0x613a323a7b693a31353b613a313a7b693a303b733a323a223338223b7d693a31343b613a313a7b693a303b733a323a223333223b7d7d, '1', 0, '1', '', '0', 1522080231, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img60_27601s.jpg', '/UserFiles/Image/Trial/img60_27601.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 5, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 5, 1, '', 0, NULL, NULL),
(61, 5, 'Стул', '<p>Состав: полиэстер 100%</p>', '<div class="product-info-box">\n<div class="content panel-smart">\n<p>Состав: полиэстер 100%</p>\n</div>\n</div>\n<div class="product-info-box empty-check"> </div>', 24000, 0, '0', '1', '1', '0980987', '0', '57,58,59', 'i15-38ii15-38ii14-33ii14-33i', 0x613a323a7b693a31353b613a323a7b693a303b733a323a223338223b693a313b733a323a223338223b7d693a31343b613a323a7b693a303b733a323a223333223b693a313b733a323a223333223b7d7d, '1', 0, '1', '', '0', 1523371703, 'null', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img61_26143s.jpg', '/UserFiles/Image/Trial/img61_26143.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 13, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 4, 1, 'stul', 0, NULL, NULL),
(62, 5, 'Ваза', '<p>Состав: полиэстер 100%</p>', '<p>Состав: полиэстер 100%</p>', 7000, 0, '0', '1', '1', '9080987', '0', '57,58,59', 'i15-36ii14-35i', 0x613a323a7b693a31353b613a313a7b693a303b733a323a223336223b7d693a31343b613a313a7b693a303b733a323a223335223b7d7d, '1', 0, '1', '', '0', 1522080248, '', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img62_12965s.jpg', '/UserFiles/Image/Trial/img62_12965.jpg', 0x613a323a7b733a333a22626964223b733a303a22223b733a343a2263626964223b733a303a22223b7d, '0', NULL, 14, 0, 0, 0, 0, 0, 'N;', 6, 'шт.', '', 0, 0, '', 0, NULL, NULL),
(63, 1, 'Шорты 42 белый', NULL, NULL, 7800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073663, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(64, 1, 'Шорты 44 черный', NULL, NULL, 7900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073661, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(65, 1, 'Шорты 46 белый', NULL, NULL, 7900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073659, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(66, 1, 'Шорты 48 белый', NULL, NULL, 7900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073658, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(67, 1, 'Комбинезон 42 красный', NULL, NULL, 11000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073635, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'красный', '#FF0000'),
(68, 1, 'Комбинезон 44 красный', NULL, NULL, 11000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073632, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'красный', '#FF0000'),
(69, 1, 'Комбинезон 42 белый', NULL, NULL, 11000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073629, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(70, 1, 'Жакет из легкого струящегося материала S красный', NULL, NULL, 5600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073679, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'красный', '#FF0000'),
(71, 1, 'Жакет из легкого струящегося материала S белый', NULL, NULL, 5600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073685, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(72, 1, 'Жакет из легкого струящегося материала M ', NULL, NULL, 5600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073696, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(73, 1, 'Жакет из легкого струящегося материала L коричневый', NULL, NULL, 5600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073704, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'коричневый', '#A0522D'),
(74, 1, 'Платье из мягкого материала XS бежевый', NULL, NULL, 9900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073743, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'XS', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'бежевый', '#C0C0C0'),
(75, 1, 'Платье из мягкого материала S белый', NULL, NULL, 9900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073749, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(76, 1, 'Платье из мягкого материала M белый', NULL, NULL, 9900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073755, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(77, 1, 'Платье из мягкого материала M черный', NULL, NULL, 9900, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522073760, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(78, 1, 'Платье черное XS белый', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077728, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'XS', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(79, 1, 'Платье черное S белый', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077735, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(80, 1, 'Платье черное M белый', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077742, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(81, 1, 'Платье черное L черный', NULL, NULL, 4500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077748, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(82, 1, 'Жакет серый S коричневый', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077762, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'коричневый', '#A0522D'),
(83, 1, 'Жакет серый M серый', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077767, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'серый', '#808080'),
(84, 1, 'Жакет серый L серый', NULL, NULL, 800, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077774, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'серый', '#808080'),
(85, 1, 'Платье Mango S бежевый', NULL, NULL, 3400, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077787, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'бежевый', '#C0C0C0'),
(86, 1, 'Платье Mango S желтый', NULL, NULL, 3400, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077792, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'желтый', '#FFFF00'),
(87, 1, 'Платье Mango M бежевый', NULL, NULL, 3400, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077801, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'M', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'бежевый', '#C0C0C0'),
(88, 1, 'Платье Mango L синий', NULL, NULL, 3400, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077808, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'L', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'синий', '#0000FF'),
(89, 1, 'Платье из плотного материала S белый', NULL, NULL, 5000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077819, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(90, 1, 'Платье из плотного материала S красный', NULL, NULL, 5000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077826, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'S', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'красный', '#FF0000'),
(91, 1, 'Платье из плотного материала  коричневый', NULL, NULL, 5000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522077835, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'коричневый', '#A0522D'),
(92, 4, 'Очки  белый', NULL, NULL, 15000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078336, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(93, 4, 'Очки  синий', NULL, NULL, 15000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078340, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'синий', '#0000FF'),
(94, 4, 'Кроссовки спортивные 37 белый', NULL, NULL, 13000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078369, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '37', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(95, 4, 'Кроссовки спортивные 42 желтый', NULL, NULL, 13000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078376, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '42', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'желтый', '#FFFF00'),
(96, 4, 'Сумка женская кожаная  бежевый', NULL, NULL, 15500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078398, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'бежевый', '#C0C0C0'),
(97, 4, 'Сумка женская кожаная  оранжевый', NULL, NULL, 15500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078402, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'оранжевый', '#FFA500'),
(98, 4, 'Кроссовки спортивные Boss 38 белый', NULL, NULL, 16000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078425, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(99, 4, 'Кроссовки спортивные Boss 39 зеленый', NULL, NULL, 16000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078432, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'зеленый', '#008000'),
(100, 4, 'Ремень  черный', NULL, NULL, 6000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078444, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(101, 4, 'Ремень  серый', NULL, NULL, 6000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078447, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'серый', '#808080'),
(102, 4, 'Ботинки мужские 38 черный', NULL, NULL, 12000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078463, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(103, 4, 'Ботинки мужские 38 белый', NULL, NULL, 12000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078469, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(104, 4, 'Ботинки мужские 42 белый', NULL, NULL, 12000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078474, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '42', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(105, 6, ' Vivo V5 металлик бежевый', NULL, NULL, 35000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078518, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'металлик', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'бежевый', '#C0C0C0'),
(106, 6, ' Vivo V5 красный черный', NULL, NULL, 35000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078526, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'красный', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(107, 6, ' Lenovo IdeaPad 110 серый ', NULL, NULL, 64000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078554, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'серый', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(108, 6, ' Lenovo IdeaPad 110 зеленый ', NULL, NULL, 64000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078558, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'зеленый', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(109, 6, 'Samsung голубой ', NULL, NULL, 60000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078570, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'голубой', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(110, 6, 'Samsung зеленый ', NULL, NULL, 60000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078573, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'зеленый', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(111, 6, 'Samsung Gear VR белый ', NULL, NULL, 67000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078587, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'белый', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(112, 6, 'Samsung Gear VR черный ', NULL, NULL, 67000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078592, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'черный', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(113, 6, 'HP ProBook 450 серый ', NULL, NULL, 125000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078606, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'серый', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(114, 6, 'HP ProBook 450 коричневый ', NULL, NULL, 125000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078611, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'коричневый', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(115, 6, 'Телефон Apple белый ', NULL, NULL, 46000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078618, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', 'белый', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(116, 12, 'Беговая дорожка 1200 см ', NULL, NULL, 45000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078680, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '1200 см', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(118, 12, 'Зашита на колено  черный', NULL, NULL, 600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078694, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(117, 12, 'Беговая дорожка 1400 см ', NULL, NULL, 45000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078684, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '1400 см', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(120, 12, 'Маска для плавания  прозрачная', NULL, NULL, 1200, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078721, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'прозрачный', '#C0C0C0'),
(119, 12, 'Зашита на колено  белый', NULL, NULL, 600, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078697, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(121, 12, 'Маска для плавания  красный', NULL, NULL, 1200, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078727, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'красный', '#FF0000'),
(122, 12, 'Очки для плавания  зеленый', NULL, NULL, 1200, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078752, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'зеленый', '#008000'),
(124, 12, 'Сумка спортивная 3 л ', NULL, NULL, 3500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078777, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '3 л', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(123, 12, 'Очки для плавания  голубой', NULL, NULL, 1200, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078757, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'голубой', '#00FFFF'),
(126, 12, 'Шлем  черный', NULL, NULL, 5000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078789, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(125, 12, 'Сумка спортивная 4 л  ', NULL, NULL, 3500, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078781, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '4 л ', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(127, 12, 'Шлем  белый', NULL, NULL, 5000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078792, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(128, 11, 'Кроссовки 574 Classic 42 белый', NULL, NULL, 5700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078840, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '42', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'белый', '#ffffff'),
(129, 11, 'Кроссовки 574 Classic 44 синий', NULL, NULL, 5700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078845, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '44', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'синий', '#0000FF'),
(130, 11, 'Кроссовки 574 Classic 44 серый', NULL, NULL, 14000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078887, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '44', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'серый', '#808080'),
(131, 11, 'Кроссовки ASICS 44 синий', NULL, NULL, 5100, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078904, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '44', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'синий', '#0000FF'),
(132, 11, 'Кроссовки женские CROCS 38 зеленый', NULL, NULL, 3400, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078946, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'зеленый', '#008000'),
(133, 11, 'Кроссовки женские CROCS 41 синий', NULL, NULL, 3400, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522078952, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '41', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'синий', '#0000FF'),
(134, 11, 'Кроссовки Finn-flare 40 черный', NULL, NULL, 6700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522079006, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '40', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, 'черный', '#000000'),
(135, 11, 'Кроссовки Finn-flare 42 ', NULL, NULL, 6700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522079012, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '42', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(136, 11, 'Кроссовки Finn-flare 44 ', NULL, NULL, 6700, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522079015, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '44', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(137, 11, 'Кроссовки Mango 38 ', NULL, NULL, 11000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522079049, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '38', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(138, 11, 'Кроссовки Mango 39 ', NULL, NULL, 11000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522079052, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '39', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL),
(139, 11, 'Кроссовки Mango 41 ', NULL, NULL, 11000, 0, '0', '0', '1', '', '0', '', '', 0x4e3b, '0', 1, '0', '', '0', 1522079055, '', 1, '', '0', '', '', '', '0', '', '', '', 0x4e3b, '1', '41', 1, 0, 0, 0, 0, 0, 'N;', 6, '', '', 0, 0, '', 0, '', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_rating_categories`
--

CREATE TABLE `phpshop_rating_categories` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `ids_dir` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '0',
  `revoting` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_rating_categories`
--

INSERT INTO `phpshop_rating_categories` (`id_category`, `ids_dir`, `name`, `enabled`, `revoting`) VALUES
(1, ',2,,3,,4,,6,,7,,8,,10,,11,,12,', 'Товары', '1', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_rating_charact`
--

CREATE TABLE `phpshop_rating_charact` (
  `id_charact` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `num` int(11) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id_charact`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_rating_charact`
--

INSERT INTO `phpshop_rating_charact` (`id_charact`, `id_category`, `name`, `num`, `enabled`) VALUES
(1, 1, 'Внешний вид', 1, '1'),
(2, 1, 'Функциональность', 2, '1'),
(3, 1, 'Качество', 3, '1');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_rating_votes`
--

CREATE TABLE `phpshop_rating_votes` (
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

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_rssgraber`
--

CREATE TABLE `phpshop_rssgraber` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link` text,
  `day_num` int(1) DEFAULT '1',
  `news_num` mediumint(8) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '1',
  `start_date` int(16) unsigned DEFAULT '0',
  `end_date` int(16) unsigned DEFAULT '0',
  `last_load` int(16) unsigned DEFAULT '0',
  `servers` varchar(64) default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_rssgraber`
--

INSERT INTO `phpshop_rssgraber` (`id`, `link`, `day_num`, `news_num`, `enabled`, `start_date`, `end_date`, `last_load`) VALUES
(1, 'http://www.phpshop.ru/rss/', 1, 10, '1', 1307995200, 1606766400, 1523394000);

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_rssgraber_jurnal`
--

CREATE TABLE `phpshop_rssgraber_jurnal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(15) unsigned DEFAULT '0',
  `link_id` int(11) DEFAULT '0',
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_search_base`
--

CREATE TABLE `phpshop_search_base` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `uid` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '1',
  `category` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_search_jurnal`
--

CREATE TABLE `phpshop_search_jurnal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `num` tinyint(32) DEFAULT '0',
  `datas` varchar(11) DEFAULT '',
  `dir` varchar(255) DEFAULT '',
  `cat` tinyint(11) DEFAULT '0',
  `set` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_servers`
--

CREATE TABLE `phpshop_servers` (
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
  `currency` int(11),
  `lang` varchar(32),
  `admoption` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_shopusers`
--

CREATE TABLE `phpshop_shopusers` (
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_shopusers`
--

INSERT INTO `phpshop_shopusers` (`id`, `login`, `password`, `datas`, `mail`, `name`, `company`, `inn`, `tel`, `adres`, `enabled`, `status`, `kpp`, `tel_code`, `wishlist`, `data_adres`, `cumulative_discount`, `sendmail`) VALUES
(17, 'test3@mail.ru', 'MTIzNDU2', '1523026100', 'pozhitok2004@mail.ru', 'Екатерина', '', '', '(888) 999-8888', '', '1', '0', '', '', 0x613a303a7b7d, 0x613a323a7b733a343a226d61696e223b693a303b733a343a226c697374223b613a313a7b693a303b613a32333a7b733a373a2266696f5f6e6577223b733a393a22c5eae0f2e5f0e8ede0223b733a373a2274656c5f6e6577223b733a383a222d30382d30383937223b733a31313a22636f756e7472795f6e6577223b733a303a22223b733a393a2273746174655f6e6577223b733a303a22223b733a383a22636974795f6e6577223b733a303a22223b733a393a22696e6465785f6e6577223b733a303a22223b733a31303a227374726565745f6e6577223b733a303a22223b733a393a22686f7573655f6e6577223b733a303a22223b733a393a22706f7263685f6e6577223b733a303a22223b733a31343a22646f6f725f70686f6e655f6e6577223b733a303a22223b733a383a22666c61745f6e6577223b733a303a22223b733a31333a2264656c697674696d655f6e6577223b733a373a222d303938303938223b733a373a2264656661756c74223b733a313a2231223b733a31323a226f72675f6e616d655f6e6577223b733a303a22223b733a31313a226f72675f696e6e5f6e6577223b733a303a22223b733a31313a226f72675f6b70705f6e6577223b733a303a22223b733a31373a226f72675f7975725f61647265735f6e6577223b733a303a22223b733a31383a226f72675f66616b745f61647265735f6e6577223b733a303a22223b733a31313a226f72675f7261735f6e6577223b733a303a22223b733a31323a226f72675f62616e6b5f6e6577223b733a303a22223b733a31313a226f72675f6b6f725f6e6577223b733a303a22223b733a31313a226f72675f62696b5f6e6577223b733a303a22223b733a31323a226f72675f636974795f6e6577223b733a303a22223b7d7d7d, 0, '1'),
(18, 'test@mail.ru', 'cG5kMTFrN3Q=', '1523370976', 'test@mail.ru', 'Юлия', '', '', '(111) 224-1241', '', '1', '0', '', '', 0x613a303a7b7d, 0x613a323a7b733a343a226c697374223b613a313a7b693a303b613a333a7b733a373a2266696f5f6e6577223b733a343a22deebe8ff223b733a373a2274656c5f6e6577223b733a31343a222831313129203232342d31323431223b733a31333a2264656c697674696d655f6e6577223b733a393a22f120313020f3f2f0e0223b7d7d733a343a226d61696e223b693a303b7d, 0, '1'),
(19, 'test2@gmail.com', 'bnl2MXJkYzg=', '1523371028', 'test2@gmail.com', 'Руслан', '', '', '(888) 888-8888', '', '1', '0', '', '', 0x613a303a7b7d, 0x613a323a7b733a343a226c697374223b613a313a7b693a303b613a333a7b733a373a2266696f5f6e6577223b733a363a22d0f3f1ebe0ed223b733a373a2274656c5f6e6577223b733a31343a222838383829203838382d38383838223b733a31333a2264656c697674696d655f6e6577223b733a31313a22f120313720e2e5f7e5f0e0223b7d7d733a343a226d61696e223b693a303b7d, 0, '1');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_shopusers_status`
--

CREATE TABLE `phpshop_shopusers_status` (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `discount` float DEFAULT '0',
  `price` enum('1','2','3','4','5') DEFAULT '1',
  `enabled` enum('0','1') DEFAULT '1',
  `cumulative_discount_check` enum('0','1') DEFAULT '0',
  `cumulative_discount` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_shopusers_status`
--

INSERT INTO `phpshop_shopusers_status` (`id`, `name`, `discount`, `price`, `enabled`, `cumulative_discount_check`, `cumulative_discount`) VALUES
(1, 'Оптовик', 5, '2', '1', '0', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_slider`
--

CREATE TABLE `phpshop_slider` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '0',
  `num` smallint(6) DEFAULT '0',
  `link` varchar(255) DEFAULT '',
  `alt` varchar(255) DEFAULT '',
  `servers` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_slider`
--

INSERT INTO `phpshop_slider` (`id`, `image`, `enabled`, `num`, `link`, `alt`, `servers`) VALUES
(12, '/UserFiles/Image/Trial/slider/slider-3.jpg', '1', 0, '', '', ''),
(9, '/UserFiles/Image/Trial/slider/slider-4.jpg', '1', 2, '', '', ''),
(14, '/UserFiles/Image/Trial/slider/slider-2.jpg', '1', 4, '', '', ''),
(13, '/UserFiles/Image/Trial/slider/slider-1.jpg', '1', 0, '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_sort`
--

CREATE TABLE `phpshop_sort` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `category` int(11) unsigned DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `page` varchar(255) DEFAULT '',
  `icon` varchar(255) DEFAULT '',
  `sort_seo_name` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_sort`
--

INSERT INTO `phpshop_sort` (`id`, `name`, `category`, `num`, `page`, `icon`, `sort_seo_name`) VALUES
(1, 'Loreal', 1, 1, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/loreal-paris.png', 'loreal'),
(2, 'Max-Factor', 1, 2, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/max-factor.png', 'max-factor'),
(3, 'Maybelline', 1, 3, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/maybelline-new-york.png', 'maybelline'),
(4, 'Schwarzkopf', 0, 4, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/schwarzkopf-professional.png', '-99'),
(5, 'Vichy', 0, 0, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/vichy.png', '-99'),
(6, 'Schwarzkopf', 1, 4, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/schwarzkopf-professional.png', 'schwarzkopf'),
(7, 'Vichy', 1, 5, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/vichy.png', 'vichy'),
(8, 'Смешанная', 3, 1, '', '', ''),
(9, 'Сухая', 3, 2, '', '', ''),
(10, 'Проблемная', 3, 3, '', '', ''),
(11, 'Asics', 6, 1, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/asics.png', 'asics'),
(12, 'Crocs', 6, 2, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/crocs.png', 'crocs'),
(13, 'Finn-flare', 6, 0, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/finn-flare.png', 'finn-flare'),
(14, 'Mango', 6, 0, 'stranica_opisaniya_brenda', '/UserFiles/Image/Trial/brand/mango.png', 'mango'),
(15, '40', 7, 1, '', '', ''),
(16, '42', 7, 2, '', '', ''),
(17, '44', 7, 3, '', '', ''),
(18, '46', 7, 4, '', '', ''),
(19, '48', 7, 5, '', '', ''),
(20, '50', 7, 6, '', '', ''),
(21, '52', 7, 7, '', '', ''),
(22, 'Белый', 8, 1, '', '', ''),
(23, 'Черный', 8, 2, '', '', ''),
(24, 'Красный', 8, 3, '', '', ''),
(25, 'Синий', 8, 4, '', '', ''),
(26, 'Бежевый', 8, 5, '', '', ''),
(27, '64 мб', 12, 1, '', '', ''),
(28, '128 мб', 12, 2, '', '', ''),
(29, '264 мб', 12, 3, '', '', ''),
(30, 'белый', 13, 1, '', '', ''),
(31, 'металлик', 13, 2, '', '', ''),
(32, 'серый', 13, 3, '', '', ''),
(33, '2000*1500', 14, 1, '', '', ''),
(34, '2500*1850', 14, 2, '', '', ''),
(35, '1500*300', 14, 3, '', '', ''),
(36, 'белый', 15, 1, '', '', ''),
(37, 'кожа', 15, 2, '', '', ''),
(38, 'желтый', 15, 3, '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_sort_categories`
--

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
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_sort_categories`
--

INSERT INTO `phpshop_sort_categories` (`id`, `name`, `num`, `category`, `filtr`, `description`, `goodoption`, `optionname`, `page`, `brand`, `product`) VALUES
(1, 'Косметический бренд', 1, 2, '1', '', '0', '0', '', '1', '0'),
(2, 'Косметика', 0, 0, '0', '', '0', '0', '', '0', '0'),
(3, 'Тип кожи', 2, 2, '1', '', '1', '0', '', '0', '0'),
(4, 'Одежда, обувь', 2, 0, '0', '', '0', '0', '', '0', '0'),
(6, 'Торговая марка', 1, 4, '1', '', '0', '0', '', '1', '0'),
(7, 'Размер', 2, 4, '1', '', '1', '0', '', '0', '0'),
(8, 'Цвет', 3, 4, '1', '', '1', '0', '', '0', '0'),
(9, 'Мебель', 0, 0, '0', '', '0', '0', '', '0', '0'),
(11, 'High-tech', 0, 0, '0', '', '0', '0', '', '0', '0'),
(12, 'Встроенная память', 1, 11, '1', '', '1', '0', '', '0', '0'),
(13, 'Цвет корпуса', 0, 11, '1', '', '1', '0', '', '0', '0'),
(14, 'Габариты', 1, 9, '1', '', '1', '0', '', '0', '0'),
(15, 'Цвет', 0, 9, '1', '', '1', '0', '', '0', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_system`
--

CREATE TABLE `phpshop_system` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
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
  `icon` varchar(255) default '',
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
  `promotext` text,
  `title_shablon3` varchar(255) DEFAULT '',
  `descrip_shablon3` varchar(255) DEFAULT '',
  `keywords_shablon3` varchar(255) DEFAULT '',
  `rss_use` int(1) unsigned DEFAULT '1',
  `1c_load_accounts` enum('0','1') DEFAULT '1',
  `1c_load_invoice` enum('0','1') DEFAULT '1',
  `1c_option` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_system`
--

INSERT INTO `phpshop_system` (`id`, `name`, `company`, `num_row`, `num_row_adm`, `dengi`, `percent`, `skin`, `adminmail2`, `title`, `keywords`, `kurs`, `spec_num`, `new_num`, `tel`, `bank`, `num_vitrina`, `icon`, `updateU`, `nds`, `nds_enabled`, `admoption`, `kurs_beznal`, `descrip`, `descrip_shablon`, `title_shablon`, `keywords_shablon`, `title_shablon2`, `descrip_shablon2`, `keywords_shablon2`, `logo`, `promotext`, `title_shablon3`, `descrip_shablon3`, `keywords_shablon3`, `rss_use`, `1c_load_accounts`, `1c_load_invoice`, `1c_option`) VALUES
(1, 'Название интернет-магазина', 'Продавец', 9, 0, 6, '0', 'hub', 'admin@localhost', 'Демо-версия скрипта интернет-магазина PHPShop', 'скрипт магазина, купить интернет-магазин', 6, 8, 8, '(495)111-22-33', 0x613a31323a7b733a383a226f72675f6e616d65223b733a31343a22cecece2022cff0eee4e0e2e5f622223b733a31323a226f72675f75725f6164726573223b733a34313a2230303030303020e32e20cceef1eae2e02c20f3eb2e20def0e8e4e8f7e5f1eae0ff2c20e4eeec20312e223b733a393a226f72675f6164726573223b733a33303a22cceef1eae2e02c20f3eb2e20d4e8e7e8f7e5f1eae0ff2c20e4eeec20312e223b733a373a226f72675f696e6e223b733a393a22373737373737373737223b733a373a226f72675f6b7070223b733a31303a2238383838383838383838223b733a393a226f72675f7363686574223b733a31363a2231313131313131313131313131313131223b733a383a226f72675f62616e6b223b733a32333a22cec0ce2022c2e0f820f2e5f1f2eee2fbe920e1e0edea22223b733a373a226f72675f626963223b733a383a223436373738383838223b733a31343a226f72675f62616e6b5f7363686574223b733a31353a22323232323232323232323232323232223b733a393a226f72675f7374616d70223b733a33323a222f5573657246696c65732f496d6167652f547269616c2f7374616d702e706e67223b733a373a226f72675f736967223b733a33363a222f5573657246696c65732f496d6167652f547269616c2f66616373696d696c652e706e67223b733a31313a226f72675f7369675f627568223b733a33363a222f5573657246696c65732f496d6167652f547269616c2f66616373696d696c652e706e67223b7d, '3', '', '1409661405', '20', '1', 0x613a37383a7b733a31323a22736b6c61645f737461747573223b733a313a2231223b733a31333a22636c6f75645f656e61626c6564223b693a303b733a32333a226469676974616c5f70726f647563745f656e61626c6564223b693a303b733a31333a22757365725f63616c656e646172223b693a303b733a31393a22757365725f70726963655f6163746976617465223b693a303b733a32323a22757365725f6d61696c5f61637469766174655f707265223b693a303b733a31383a227273735f6772616265725f656e61626c6564223b733a313a2231223b733a31373a22696d6167655f736176655f736f75726365223b733a313a2231223b733a363a22696d675f776d223b4e3b733a353a22696d675f77223b733a333a22333530223b733a353a22696d675f68223b733a333a22333530223b733a363a22696d675f7477223b733a333a22323530223b733a363a22696d675f7468223b733a333a22323530223b733a31343a2277696474685f706f64726f626e6f223b733a333a22313030223b733a31323a2277696474685f6b7261746b6f223b733a333a22313030223b733a31323a22626173655f656e61626c6564223b4e3b733a31313a22736d735f656e61626c6564223b693a303b733a31343a226e6f746963655f656e61626c6564223b693a303b733a373a22626173655f6964223b733a303a22223b733a393a22626173655f686f7374223b733a303a22223b733a31333a22736b6c61645f656e61626c6564223b733a313a2231223b733a31303a2270726963655f7a6e616b223b733a313a2230223b733a31383a22757365725f6d61696c5f6163746976617465223b693a303b733a31313a22757365725f737461747573223b733a313a2230223b733a393a22757365725f736b696e223b733a313a2231223b733a31323a22636172745f6d696e696d756d223b733a303a22223b733a31333a2277617465726d61726b5f626967223b613a32313a7b733a31343a226269675f6d657267654c6576656c223b693a37303b733a31313a226269675f656e61626c6564223b733a313a2231223b733a383a226269675f74797065223b733a333a22706e67223b733a31323a226269675f706e675f66696c65223b733a33303a222f5573657246696c65732f496d6167652f73686f705f6c6f676f2e706e67223b733a31323a226269675f636f7079466c6167223b733a313a2230223b733a363a226269675f736d223b693a303b733a31363a226269675f706f736974696f6e466c6167223b733a313a2234223b733a31333a226269675f706f736974696f6e58223b693a303b733a31333a226269675f706f736974696f6e59223b693a303b733a393a226269675f616c706861223b693a37303b733a383a226269675f74657874223b733a303a22223b733a32313a226269675f746578745f706f736974696f6e466c6167223b693a303b733a383a226269675f73697a65223b693a303b733a393a226269675f616e676c65223b693a303b733a31383a226269675f746578745f706f736974696f6e58223b693a303b733a31383a226269675f746578745f706f736974696f6e59223b693a303b733a31303a226269675f636f6c6f7252223b693a303b733a31303a226269675f636f6c6f7247223b693a303b733a31303a226269675f636f6c6f7242223b693a303b733a31343a226269675f746578745f616c706861223b693a303b733a383a226269675f666f6e74223b733a31363a226e6f726f626f745f666f6e742e747466223b7d733a31353a2277617465726d61726b5f736d616c6c223b613a32313a7b733a31363a22736d616c6c5f6d657267654c6576656c223b693a3130303b733a31333a22736d616c6c5f656e61626c6564223b733a313a2231223b733a31303a22736d616c6c5f74797065223b733a333a22706e67223b733a31343a22736d616c6c5f706e675f66696c65223b733a32353a222f5573657246696c65732f496d6167652f6c6f676f2e706e67223b733a31343a22736d616c6c5f636f7079466c6167223b733a313a2230223b733a383a22736d616c6c5f736d223b693a303b733a31383a22736d616c6c5f706f736974696f6e466c6167223b733a313a2231223b733a31353a22736d616c6c5f706f736974696f6e58223b693a303b733a31353a22736d616c6c5f706f736974696f6e59223b693a303b733a31313a22736d616c6c5f616c706861223b693a35303b733a31303a22736d616c6c5f74657874223b733a303a22223b733a32333a22736d616c6c5f746578745f706f736974696f6e466c6167223b693a303b733a31303a22736d616c6c5f73697a65223b693a303b733a31313a22736d616c6c5f616e676c65223b693a303b733a32303a22736d616c6c5f746578745f706f736974696f6e58223b693a303b733a32303a22736d616c6c5f746578745f706f736974696f6e59223b693a303b733a31323a22736d616c6c5f636f6c6f7252223b693a303b733a31323a22736d616c6c5f636f6c6f7247223b693a303b733a31323a22736d616c6c5f636f6c6f7242223b693a303b733a31363a22736d616c6c5f746578745f616c706861223b693a303b733a31303a22736d616c6c5f666f6e74223b733a31363a226e6f726f626f745f666f6e742e747466223b7d733a31353a2277617465726d61726b5f6973686f64223b613a32313a7b733a31363a226973686f645f6d657267654c6576656c223b693a3130303b733a31333a226973686f645f656e61626c6564223b4e3b733a31303a226973686f645f74797065223b733a333a22706e67223b733a31343a226973686f645f706e675f66696c65223b733a303a22223b733a31343a226973686f645f636f7079466c6167223b733a313a2230223b733a383a226973686f645f736d223b693a303b733a31383a226973686f645f706f736974696f6e466c6167223b733a313a2231223b733a31353a226973686f645f706f736974696f6e58223b693a303b733a31353a226973686f645f706f736974696f6e59223b693a303b733a31313a226973686f645f616c706861223b693a303b733a31303a226973686f645f74657874223b733a303a22223b733a32333a226973686f645f746578745f706f736974696f6e466c6167223b693a303b733a31303a226973686f645f73697a65223b693a303b733a31313a226973686f645f616e676c65223b693a303b733a32303a226973686f645f746578745f706f736974696f6e58223b693a303b733a32303a226973686f645f746578745f706f736974696f6e59223b693a303b733a31323a226973686f645f636f6c6f7252223b693a303b733a31323a226973686f645f636f6c6f7247223b693a303b733a31323a226973686f645f636f6c6f7242223b693a303b733a31363a226973686f645f746578745f616c706861223b693a303b733a31303a226973686f645f666f6e74223b733a31363a226e6f726f626f745f666f6e742e747466223b7d733a31343a226e6f776275795f656e61626c6564223b733a313a2232223b733a363a22656469746f72223b733a373a2274696e796d6365223b733a353a227468656d65223b733a373a2264656661756c74223b733a32343a22736d735f7374617475735f6f726465725f656e61626c6564223b693a303b733a31373a226d61696c5f736d74705f7265706c79746f223b733a303a22223b733a393a22736d735f70686f6e65223b733a303a22223b733a383a22736d735f75736572223b733a303a22223b733a383a22736d735f70617373223b733a303a22223b733a383a22736d735f6e616d65223b733a303a22223b733a393a226163655f7468656d65223b733a343a226461776e223b733a393a2261646d5f7469746c65223b733a303a22223b733a31343a227365617263685f656e61626c6564223b733a313a2233223b733a31343a226d61696c5f736d74705f686f7374223b733a303a22223b733a31343a226d61696c5f736d74705f706f7274223b733a303a22223b733a31343a226d61696c5f736d74705f75736572223b733a303a22223b733a31343a226d61696c5f736d74705f70617373223b733a303a22223b733a32303a22706172656e745f70726963655f656e61626c6564223b693a303b733a31373a226d61696c5f736d74705f656e61626c6564223b693a303b733a31353a226d61696c5f736d74705f6465627567223b693a303b733a31343a226d61696c5f736d74705f61757468223b693a303b733a31323a2272756c655f656e61626c6564223b693a303b733a31353a226361746c6973745f656e61626c6564223b733a313a2231223b733a31373a227265636170746368615f656e61626c6564223b733a313a2231223b733a31343a227265636170746368615f706b6579223b733a303a22223b733a31343a227265636170746368615f736b6579223b733a303a22223b733a31343a226461646174615f656e61626c6564223b733a313a2231223b733a31323a226461646174615f746f6b656e223b733a303a22223b733a32313a226d756c74695f63757272656e63795f736561726368223b693a303b733a31373a22696d6167655f726573756c745f70617468223b733a303a22223b733a31343a2277617465726d61726b5f74657874223b733a303a22223b733a32303a2277617465726d61726b5f746578745f636f6c6f72223b733a373a2223636363636363223b733a31393a2277617465726d61726b5f746578745f73697a65223b733a323a223230223b733a31393a2277617465726d61726b5f746578745f666f6e74223b733a343a2256657261223b733a31353a2277617465726d61726b5f7269676874223b733a313a2230223b733a31363a2277617465726d61726b5f626f74746f6d223b733a313a2230223b733a32303a2277617465726d61726b5f746578745f616c706861223b733a323a223830223b733a31353a2277617465726d61726b5f696d616765223b733a303a22223b733a32313a22696d6167655f61646170746976655f726573697a65223b693a303b733a31353a22696d6167655f736176655f6e616d65223b693a303b733a32313a2277617465726d61726b5f6269675f656e61626c6564223b693a303b733a32343a2277617465726d61726b5f736f757263655f656e61626c6564223b693a303b733a31373a2279616e6465786d61705f656e61626c6564223b733a313a2231223b733a393a226875625f7468656d65223b733a32333a22626f6f7473747261702d7468656d652d64656661756c74223b733a31353a226875625f666c7569645f7468656d65223b733a32333a22626f6f7473747261702d7468656d652d64656661756c74223b733a32343a2277617465726d61726b5f63656e7465725f656e61626c6564223b693a303b733a343a226c616e67223b733a373a227275737369616e223b733a31393a2266696c7465725f63616368655f706572696f64223b733a303a22223b733a32303a2266696c7465725f63616368655f656e61626c6564223b693a303b733a32313a2266696c7465725f70726f64756374735f636f756e74223b693a303b7d, 6, 'PHPShop – это готовое решение для быстрого создания интернет-магазина.', '@Podcatalog@, @Catalog@, @System@', '@Podcatalog@ - @Catalog@ - @System@', '@Podcatalog@, @Catalog@, @Generator@', '@Product@ - @Podcatalog@ - @Catalog@', '@Product@, @Podcatalog@, @Catalog@', '@Product@,@System@', '/UserFiles/Image/Trial/logotip2018.png', '', '@Catalog@ - @System@', '@Catalog@', '@Catalog@', 0, '0', '0', 0x613a373a7b733a31313a227570646174655f6e616d65223b733a313a2231223b733a31343a227570646174655f636f6e74656e74223b733a313a2231223b733a31383a227570646174655f6465736372697074696f6e223b733a313a2231223b733a31353a227570646174655f63617465676f7279223b733a313a2231223b733a31313a227570646174655f736f7274223b733a313a2231223b733a31323a227570646174655f7072696365223b733a313a2231223b733a31313a227570646174655f6974656d223b733a313a2231223b7d);


-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_templates_key`
--

CREATE TABLE `phpshop_templates_key` (
  `path` varchar(64) NOT NULL DEFAULT '',
  `date` int(11) DEFAULT '0',
  `key` text,
  `verification` varchar(32) DEFAULT '',
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_users`
--

CREATE TABLE `phpshop_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` blob,
  `login` varchar(64) DEFAULT '',
  `password` varchar(64) DEFAULT '',
  `mail` varchar(64) DEFAULT '',
  `enabled` enum('0','1') DEFAULT '1',
  `name` varchar(255) DEFAULT '',
  `hash` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;



-- --------------------------------------------------------

--
-- Структура таблицы `phpshop_valuta`
--

CREATE TABLE `phpshop_valuta` (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `code` varchar(64) DEFAULT '',
  `iso` varchar(64) DEFAULT '',
  `kurs` varchar(64) DEFAULT '0',
  `num` tinyint(11) DEFAULT '0',
  `enabled` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_valuta`
--

INSERT INTO `phpshop_valuta` (`id`, `name`, `code`, `iso`, `kurs`, `num`, `enabled`) VALUES
(4, 'Гривны', 'гр.', 'UAU', '0.061', 4, '1'),
(5, 'Доллары', '$', 'USD', '0.016', 0, '1'),
(6, 'Рубли', 'руб.', 'RUB', '1', 1, '1');

--
-- Модуль ProductLastView
--

CREATE TABLE `phpshop_modules_productlastview_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1','2') NOT NULL default '1',
  `flag` enum('1','2') NOT NULL default '1',
  `title` varchar(64) NOT NULL default '',
  `pic_width` tinyint(100) NOT NULL default '0',
  `memory` enum('0','1') NOT NULL default '1',
  `num` tinyint(11) NOT NULL default '0',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;


INSERT INTO `phpshop_modules_productlastview_system` VALUES (1, '2', '1', 'Просмотренные товары', 50, '1', 5, '');

CREATE TABLE `phpshop_modules_productlastview_memory` (
  `id` int(11) NOT NULL auto_increment,
  `memory` varchar(64) NOT NULL default '',
  `product` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  `user` int(11) NOT NULL default '0',
  `ip` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
