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
(1, '��������'),
(2, '����� � �����'),
(3, '������ ��� ����������'),
(4, '�����������'),
(5, '����, ��������� � ���������'),
(6, '���������� � ����������'),
(7, '�������� � ����������� �����'),
(8, '��������'),
(9, '���������� ����������');

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
(32, '������ �����', 1),
(33, '�����', 1),
(34, '������������ ��������', 1),
(35, 'MP3-������', 2),
(36, '��������, �������, ���������', 2),
(37, '�����, DVD � Blu-ray ������', 2),
(38, '�����������', 2),
(39, '������ � ��������', 2),
(40, '���������', 2),
(41, '������ � ������', 2),
(42, '����������� ������, ���������', 2),
(43, '��������', 2),
(44, '���������� � ���������', 2),
(45, '��������� � ��������', 2),
(46, '����������', 2),
(47, '��������', 3),
(48, '���-������', 3),
(49, '��������� � ����', 3),
(50, '���������� � ����', 3),
(51, 'CD, DVD � Blu-ray �������', 3),
(52, '����� �������', 3),
(53, '����������', 3),
(54, 'Ƹ����� �����', 3),
(55, '�������� �����', 3),
(56, '�����������', 3),
(57, '�������', 3),
(58, '����������� �����', 3),
(59, '����������� ������', 3),
(60, '����������', 3),
(61, '������� ����������', 3),
(62, '��������', 3),
(63, '���������� ������ �����', 3),
(64, '������� ������������', 3),
(65, '��-������', 3),
(66, '������ � ����� ������', 3),
(67, '����������', 3),
(68, '���������� ������������', 4),
(69, '���������� ������������', 4),
(70, '�������� ������������', 4),
(71, '������� � ���������', 4),
(72, '���������', 4),
(73, '������������ � ����������', 4),
(74, '������� ���������', 5),
(75, '���� ��� ���������', 5),
(76, '������������ ����', 5),
(77, '���������', 5),
(78, '���, ������ � �������', 6),
(79, '��������', 6),
(80, '���������', 6),
(81, '���, ������� �������', 6),
(82, '������������ �����', 6),
(83, '��������� ������', 6),
(84, '����������', 6),
(85, '��������', 7),
(86, '����������� �����', 7),
(87, '����������', 7),
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
(104, '������', 8);

ALTER TABLE `phpshop_products` ADD `condition_avito` varchar(64) DEFAULT '�����';
ALTER TABLE `phpshop_products` ADD `export_avito` enum('0','1') DEFAULT '0';
ALTER TABLE `phpshop_products` ADD `name_avito` varchar(255) DEFAULT '';
ALTER TABLE `phpshop_products` ADD `listing_fee_avito` varchar(64) DEFAULT 'Package';
ALTER TABLE `phpshop_products` ADD `ad_status_avito` varchar(64) DEFAULT 'Free';
ALTER TABLE `phpshop_categories` ADD `category_avito` int(11) DEFAULT NULL;
ALTER TABLE `phpshop_categories` ADD `type_avito` int(11) DEFAULT NULL;
  