
ALTER TABLE `phpshop_sort` ADD `code` VARCHAR(6) NOT NULL;

--
-- Структура таблицы `phpshop_modules_w4asmartfilter_system`
--

DROP TABLE IF EXISTS `phpshop_modules_w4asmartfilter_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_w4asmartfilter_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial` varchar(64) NOT NULL DEFAULT '',
  `color` int(11) NOT NULL,
  `price_enabled` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_w4asmartfilter_system` VALUES (1,'','999','1');

--
-- Структура таблицы `phpshop_modules_w4asmartfilter`
--
DROP TABLE IF EXISTS `phpshop_modules_w4asmartfilter`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_w4asmartfilter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `vendor_id_0` int(11) NOT NULL,
  `var_id_0` int(11) NOT NULL,
  `var_name_0` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_1` int(11) NOT NULL,
  `var_id_1` int(11) NOT NULL,
  `var_name_1` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_2` int(11) NOT NULL,
  `var_id_2` int(11) NOT NULL,
  `var_name_2` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_3` int(11) NOT NULL,
  `var_id_3` int(11) NOT NULL,
  `var_name_3` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_4` int(11) NOT NULL,
  `var_id_4` int(11) NOT NULL,
  `var_name_4` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_5` int(11) NOT NULL,
  `var_id_5` int(11) NOT NULL,
  `var_name_5` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_6` int(11) NOT NULL,
  `var_id_6` int(11) NOT NULL,
  `var_name_6` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_7` int(11) NOT NULL,
  `var_id_7` int(11) NOT NULL,
  `var_name_7` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_8` int(11) NOT NULL,
  `var_id_8` int(11) NOT NULL,
  `var_name_8` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `vendor_id_9` int(11) NOT NULL,
  `var_id_9` int(11) NOT NULL,
  `var_name_9` varchar(64) CHARACTER SET cp1251 NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS `phpshop_modules_w4asmartfilter_categories`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_w4asmartfilter_categories` (
  `id` int(11) NOT NULL,
  `id_sort` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET cp1251 NOT NULL,
  UNIQUE KEY `num` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_w4asmartfilter_categories`
--

INSERT INTO `phpshop_modules_w4asmartfilter_categories` (`id`, `id_sort`, `name`) VALUES
(0, 0, ''),
(1, 0, ''),
(2, 0, ''),
(3, 0, ''),
(4, 0, ''),
(5, 0, ''),
(6, 0, ''),
(7, 0, ''),
(8, 0, ''),
(9, 0, '');