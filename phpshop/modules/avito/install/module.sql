DROP TABLE IF EXISTS `phpshop_modules_avito_system`;
CREATE TABLE `phpshop_modules_avito_system` (
  `id` int(11) NOT NULL auto_increment,
  `password` varchar(64),
  `manager` varchar(255),
  `phone` varchar(64),
  `version` varchar(64) default '1.1',
  `additional_description` text default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_avito_system` VALUES (1,'', '', '', '', '1.1');

DROP TABLE IF EXISTS `phpshop_modules_avito_categories`;
CREATE TABLE `phpshop_modules_avito_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_avito_categories` (`id`, `name`) VALUES
(1, 'Телефоны'),
(2, 'Аудио и видео'),
(3, 'Товары для компьютера'),
(4, 'Фототехника'),
(5, 'Игры, приставки и программы'),
(6, 'Оргтехника и расходники'),
(7, 'Планшеты и электронные книги'),
(8, 'Ноутбуки'),
(9, 'Настольные компьютеры');

DROP TABLE IF EXISTS `phpshop_modules_avito_types`;
CREATE TABLE `phpshop_modules_avito_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64),
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_avito_types` (`id`, `name`, `category_id`) VALUES
(1, 'Acer', 1),
(2, 'Alcatel', 1),
(3, 'ASUS', 1),
(4, 'BlackBerry', 1),
(5, 'BQ', 1),
(6, 'DEXP', 1),
(7, 'Explay', 1),
(8, 'Fly', 1),
(9, 'Highscreen', 1),
(10, 'HTC', 1),
(11, 'Huawei', 1),
(12, 'iPhone', 1),
(13, 'Lenovo', 1),
(14, 'LG', 1),
(15, 'Meizu', 1),
(16, 'Micromax', 1),
(17, 'Microsoft', 1),
(18, 'Motorola', 1),
(19, 'MTS', 1),
(20, 'Nokia', 1),
(21, 'Panasonic', 1),
(22, 'Philips', 1),
(23, 'Prestigio', 1),
(24, 'Samsung', 1),
(25, 'Siemens', 1),
(26, 'SkyLink', 1),
(27, 'Sony', 1),
(28, 'teXet', 1),
(29, 'Vertu', 1),
(30, 'Xiaomi', 1),
(31, 'ZTE', 1),
(32, 'Другие марки', 1),
(33, 'Рации', 1),
(34, 'Стационарные телефоны', 1),
(35, 'MP3-плееры', 2),
(36, 'Акустика, колонки, сабвуферы', 2),
(37, 'Видео, DVD и Blu-ray плееры', 2),
(38, 'Видеокамеры', 2),
(39, 'Кабели и адаптеры', 2),
(40, 'Микрофоны', 2),
(41, 'Музыка и фильмы', 2),
(42, 'Музыкальные центры, магнитолы', 2),
(43, 'Наушники', 2),
(44, 'Телевизоры и проекторы', 2),
(45, 'Усилители и ресиверы', 2),
(46, 'Аксессуары', 2),
(47, 'Акустика', 3),
(48, 'Веб-камеры', 3),
(49, 'Джойстики и рули', 3),
(50, 'Клавиатуры и мыши', 3),
(51, 'CD, DVD и Blu-ray приводы', 3),
(52, 'Блоки питания', 3),
(53, 'Видеокарты', 3),
(54, 'Жёсткие диски', 3),
(55, 'Звуковые карты', 3),
(56, 'Контроллеры', 3),
(57, 'Корпусы', 3),
(58, 'Материнские платы', 3),
(59, 'Оперативная память', 3),
(60, 'Процессоры', 3),
(61, 'Системы охлаждения', 3),
(62, 'Мониторы', 3),
(63, 'Переносные жёсткие диски', 3),
(64, 'Сетевое оборудование', 3),
(65, 'ТВ-тюнеры', 3),
(66, 'Флэшки и карты памяти', 3),
(67, 'Аксессуары', 3),
(68, 'Компактные фотоаппараты', 4),
(69, 'Зеркальные фотоаппараты', 4),
(70, 'Плёночные фотоаппараты', 4),
(71, 'Бинокли и телескопы', 4),
(72, 'Объективы', 4),
(73, 'Оборудование и аксессуары', 4),
(74, 'Игровые приставки', 5),
(75, 'Игры для приставок', 5),
(76, 'Компьютерные игры', 5),
(77, 'Программы', 5),
(78, 'МФУ, копиры и сканеры', 6),
(79, 'Принтеры', 6),
(80, 'Телефония', 6),
(81, 'ИБП, сетевые фильтры', 6),
(82, 'Уничтожители бумаг', 6),
(83, 'Расходные бумаги', 6),
(84, 'Канцелярия', 6),
(85, 'Планшеты', 7),
(86, 'Электронные книги', 7),
(87, 'Аксессуары', 7),
(88, 'Acer', 8),
(89, 'Apple', 8),
(90, 'ASUS', 8),
(91, 'Compaq', 8),
(92, 'Dell', 8),
(93, 'Fujitsu', 8),
(94, 'HP', 8),
(95, 'Huawei', 8),
(96, 'Lenovo', 8),
(97, 'MSI', 8),
(98, 'Packard Bell', 8),
(99, 'Microsoft', 8),
(100, 'Samsung', 8),
(101, 'Sony', 8),
(102, 'Toshiba', 8),
(103, 'Xiaomi', 8),
(104, 'Другой', 8);

ALTER TABLE `phpshop_products` ADD `condition_avito` varchar(64) DEFAULT 'Новое';
ALTER TABLE `phpshop_products` ADD `export_avito` enum('0','1') DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `name_avito` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `listing_fee_avito` varchar(64) DEFAULT 'Package';
ALTER TABLE `phpshop_products` ADD `ad_status_avito` varchar(64) DEFAULT 'Free';
ALTER TABLE `phpshop_categories` ADD `category_avito` int(11) DEFAULT NULL;
ALTER TABLE `phpshop_categories` ADD `type_avito` int(11) DEFAULT NULL;
  