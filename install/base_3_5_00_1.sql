
CREATE TABLE `phpshop_modules` (
  `path` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



CREATE TABLE `phpshop_1c_docs` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `cid` varchar(64) NOT NULL default '',
  `datas` int(11) NOT NULL default '0',
  `datas_f` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



CREATE TABLE `phpshop_1c_jurnal` (
  `id` int(11) NOT NULL auto_increment,
  `datas` varchar(64) NOT NULL default '0',
  `p_name` varchar(64) NOT NULL default '',
  `f_name` varchar(64) NOT NULL default '',
  `time` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_baners` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `content` text NOT NULL,
  `count_all` int(11) NOT NULL default '0',
  `count_today` int(11) NOT NULL default '0',
  `flag` enum('0','1') NOT NULL default '0',
  `datas` varchar(32) NOT NULL default '',
  `limit_all` int(11) NOT NULL default '0',
  `dir` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_baners` VALUES
(1, 'Дизайн-бюро PHPShop', '<A title="Акция! 10% скидка на персональный дизайн" href="http://www.phpshop.ru/docs/service.html#1"><IMG border=0 src="/UserFiles/Image/Trial/banner_skidka1.gif" width=468 height=60></A>', 472092, 1931, '1', '30.06.10', 2147483647, '');
INSERT INTO `phpshop_baners` VALUES (2, 'Аренда интернет-магазина', '<object codebase="http://active.macromedia.com/flash6/cabs/swflash.cab#version=6.0.0.0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" height="80" width="460"><param name="movie" value="/UserFiles/Image/Trial/shopbuilder.swf"><param name="play" value="true"><param name="loop" value="true"><param name="WMode" value="Opaque"><param name="quality" value="high"><param name="bgcolor" value=""><param name="align" value=""><embed src="/UserFiles/Image/Trial/shopbuilder.swf" play="true" loop="true" wmode="Opaque" quality="high" bgcolor="" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" align="" height="80" width="460"></object>', 29, 29, '1', '14.06.11', 2147483647, '');


CREATE TABLE `phpshop_bigcsv` (
  `id` enum('1') NOT NULL default '1',
  `file` text NOT NULL,
  `status` enum('0','1','2') NOT NULL default '0',
  `seek` bigint(12) NOT NULL default '0',
  `num_new` bigint(12) NOT NULL default '0',
  `num_upd` bigint(12) NOT NULL default '0',
  `aoption` blob NOT NULL,
  `num` int(8) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_black_list` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ip` varchar(32) NOT NULL default '',
  `datas` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_cache` (
  `sesid` varchar(64) NOT NULL default '',
  `cache` longblob NOT NULL,
  `datas` int(11) NOT NULL default '0',
  PRIMARY KEY  (`sesid`),
  KEY `datas` (`datas`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_categories` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `num` tinyint(11) NOT NULL default '0',
  `parent_to` int(11) NOT NULL default '0',
  `yml` enum('0','1') NOT NULL default '1',
  `num_row` enum('1','2','3') NOT NULL default '2',
  `num_cow` tinyint(11) NOT NULL default '0',
  `sort` blob NOT NULL,
  `content` text NOT NULL,
  `vid` enum('0','1') NOT NULL default '0',
  `name_rambler` varchar(255) NOT NULL default '',
  `servers` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `title_enabled` enum('0','1','2') NOT NULL default '0',
  `title_shablon` varchar(255) NOT NULL default '',
  `descrip` varchar(255) NOT NULL default '',
  `descrip_enabled` enum('0','1','2') NOT NULL default '0',
  `descrip_shablon` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `keywords_enabled` enum('0','1','2') NOT NULL default '0',
  `keywords_shablon` varchar(255) NOT NULL default '',
  `skin` varchar(64) NOT NULL default '',
  `skin_enabled` enum('0','1') NOT NULL default '0',
  `order_by` enum('1','2','3') NOT NULL default '3',
  `order_to` enum('1','2') NOT NULL default '1',
  `secure_groups` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parent_to` (`parent_to`),
  KEY `servers` (`servers`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_categories` VALUES
(1, 'Мелкая бытовая техника', 0, 0, '0', '', 0, 'N;', '<p><img alt=\"\" src=\"/UserFiles/Image/Trial/img9_18900s.jpg\" width=\"86\" border=\"0\" height=\"100\"></p>\r\n', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '3', '1', ''),
(2, 'Утюги', 3, 1, '0', '', 0, 'a:4:{i:0;s:2:\"16\";i:1;s:2:\"23\";i:2;s:1:\"4\";i:3;s:1:\"5\";}', '', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', ''),
(3, 'Машинки для стрижки', 2, 1, '0', '2', 0, 'a:6:{i:0;s:1:\"2\";i:1;s:2:\"14\";i:2;s:2:\"16\";i:3;s:2:\"23\";i:4;s:2:\"29\";i:5;s:2:\"17\";}', '', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', ''),
(4, 'Электрочайники', 1, 1, '0', '2', 0, 'a:3:{i:0;s:1:\"2\";i:1;s:2:\"23\";i:2;s:2:\"29\";}', '<BR>', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', ''),
(5, 'Крупная бытовая техника', 0, 0, '0', '', 0, 'N;', '<img alt=\"\" src=\"/UserFiles/Image/Trial/img21_66070s.jpg\" width=\"85\" border=\"0\" height=\"100\">\r\n', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '3', '1', ''),
(6, 'Микроволновые печи', 1, 5, '0', '2', 0, 'a:3:{i:0;s:2:\"14\";i:1;s:2:\"15\";i:2;s:2:\"10\";}', '', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', ''),
(7, 'Стиральные машины', 3, 5, '0', '2', 0, 'a:3:{i:0;s:1:\"9\";i:1;s:2:\"19\";i:2;s:2:\"21\";}', '', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', ''),
(8, 'Печи-духовки', 2, 5, '0', '2', 0, 'a:4:{i:0;s:1:\"2\";i:1;s:2:\"14\";i:2;s:2:\"23\";i:3;s:2:\"10\";}', '', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', ''),
(9, 'Аудио-Видео-Фото', 0, 0, '0', '', 0, 'N;', '<IMG height=73 alt=\"\" src=\"/UserFiles/Image/Trial/img26_20400s.jpg\" width=100 border=0>', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '', '', ''),
(10, 'ЖК-телевизоры', 0, 9, '0', '2', 0, 'a:3:{i:0;s:1:\"7\";i:1;s:1:\"2\";i:2;s:2:\"10\";}', '<BR>', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', ''),
(11, 'Цифровые фотоаппараты', 0, 9, '0', '2', 0, 'a:4:{i:0;s:1:\"2\";i:1;s:2:\"10\";i:2;s:2:\"24\";i:3;s:2:\"27\";}', '<BR>', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', ''),
(12, 'MP3-плееры', 0, 9, '0', '2', 0, 'a:3:{i:0;s:1:\"2\";i:1;s:2:\"10\";i:2;s:2:\"28\";}', '', '', '', '', '', '0', '', '', '0', '', '', '0', '', 'phpshop_1', '', '1', '1', '');

CREATE TABLE `phpshop_comment` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `datas` varchar(32) default NULL,
  `name` varchar(32) default NULL,
  `parent_id` int(11) NOT NULL default '0',
  `content` text,
  `user_id` int(11) NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_comment` VALUES
(4, '1277798400', 'Екатерина', 13, 'В целом, очень приличная вещь, рекомендую.', 66, '1'),
(3, '1276675200', 'Олег', 10, 'в целом продукт отличный! \r\nпользуюсь с удовольствием уже долго!) :yes::yes::yes:', 2, '1');


CREATE TABLE `phpshop_delivery` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `city` varchar(255) NOT NULL default '',
  `price` float NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '1',
  `flag` enum('0','1') NOT NULL default '0',
  `price_null` float NOT NULL default '0',
  `price_null_enabled` enum('0','1') NOT NULL default '0',
  `PID` int(11) NOT NULL default '0',
  `taxa` int(11) NOT NULL default '0',
  `is_folder` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_delivery` VALUES
(1, 'Курьер', '0', '1', '', '0', '', 0, 0, '1'),
(3, 'Москва в пределах МКАД', '180', '1', '0', '0', '0', 1, 0, '0'),
(4, 'Москва за пределами МКАД', '300', '1', '0', '0', '0', 1, 0, '0'),
(7, 'Почта России', '0', '1', '', '0', '', 0, 0, '1'),
(8, 'Самара', '500', '1', '', '0', '', 7, 50, '0'),
(9, 'Екатеринбург', '100', '1', '', '1000', '1', 7, 60, '0');


CREATE TABLE `phpshop_discount` (
  `id` tinyint(11) unsigned NOT NULL auto_increment,
  `sum` int(255) NOT NULL default '0',
  `discount` varchar(64) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_foto` (
  `id` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `num` tinyint(11) NOT NULL default '0',
  `info` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_foto` VALUES
(292, 1, '/UserFiles/Image/Trial/img1_14820.jpg', 0, ''),
(296, 2, '/UserFiles/Image/Trial/img2_61080.jpg', 0, ''),
(300, 3, '/UserFiles/Image/Trial/img3_66664.jpg', 0, ''),
(7, 4, '/UserFiles/Image/Trial/img4_97080.jpg', 0, ''),
(276, 6, '/UserFiles/Image/Trial/img6_20923.jpg', 0, ''),
(288, 38, '/UserFiles/Image/Trial/img38_11536.jpg', 0, ''),
(280, 7, '/UserFiles/Image/Trial/img7_66247.jpg', 0, ''),
(284, 8, '/UserFiles/Image/Trial/img8_13325.jpg', 0, ''),
(252, 9, '/UserFiles/Image/Trial/img9_20828.jpg', 0, ''),
(258, 10, '/UserFiles/Image/Trial/img10_98700.jpg', 0, ''),
(271, 11, '/UserFiles/Image/Trial/img11_15809.jpg', 0, ''),
(253, 9, '/UserFiles/Image/Trial/img9_15513.jpg', 0, ''),
(327, 13, '/UserFiles/Image/Trial/img13_15367.jpg', 0, ''),
(337, 15, '/UserFiles/Image/Trial/img15_13063.jpg', 0, ''),
(331, 14, '/UserFiles/Image/Trial/img14_61199.jpg', 0, ''),
(339, 16, '/UserFiles/Image/Trial/img16_13068.jpg', 0, ''),
(373, 17, '/UserFiles/Image/Trial/img17_35966.jpg', 0, ''),
(377, 18, '/UserFiles/Image/Trial/img18_54162.jpg', 0, ''),
(381, 19, '/UserFiles/Image/Trial/img19_97642.', 0, ''),
(385, 20, '/UserFiles/Image/Trial/img20_13117.jpg', 0, ''),
(358, 21, '/UserFiles/Image/Trial/img21_10640.jpg', 0, ''),
(361, 22, '/UserFiles/Image/Trial/img22_71944.jpg', 0, ''),
(365, 23, '/UserFiles/Image/Trial/img23_11103.jpg', 0, ''),
(369, 24, '/UserFiles/Image/Trial/img24_19847.jpg', 0, ''),
(406, 25, '/UserFiles/Image/Trial/img25_27608.jpg', 0, ''),
(422, 26, '/UserFiles/Image/Trial/img26_12295.jpg', 0, ''),
(414, 27, '/UserFiles/Image/Trial/img27_57811.jpg', 0, ''),
(421, 28, '/UserFiles/Image/Trial/img28_64148.jpg', 0, ''),
(423, 29, '/UserFiles/Image/Trial/img29_62370.jpg', 0, ''),
(428, 30, '/UserFiles/Image/Trial/img30_20926.jpg', 0, ''),
(435, 31, '/UserFiles/Image/Trial/img31_11735.jpg', 0, ''),
(437, 32, '/UserFiles/Image/Trial/img32_56526.jpg', 0, ''),
(441, 33, '/UserFiles/Image/Trial/img33_20905.jpg', 0, ''),
(450, 35, '/UserFiles/Image/Trial/img35_49910.jpg', 0, ''),
(453, 36, '/UserFiles/Image/Trial/img36_64985.jpg', 0, ''),
(293, 1, '/UserFiles/Image/Trial/img1_15540.jpg', 0, ''),
(304, 37, '/UserFiles/Image/Trial/img37_17791.jpg', 0, ''),
(301, 3, '/UserFiles/Image/Trial/img3_11413.jpg', 0, ''),
(294, 1, '/UserFiles/Image/Trial/img1_16674.jpg', 0, ''),
(328, 13, '/UserFiles/Image/Trial/img13_59320.jpg', 0, ''),
(297, 2, '/UserFiles/Image/Trial/img2_15458.jpg', 0, ''),
(298, 2, '/UserFiles/Image/Trial/img2_16142.jpg', 0, ''),
(302, 3, '/UserFiles/Image/Trial/img3_67419.jpg', 0, ''),
(305, 37, '/UserFiles/Image/Trial/img37_14361.jpg', 0, ''),
(279, 6, '/UserFiles/Image/Trial/img6_81860.jpg', 0, ''),
(285, 8, '/UserFiles/Image/Trial/img8_43325.jpg', 0, ''),
(286, 8, '/UserFiles/Image/Trial/img8_19919.jpg', 0, ''),
(281, 7, '/UserFiles/Image/Trial/img7_20815.jpg', 0, ''),
(282, 7, '/UserFiles/Image/Trial/img7_39656.jpg', 0, ''),
(277, 6, '/UserFiles/Image/Trial/img6_18424.jpg', 0, ''),
(278, 6, '/UserFiles/Image/Trial/img6_12208.jpg', 0, ''),
(289, 38, '/UserFiles/Image/Trial/img38_12110.jpg', 0, ''),
(290, 38, '/UserFiles/Image/Trial/img38_35567.jpg', 0, ''),
(256, 9, '/UserFiles/Image/Trial/img9_47633.jpg', 0, ''),
(255, 9, '/UserFiles/Image/Trial/img9_55876.jpg', 0, ''),
(259, 10, '/UserFiles/Image/Trial/img10_70506.jpg', 0, ''),
(260, 10, '/UserFiles/Image/Trial/img10_99517.jpg', 0, ''),
(267, 11, '/UserFiles/Image/Trial/img11_19968.jpg', 0, ''),
(268, 11, '/UserFiles/Image/Trial/img11_16800.jpg', 0, ''),
(269, 11, '/UserFiles/Image/Trial/img11_15173.jpg', 0, ''),
(197, 41, '/UserFiles/Image/Trial/img41_86676.jpg', 0, ''),
(332, 14, '/UserFiles/Image/Trial/img14_14799.jpg', 0, ''),
(333, 14, '/UserFiles/Image/Trial/img14_16725.jpg', 0, ''),
(342, 16, '/UserFiles/Image/Trial/img16_14316.jpg', 0, ''),
(335, 15, '/UserFiles/Image/Trial/img15_13938.jpg', 0, ''),
(340, 16, '/UserFiles/Image/Trial/img16_95385.jpg', 0, ''),
(341, 16, '/UserFiles/Image/Trial/img16_16151.jpg', 0, ''),
(374, 17, '/UserFiles/Image/Trial/img17_34086.jpg', 0, ''),
(375, 17, '/UserFiles/Image/Trial/img17_18035.jpg', 0, ''),
(378, 18, '/UserFiles/Image/Trial/img18_61454.jpg', 0, ''),
(379, 18, '/UserFiles/Image/Trial/img18_20302.jpg', 0, ''),
(382, 19, '/UserFiles/Image/Trial/img19_10107.jpg', 0, ''),
(383, 19, '/UserFiles/Image/Trial/img19_97468.jpg', 0, ''),
(386, 20, '/UserFiles/Image/Trial/img20_70570.jpg', 0, ''),
(389, 20, '/UserFiles/Image/Trial/img20_35512.jpg', 0, ''),
(359, 21, '/UserFiles/Image/Trial/img21_32804.jpg', 0, ''),
(357, 21, '/UserFiles/Image/Trial/img21_57890.jpg', 0, ''),
(362, 22, '/UserFiles/Image/Trial/img22_10116.jpg', 0, ''),
(363, 22, '/UserFiles/Image/Trial/img22_99779.jpg', 0, ''),
(366, 23, '/UserFiles/Image/Trial/img23_12441.jpg', 0, ''),
(367, 23, '/UserFiles/Image/Trial/img23_50672.jpg', 0, ''),
(370, 24, '/UserFiles/Image/Trial/img24_20831.jpg', 0, ''),
(371, 24, '/UserFiles/Image/Trial/img24_13073.jpg', 0, ''),
(407, 25, '/UserFiles/Image/Trial/img25_38615.jpg', 0, ''),
(408, 25, '/UserFiles/Image/Trial/img25_17990.jpg', 0, ''),
(426, 29, '/UserFiles/Image/Trial/img29_18781.jpg', 0, ''),
(410, 26, '/UserFiles/Image/Trial/img26_39871.jpg', 0, ''),
(415, 27, '/UserFiles/Image/Trial/img27_13799.jpg', 0, ''),
(416, 27, '/UserFiles/Image/Trial/img27_10423.jpg', 0, ''),
(420, 28, '/UserFiles/Image/Trial/img28_12357.jpg', 0, ''),
(419, 28, '/UserFiles/Image/Trial/img28_36278.jpg', 0, ''),
(424, 29, '/UserFiles/Image/Trial/img29_26450.jpg', 0, ''),
(425, 29, '/UserFiles/Image/Trial/img29_15804.jpg', 0, ''),
(429, 30, '/UserFiles/Image/Trial/img30_91257.jpg', 0, ''),
(430, 30, '/UserFiles/Image/Trial/img30_77562.jpg', 0, ''),
(433, 31, '/UserFiles/Image/Trial/img31_62035.jpg', 0, ''),
(434, 31, '/UserFiles/Image/Trial/img31_16012.jpg', 0, ''),
(438, 32, '/UserFiles/Image/Trial/img32_12719.jpg', 0, ''),
(439, 32, '/UserFiles/Image/Trial/img32_74919.jpg', 0, ''),
(442, 33, '/UserFiles/Image/Trial/img33_94300.jpg', 0, ''),
(443, 33, '/UserFiles/Image/Trial/img33_16896.jpg', 0, ''),
(447, 34, '/UserFiles/Image/Trial/img34_18896.jpg', 0, ''),
(446, 34, '/UserFiles/Image/Trial/img34_54875.jpg', 0, ''),
(451, 35, '/UserFiles/Image/Trial/img35_16730.jpg', 0, ''),
(452, 35, '/UserFiles/Image/Trial/img35_79801.jpg', 0, ''),
(454, 36, '/UserFiles/Image/Trial/img36_56679.jpg', 0, ''),
(455, 36, '/UserFiles/Image/Trial/img36_50315.jpg', 0, ''),
(303, 3, '/UserFiles/Image/Trial/img3_47552.jpg', 0, ''),
(295, 1, '/UserFiles/Image/Trial/img1_14333.jpg', 0, ''),
(272, 12, '/UserFiles/Image/Trial/img12_54691.jpg', 0, ''),
(274, 12, '/UserFiles/Image/Trial/img12_14304.jpg', 0, ''),
(380, 18, '/UserFiles/Image/Trial/img18_37948.jpg', 0, ''),
(206, 41, '/UserFiles/Image/Trial/img41_78022.jpg', 0, ''),
(261, 10, '/UserFiles/Image/Trial/img10_20493.jpg', 0, ''),
(273, 12, '/UserFiles/Image/Trial/img12_39663.jpg', 0, ''),
(275, 12, '/UserFiles/Image/Trial/img12_78129.jpg', 0, ''),
(283, 7, '/UserFiles/Image/Trial/img7_91321.jpg', 0, ''),
(287, 8, '/UserFiles/Image/Trial/img8_21440.jpg', 0, ''),
(291, 38, '/UserFiles/Image/Trial/img38_17545.jpg', 0, ''),
(299, 2, '/UserFiles/Image/Trial/img2_11819.jpg', 0, ''),
(458, 37, '/UserFiles/Image/Trial/img37_11242.jpg', 0, ''),
(307, 37, '/UserFiles/Image/Trial/img37_84802.jpg', 0, ''),
(360, 21, '/UserFiles/Image/Trial/img21_74428.jpg', 0, ''),
(338, 15, '/UserFiles/Image/Trial/img15_14316.jpg', 0, ''),
(334, 14, '/UserFiles/Image/Trial/img14_90055.jpg', 0, ''),
(336, 15, '/UserFiles/Image/Trial/img15_92385.jpg', 0, ''),
(330, 13, '/UserFiles/Image/Trial/img13_53749.jpg', 0, ''),
(344, 13, '/UserFiles/Image/Trial/img13_19444.jpg', 0, ''),
(364, 22, '/UserFiles/Image/Trial/img22_14431.jpg', 0, ''),
(368, 23, '/UserFiles/Image/Trial/img23_16936.jpg', 0, ''),
(372, 24, '/UserFiles/Image/Trial/img24_20598.jpg', 0, ''),
(376, 17, '/UserFiles/Image/Trial/img17_13782.jpg', 0, ''),
(384, 19, '/UserFiles/Image/Trial/img19_12587.jpg', 0, ''),
(388, 20, '/UserFiles/Image/Trial/img20_45107.jpg', 0, ''),
(409, 25, '/UserFiles/Image/Trial/img25_17522.jpg', 0, ''),
(412, 26, '/UserFiles/Image/Trial/img26_77269.jpg', 0, ''),
(417, 27, '/UserFiles/Image/Trial/img27_13427.jpg', 0, ''),
(418, 28, '/UserFiles/Image/Trial/img28_16567.jpg', 0, ''),
(413, 26, '/UserFiles/Image/Trial/img26_19691.jpg', 0, ''),
(427, 29, '/UserFiles/Image/Trial/img29_90265.jpg', 0, ''),
(431, 30, '/UserFiles/Image/Trial/img30_80985.jpg', 0, ''),
(436, 31, '/UserFiles/Image/Trial/img31_15570.jpg', 0, ''),
(440, 32, '/UserFiles/Image/Trial/img32_19735.jpg', 0, ''),
(444, 33, '/UserFiles/Image/Trial/img33_15359.jpg', 0, ''),
(448, 34, '/UserFiles/Image/Trial/img34_52462.jpg', 0, ''),
(449, 34, '/UserFiles/Image/Trial/img34_84429.jpg', 0, ''),
(456, 36, '/UserFiles/Image/Trial/img36_11726.jpg', 0, '');

CREATE TABLE `phpshop_gbook` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `datas` int(11) default NULL,
  `name` varchar(32) default NULL,
  `mail` varchar(32) default NULL,
  `tema` text,
  `otsiv` text,
  `otvet` text,
  `flag` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_jurnal` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user` varchar(64) NOT NULL default '',
  `datas` varchar(32) NOT NULL default '',
  `flag` enum('0','1') NOT NULL default '0',
  `ip` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



CREATE TABLE `phpshop_links` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `image` text NOT NULL,
  `opis` text NOT NULL,
  `link` text NOT NULL,
  `num` int(11) default NULL,
  `enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_links` VALUES
(1, 'PHPShop Software', '', 'Создание интернет-магазина, скрипт интернет-магазина PHPShop.', 'http://www.phpshop.ru', 5, '1'),
(2, 'PHPShop CMS Free', '', 'Бесплатная сиcтема управления сайтом PHPShop CMS Free.', 'http://www.phpshopcms.ru', 3, '1'),
(3, 'Аренда интернет-магазина', '', 'Shopbuilder - это новый SaaS сервис аренды интернет-магазина, позволяющий пользователям за считанные минуты создать полноценный сайт интернет-магазина за 599 руб.', 'http://www.shopbuilder.ru', 1, '1');


CREATE TABLE `phpshop_menu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `content` text NOT NULL,
  `flag` enum('0','1') NOT NULL default '1',
  `num` int(11) NOT NULL default '0',
  `dir` varchar(64) NOT NULL default '',
  `element` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `flag` (`flag`),
  KEY `element` (`element`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


  
CREATE TABLE `phpshop_messages` (
  `ID` int(11) NOT NULL auto_increment,
  `PID` int(11) NOT NULL default '0',
  `UID` int(11) NOT NULL default '0',
  `AID` int(11) NOT NULL default '0',
  `DateTime` datetime NOT NULL default '0000-00-00 00:00:00',
  `Subject` text NOT NULL,
  `Message` text NOT NULL,
  `enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_news` (
  `id` int(11) NOT NULL auto_increment,
  `datas` varchar(32) NOT NULL default '',
  `zag` varchar(255) NOT NULL default '',
  `kratko` text NOT NULL,
  `podrob` text NOT NULL,
  `datau` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_notice` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `product_id` int(11) NOT NULL default '0',
  `datas_start` varchar(64) NOT NULL default '',
  `datas` varchar(64) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS phpshop_opros;
CREATE TABLE `phpshop_opros` (
  `id` int(11) NOT NULL auto_increment,
  `category` int(11) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `total` int(11) NOT NULL default '0',
  `num` tinyint(32) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_opros` VALUES
(1, 1, 'Да', 23, 0),
(2, 1, 'Нормально', 7, 0),
(3, 1, 'Не очень', 7, 0),
(4, 2, 'Да, не удобно ехать к вам', 66, 0),
(5, 2, 'Да, надо, чтоб специалист показал все лично', 62, 0),
(6, 2, 'Нет, не вижу смысла', 114, 0);

CREATE TABLE `phpshop_opros_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `dir` varchar(32) NOT NULL default '',
  `flag` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_opros_categories` VALUES
(1, 'Вам нравится новый дизайн?', '', '0'),
(2, 'Нужен ли Вам  выезд консультанта по созданию интернет-магазина на дом/в офис? ', '', '1');

CREATE TABLE `phpshop_order_status` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `color` varchar(64) NOT NULL default '',
  `sklad_action` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_order_status` VALUES
(1, 'Аннулирован', 'red', ''),
(2, 'Выполняется', '#99cccc', ''),
(3, 'Доставляется', '#ff9900', ''),
(4, 'Выполнен', '#ccffcc', '1'),
(100, 'Передано в бухгалтерию', '#ffff33', '');

CREATE TABLE `phpshop_orders` (
  `id` int(11) NOT NULL auto_increment,
  `datas` varchar(64) NOT NULL default '',
  `uid` varchar(64) NOT NULL default '0',
  `orders` blob NOT NULL,
  `status` text NOT NULL,
  `user` int(11) unsigned NOT NULL default '0',
  `seller` enum('0','1') NOT NULL default '0',
  `statusi` tinyint(11) NOT NULL default '0',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS phpshop_page;
CREATE TABLE `phpshop_page` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `link` varchar(64) NOT NULL default '',
  `category` int(11) NOT NULL default '0',
  `keywords` text NOT NULL,
  `description` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `flag` varchar(16) NOT NULL default '1',
  `num` smallint(3) NOT NULL default '0',
  `datas` int(11) NOT NULL default '0',
  `odnotip` text NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '0',
  `secure` enum('0','1') NOT NULL default '0',
  `secure_groups` varchar(255) NOT NULL default '',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `link` (`link`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_page` VALUES (10, 'Интервью с разработчиком PHPShop', 'page10', 1, 'создание интернет магазина, купить интернет магазин, разработка сайтов, недорогой интернет магазин, изготовление сайтов, движок +для интернет магазина, бухгалтерия интернет магазина ', 'Интервью с разработчиком разработчиком готового интернет-магазина PHPShop', '<DIV><I><EM><IMG border=0 hspace=10 alt="" vspace=5 align=left src="/UserFiles/Image/Trial/denis.jpg" width=113 height=150>В последнее время интерес к интернет-торговле заметно вырос. Стало понятно, что теперь интернет-магазин - не просто виртуальная торговая площадка, а сложная программа, которая обеспечивает стабильный торговый процесс. О преимуществах интернет-магазинов и залоге успешной торговли расскажет Денис Туренко, создатель и главный архитектор PHPShop Software, - популярной платформы для интернет-магазинов в России.<BR><BR></EM></I></DIV>\r\n<DIV><I>Продукт PHPShop широко известен в России.</I></DIV>\r\n<DIV><I>Как давно компания появилась на российском рынке?</I></DIV>\r\n<DIV>&nbsp;</DIV>\r\n<DIV>Компания <A href="http://www.phpshop.ru/">PHPShop Software</A>&nbsp;появилась в 2004 году. За пять лет работы на рынке, мы прошли довольно долгий путь развития. Мы знаем об интернет-торговле не понаслышке, и ставим перед собой задачу прежде всего, быстрого освоения пользователем системы, простого решения сложных задач, например, упрощаем управление магазином с помощью специальных программ и приложений.<BR><BR><I>Денис, как пришла идея создавать интернет-магазины? Почему именно магазины, а не другие интернет-приложения, ведь программирование - это широкая область для творчества.</I></DIV>\r\n<P>В 2004 году мы создавали только CMS для сайтов, на тот момент это была уже полнофункциональная система и хорошо воспринималась пользователями. Неожиданно пришел заказ на создание магазина. Это была возможность сделать шаг вперед, и мы взялись за проект. Примерно через месяц первый магазин PHPShop был готов, а на момент создания релиза было создано уже около десяти интернет-магазинов.<BR>В то время рынок электронной коммерции был практически пустым, имелось всего 3 компании, занимающиеся профессионально созданием интернет-магазинов на собственных CMS. PHPShop занял свою нишу благодаря демократичным ценам и новому подходу к внешнему виду панели управления, которая и до сих пор остается уникальной. К тому же, стиль продуктов PHPShop похож на привычный Windows, это максимально упрощает управление и делает работу более удобной, что не могут не оценить покупатели.</P>\r\n<P><I>Известно, что сейчас на рынке скриптов интернет-магазинов у Вашей компании есть конкуренты. Как Вам удается выдерживать конкуренцию на российском рынке?</I></P>\r\n<P>Естественно, рынок расширяется и становится более емким. Сегодня спрос на интернет-магазины сильно вырос, и соответственно, появилось множество компаний и дизайн-студий, занимающихся их созданием. Некоторые из таких студий создают программные продукты с нуля, но чаще закупают у разработчиков уже готовый скрипт, достаточно гибкий, чтобы адаптировать под свои задачи. Кстати, мы работаем с такими компаниями по <A href="http://www.phpshop.ru/docs/partners.html">партнерской программе</A>, и они имеют возможность закупать движки для магазинов по сниженным ценам.</P>\r\n<DIV>PHPShop выдерживает конкуренцию, благодаря упору на конечный результат. Мы не просто продаем платформу, а оказываем сопутствующие услуги - верстку и дизайн, и в итоге получаем продукт высокого качества на выходе. У нас работают грамотные специалисты, с единым видением развития продукта и сохранения его качества. Мы регулярно создаем бесплатные дополнения и утилиты, для более быстрого и удобного создания, установки, поддержки и синхронизации магазина. Например, полюбившийся пользователями, пакет утилит для быстрого управления магазином <A href="http://www.phpshop.ru/news/ID_208.html">PHPShop EasyControl</A>.</DIV>\r\n<DIV>Уникальной разработкой можно считать продукт Enterprise Pro 1С - синхронизацию товарных баз с программой 1С, теперь автоматически происходит заполнение, синхронизация и принятие заказов с интернет-магазинов в режиме реального времени.<BR>Известно, что даже самый качественный продукт не будет востребован без грамотной технической поддержки, мы это понимаем, и, стараемся оказывать грамотную и эффективную техподдержку. Не может не радовать частота повторных обращений клиентов - они предпочитают работать с нами и дальше.<BR>И конечно, привлекательные цены - немаловажный фактор, благодаря которому люди выбирают PHPShop.<BR><I><BR>Кроме интернет-магазинов, Вы предоставляете бесплатную систему управления сайтом (CMS). Расскажите подробнее о проекте. Чем PHPShop CMS Free отличается от других платформ?</I></DIV>\r\n<P><A href="http://www.phpshopcms.ru/">PHPShop CMS Free</A>– это бесплатная программа, в которую мы вложили наше желание помочь пользователям, без усилий и вложений, создать свой сайт. Наша система позволяет, без малейших навыков работы в web, т.е. без знания html, php, и т.д., создать свой собственный сайт. С появлением новой версии 3.1., с ее помощью возможно самостоятельно сделать все, начиная от сайта представительства, заканчивая простейшим интернет-магазином. На данный момент существует около 40 бесплатных <A href="http://phpshopcms.ru/doc/skins.html">шаблонов</A>- нужно только выбрать подходящий. Недавно мы запустили <A href="http://forum.phpshopcms.ru/">форум для пользователей</A>, так что все вопросы можно задать там, и пообщаться с разработчиком и опытными пользователями. Сейчас, по статистике загрузок,<I>PHPShop CMS Free </I>уже выбрали почти 40000 пользователей, и мы надеемся на активный рост этого показателя.</P>\r\n<P><I>Действительно ли полностью бесплатная эта система? Нет ли каких-нибудь скрытых платежей?</I></P>\r\n<P>Система полностью бесплатная. Это имиджевый проект, нацеленный на популяризацию нашей платной системы управления магазином, в разделе "Полезные ссылки" по умолчаниюпроставлены ссылки, ведущие на наш сайт. Однако мы не настаиваем на упоминание автора, и пользователь в любой момент может отключить эту опцию. Так что надеемся на совесть пользователя (смеется).<BR><BR><I>Компания iTrack произвела независимый рейтинг систем управления сайтами (CMS), составленный по информации о реальных установках на сайтах, с помощью которого установила, что PHPShop занимает 51,7% рынка рунета в CMS для интернет-магазинов. Это серьезный результат.<BR>Как Вы думаете, почему люди выбирают ваш продукт?</I></P>\r\n<P>Конечно, мы довольны таким результатом. Не могу сказать, что этот рейтинг можно сравнить по точности с переписью населения, но он показывает более-менее реальное соотношение загруженных платформ. Как уже и говорилось, люди нас выбирают по соотношению «цена-качество» и большому количеству дополнительных утилит, позволяющих любому пользователю Интернета, вне зависимости от знаний, сделать и поддерживать профессиональный магазин.</P>\r\n<P><I>И все же, нужны ли специальные навыки для работы с PHPShop?</I></P>\r\n<P>Знания никогда не бывают лишними, но сейчас все настолько упрощается, что навыков нужно все меньше, например, можно <A href="http://www.phpshop.ru/news/ID_217.html">создать магазин локально</A>&nbsp;на компьютере или даже «флешке», а потом всего одним нажатием клавиши выгрузить файлы в Интернет, экономя при этом время и деньги. Главное желание, а об остальном мы уже позаботились!</P>\r\n<P><I>Кто Ваши клиенты? От какой категории людей чаще поступают заказы?</I></P>\r\n<P>PHPShop покупают абсолютно разные люди: это и крупные компании, и представительства, а также средние предприниматели и даже студенты. Это еще раз доказывает, что цена на наш продукт очень демократичная: стоимсоть начальной версии <A href="http://www.phpshop.ru/docs/product2.html">PHPShop Start</A>- 3990 руб. и ее может позволить себе любой. Профессиональная версия с поддержкой 1С (<A href="http://www.phpshop.ru/docs/product5.html">PHPShop Pro 1C</A>) стоит 16770 руб. и ориентирована на средний класс бизнеса.</P>\r\n<P><I>Коснулся ли сферы электронной коммерции мировой экономический кризис? Как изменился конкретно Ваш бизнес за два последних кризисных года?</I></P>\r\n<P>К счастью, нет. Наоборот, в связи с массовыми сокращениями, люди начинают работать на себя, в Интернете, и соответственно, становятся нашими клиентами. А клиенты конкурирующих компаний теперь все чаще выбирают PHPShop, потому что больше ценят свои деньги.<BR><I><BR>Какие советы Вы можете дать начинающим предпринимателям?</I></P>\r\n<DIV>Ничего не бояться, и смело начинать бизнес в Интернете. Ведь вы ничем не рискуете.</DIV>\r\n<DIV align=right>&nbsp;</DIV>\r\n<DIV align=right>Туренко Денис,</DIV>\r\n<DIV align=right>создатель компании PHPShop Software,</DIV>\r\n<DIV align=right>главный архитектор проектов phpshop.ru,&nbsp;phpshopcms.ru.</DIV>', '', 1, 1279698501, '', 'Как создавался готовый интернет-магазин', '1', '', '');
INSERT INTO `phpshop_page` VALUES (9, 'Гаджеты для Windows 7', 'page9', 1, 'разработка интернет-магазина, гаджеты для виндовс 7, гаджеты для Windows 7, продажи через интернет, создание интернет-магазина, недорогой интернет-магазин, готовый интернет магазин бесплатно, гаджеты для интернет-магазина', 'Виджеты, или как их называет Microsoft, "гаджеты" --это маленькие приложения, призванные облегчить нам жизнь. Завоевывая все большую популярность, они все же перевернут привычный взгляд на организацию виртуального рабочего места.', '<p><i>Виджеты, или как их называет Microsoft,\r\n"гаджеты" --это маленькие приложения, призванные облегчить нам жизнь.\r\nЗавоевывая все большую популярность, они все же перевернут привычный взгляд на\r\nорганизацию виртуального рабочего места.</i></p><p>Недавно\r\nвышедшая операционная система Windows 7 произвела настоящий фурор среди компьютерных пользователей. Новая версия\r\nсистемы показала, что в Microsoft наконец сделали упор на качество. Помимо улучшенных\r\nтехнических характеристик, Windows 7 радует прекрасным дизайном и удобством интерфейса.</p><p>Главным\r\nотличием Windows Seven от предыдущих версий операционных систем стал новый\r\nподход к организации рабочего стола. Исчезла боковая панель, которую\r\nпрактически никто не использовал, и теперь полезные гаджеты можно размещать\r\nпрямо на рабочем столе.</p><p>К\r\nслову, гаджеты - это мини-приложения, используя которые можно настраивать\r\nудобный и быстрый доступ к практически любой информации - как системной, так и\r\nрегулярно обновляемой через Интернет. Мини-приложения позволяют, например,\r\nпоказывать слайды, просматривать постоянно обновляемые заголовки новостей и\r\nсведения о погоде, искать контакты, следить за финансовыми сводками и выполнять\r\nдругие операции.</p><p>РазработчикиWindows 7 предложили\r\nнесколько встроенных гаджетов, и позаботились, чтобы уже полюбившиеся\r\nприложения из Windows Vista корректно работали в новой версии и наоборот. Гаджеты можно\r\nи нужно качать из Интернета. Они могут значительно облегчить вам жизнь и\r\nускорить работу.</p><p>Вы\r\nможете выбрать любое приложение, посетив веб-узел<a href="http://http://vista.gallery.microsoft.com/vista/SideBar.aspx?mkt=ru-ru"> Gadgets</a>.&nbsp; На сайте доступно множество полезных и бесполезных гаджетов, отсортированных по\r\nрейтингу и количеству скачиваний.</p><p>Кроме того, отдельные разработчики предлагают свои варианты\r\nмини-приложений. Например, незаменимым для владельцев интернет-магазинов или\r\nоператоров, оказался <a href="http://http://www.phpshop.ru/news/ID_212.html">PHPShop Vista Order Gadge</a>,\r\nсделанный под популярный в России<a href="http://http://www.phpshop.ru/"> интернет-магазин PHPShop</a>. Он предназначен для\r\nвывода информации по необработанным заказам и позволяет моментально\r\nконтролировать их. Гаджет доступен для<a href="http://http://www.phpshop.ru/update/gadget/download.php"> бесплатного скачивания </a>на сайте\r\nразработчика.</p>\r\n', '', 1, 1279547998, '', 'Гаджеты для Windows 7', '1', '', '');
INSERT INTO `phpshop_page` VALUES (11, 'Видео-уроки', 'page11', 1000, '', '', '<H2>Посмотрите, как легко и удобно работать с товарами, каталогами, статьями, новостями в PHPShop!</H2><BR><BR>Все видео-уроки смотрите в <A href="http://www.phpshop.ru/help/" target=_blank>on-line учебнике PHPShop</A>.<BR><BR><BR>\r\n<OBJECT width=480 height=385><PARAM NAME="movie" VALUE="http://www.youtube.com/v/AKEMaJwTryo&amp;amp;amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;amp;amp;fs=1?rel=0"><PARAM NAME="allowFullScreen" VALUE="true"><PARAM NAME="allowscriptaccess" VALUE="always">\r\n<embed src="http://www.youtube.com/v/AKEMaJwTryo&amp;amp;amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;amp;amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></OBJECT><BR><BR>\r\n<OBJECT width=480 height=385><PARAM NAME="movie" VALUE="http://www.youtube.com/v/LrqW9lzLxcM&amp;amp;amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;amp;amp;fs=1?rel=0"><PARAM NAME="allowFullScreen" VALUE="true"><PARAM NAME="allowscriptaccess" VALUE="always">\r\n<embed src="http://www.youtube.com/v/LrqW9lzLxcM&amp;amp;amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;amp;amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></OBJECT><BR><BR>\r\n<OBJECT width=480 height=385><PARAM NAME="movie" VALUE="http://www.youtube.com/v/XPuuJD6maXQ&amp;amp;amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;amp;amp;fs=1?rel=0"><PARAM NAME="allowFullScreen" VALUE="true"><PARAM NAME="allowscriptaccess" VALUE="always">\r\n<embed src="http://www.youtube.com/v/XPuuJD6maXQ&amp;amp;amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;amp;amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></OBJECT><BR><BR><BR><BR>Все видео-уроки смотрите в <A href="http://www.phpshop.ru/help/" target=_blank>on-line учебнике PHPShop</A>', '', 1, 1279695906, '', '', '1', '', '');
INSERT INTO `phpshop_page` VALUES (8, 'Как создать сайт самостоятельно', 'page8', 1, 'скрипт сайта, cms, как создать сайт, скачать движок сайта, движок сайта', 'Как создать сайт? Этот вопрос волнует множество людей. Однако создать свою страницу в Интернете можно проще и быстрее, чем вы думаете. Итак, для того, чтобы создать полноценный сайт, нужен хостинг, скрипт, дизайн и контент. Если эти слова вам не знакомы,', '<p>Как создать сайт? Этот вопрос волнует множество людей.\r\nОднако создать свою страницу в Интернете можно проще и быстрее, чем вы думаете.\r\nИтак, для того, чтобы создать полноценный сайт, нужен <i>хостинг, скрипт, дизайн </i>и<i> контент</i>.\r\nЕсли эти слова вам не знакомы, не пугайтесь, ниже я расскажу о каждом из них.</p><p><i>Хостинг</i> – это\r\nуслуга по размещению сайта в сети. Вы можете выбрать платный или бесплатный\r\nхостинг, однако платеж в данном случае будет давать вам необходимые гарантии. К\r\nтому же, удачный сайт можно продать, только если он размещен на платном\r\nхостинге. При покупке хостинга<span></span>регистрируется доменное имя (любое придуманное\r\nвами название) и за сайтом закрепляется адрес вида&nbsp; www.домен.net (.ru,и т.п.). При выборе домена\r\nучтите 2 простых правила: имя должно быть не слишком длинным и должно легко\r\nзапоминаться.</p><p><i>Скрипт</i> сайта (CMS)&nbsp; – это программа, позволяющая организовать взаимодействие\r\nсайта с посетителями. В су щности, скрипт – это и есть сам сайт. Известно, что\r\nне имея навыков программирования, создать скрипт самостоятельно невозможно. Конечно,\r\nвы можете обратиться к услугам программиста, однако существуют уже готовые\r\nскрипты, и, как ни странно, некоторые из\r\nних бесплатны и, в то же время, качественны!</p><p>К примеру, признанно качественные системы управления сайтом\r\nпредлагает отечественный разработчик <a href="http://www.phpshopcms.ru/">PHPShop Software</a>. CMS от\r\nPHPShop полностью\r\nбесплатна и подходит для создания сайта любой сложности: от личной странички до\r\nпредставительства компании. Вам нужно всего лишь скачать программу и сайт\r\nготов!</p><p>Интерфейс такой\r\nпрограммы очень прост и напоминает привычный Windows. Рассмотреть его детально можно благодаря наличию <a href="http://demo.phpshop.ru/">демо-версии</a> на сайте производителя.</p><p>Готовый сайт также освобождает вас от хлопот с<i> дизайном</i>. Например, PHPShop предоставляет\r\nна выбор более 35 бесплатных вариантов\r\nдизайна на любой вкус. Если вы не нашли ничего подходящего, обратитесь к\r\nдизайнерам – на эту CMS легко поставить любой дизайн.</p><p>Итак, вы выбрали (купили, сделали) CMS. Теперь вам предстоит немного\r\nосвоиться и начать наполнять сайт<i> контентом</i>,\r\nто есть содержимым. Разместите на сайте тексты и фотографии! Пожалуйста, думайте\r\nо том, насколько удобно посетителям будет просматривать ваш сайт – не\r\nвставляйте слишком большие или слишком маленькие фотографии, позаботьтесь об их\r\nнебольшом «весе» в мегабайтах, пишите легкие для усвоения тексты и используйте\r\nудобочитаемые шрифты.</p><br>\r\n\r\n', '', 1, 1279547987, '', 'Как создать сайт самостоятельно', '1', '', '');
INSERT INTO `phpshop_page` VALUES (2, 'Связь с 1С', 'page2', 1000, '', '', '<DIV>\r\n<H2>Видео-уроки по работе с 1С-Синхронизацией</H2>Сморите обучающие видео-уроки по работе с 1С-Синхронизацией для PHPShop Pro 1C! Уроки описывают на примерах настройки обработчика связи для 1С версий 7.7 и 8.1.<BR><BR>Все видео-уроки смотрите в <A href="http://www.phpshop.ru/help/" target=_blank>on-line учебнике PHPShop</A>.<BR></DIV><BR>\r\n<OBJECT width=480 height=385><PARAM NAME="movie" VALUE="http://www.youtube.com/v/aHGF7xZwgjM&amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;fs=1?rel=0"><PARAM NAME="allowFullScreen" VALUE="true"><PARAM NAME="allowscriptaccess" VALUE="always">\r\n<embed src="http://www.youtube.com/v/aHGF7xZwgjM&amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></OBJECT><BR><BR>\r\n<OBJECT width=480 height=385><PARAM NAME="movie" VALUE="http://www.youtube.com/v/yDWpuzvrmy8&amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;fs=1?rel=0"><PARAM NAME="allowFullScreen" VALUE="true"><PARAM NAME="allowscriptaccess" VALUE="always">\r\n<embed src="http://www.youtube.com/v/yDWpuzvrmy8&amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></OBJECT><BR><BR>\r\n<OBJECT width=480 height=385><PARAM NAME="movie" VALUE="http://www.youtube.com/v/dk6ULAmGGvI&amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;fs=1?rel=0"><PARAM NAME="allowFullScreen" VALUE="true"><PARAM NAME="allowscriptaccess" VALUE="always">\r\n<embed src="http://www.youtube.com/v/dk6ULAmGGvI&amp;amp;amp;amp;hl=ru_RU&amp;amp;amp;amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></OBJECT><BR><BR><STRONG></STRONG>Все желающие могут:<BR>\r\n<DIV><A href="http://phpshop.ru/loads/ThLHDegJUj/setup.exe" target=_blank>Загрузить PHPShop EasyControl(~ 8 Mb)</A><BR><A href="http://phpshop.ru/help/Content/install/phpshop_server.html#1" target=_blank>Посмотреть инструкцию по PHPShop EasyControl</A><BR>Задать вопросы консультантам по телефону 8-800-700-11-15, звонок бесплатный по РФ.</DIV>', '', 2, 1279695855, '', '', '1', '', '');
INSERT INTO `phpshop_page` VALUES (3, 'Легкое освоение', 'page3', 1000, '', '', '<h2>Выпущена брошюра "Вводное руководство"для легкого освоения PHPShop! </h2>\r\n<div>Для первого легкого освоения интернет-магазина PHPShop выпушена брошюра "Вводное руководство", с подробными примерами и наглядными скриншотами.<br><br>Брошюра входит в стандартную поставку коробочных версий по Москве, все желающие могут получить ее в <a href="http://www.phpshop.ru/docs/adres.html">офисе компании</a>, или <a href="http://www.phpshop.ru/option/blok.rar">скачать в эл. виде</a> (win rar, ~7 мб).</div>\r\n<div><br><b><img alt="" src="/UserFiles/Image/Trial/broshura.jpg" width="277" border="0" height="218"><br><br>1. Введение </b></div>\r\n<div>1.1. Что такое PHPShop? </div>\r\n<div>1.2. Таблица сравнения версий</div>\r\n<div>1.3. Системные требования</div>\r\n<div>1.4. Активация</div>\r\n<div>1.5. Что такое PHPShop EasyControl </div>\r\n<div>1.6. Установка на хостинг</div>\r\n<div><b></b><b>2</b><b>. Начало работы</b></div>\r\n<div>2.1. Настройки PHPShop</div>\r\n<div>2.2. Реквизиты, логотип</div>\r\n<div>3. Keywords &amp; Titles </div>\r\n<div>2.4. Создание нового каталога товаров</div>\r\n<div>2.5. Создание нового подкаталога</div>\r\n<div>2.6. Создание нового товара</div>\r\n<div>2.7. Работа с изображениями</div>\r\n<div>2.8. Работа с характеристиками</div>\r\n<div>2.9. Автоматическая загрузка базы через Exсel </div>\r\n<div>2.10. Автоматическая загрузка прайс-листа через Exсel </div>\r\n<div><b>3. Связь с 1С:Предприятие</b></div>\r\n<div>3.1. Установка и обновление</div>\r\n<div>3.2. Как работает связь с 1С:Предприятие?</div>\r\n<div><b>4. Изменение дизайна</b></div>\r\n<div>4.1. Как поменять логотип?</div>\r\n<div>4.2. Как поменять заголовки в "шапке" сайта?</div>\r\n<div>4.3. Как поменять описание главной страницы?</div>\r\n<div>4.4. Как поменять телефон сайта?</div>\r\n<div>4.5. Как создать новое навигационное меню?</div>\r\n<div>4.6. Как вставить код кнопки?</div>\r\n<div><b>5. Частые вопросы</b></div>\r\n<div>5.1. Подключение электронных способов оплаты</div>\r\n<div>5.2. Работа с текстом</div>\r\n<div>5.3. Пример создания характеристик </div>\r\n<div>5.4. С чего начать синхронизацию товаров c 1C \r\n<div>\r\n<div>5.5. Пример вывода брендов</div>\r\n<div>5.6. Инструкция подключения магазина-клона<br>5.7. Как подать заявку в службу поддержки.</div></div></div>\r\n<p></p>\r\n', '', 3, 1279633387, '', 'Выпущена брошюра "Вводное руководство"для легкого освоения PHPShop!', '1', '', '');
INSERT INTO `phpshop_page` VALUES (4, 'Контакты', 'page4', 1000, '', '', '<h2>Вы находитесь в демо-версии скрипта интернет-магазина PHPShop!</h2>\r\n<h2>Контакты&nbsp;ООО "ПХПШОП"</h2>\r\n<p>Вы можете направить <a title="Скачать бриф на программную доработку \r\nинтернет-магазина" href="http://www.phpshop.ru/brif_tz.doc" target="_blank">бриф на техническое задание</a>, либо <a title="Скачать бриф на персональный дизайн" href="http://www.phpshop.ru/brif_person_diz.doc" target="_blank">бриф на персональный дизайн сайта</a>, либо уточнить интересующие вас вопросы: <br>Андрею Алешкину - <a href="mailto:client@phpshop.ru">client@phpshop.ru</a>, либо <br>Евгению Головину - <a href="mailto:sales@phpshop.ru">sales@phpshop.ru</a>. <br><br>Консультация клиентов, заключение договоров в офисе по предварительной записи <br>по телефонам: <br><br><b>8-800-700-11-15</b> (звонок бесплатный по РФ)<br>(495) 989-11-15, с понедельника по пятницу.<br><br>пн-пт: 10:00-19:00 <br><br>Адрес: м.Семеновская, ул.Щербаковская, д.41а, 1 подъезд, 2 этаж, офис 1, кнопка домофона "PHPShop". </p>\r\n<h1></h1>\r\n<div>&nbsp;</div>\r\n', '', 4, 1279633382, '', '', '1', '', '');
INSERT INTO `phpshop_page` VALUES (5, 'Скрытые возможности обычной  «флешки»', 'page5', 1, '', '', '<i>...В последнее\r\nвремя практически у каждого обладателя персонального компьютера есть переносное\r\nзапоминающее устройство в виде брелока причудливой или не очень формы. В 2009\r\nгоду исполнилось 25-летие первой флешки и ее потомки уже вытеснили собой\r\nдискеты и продолжают вытеснять компакт-диски, а их счастливые обладатели до сих\r\nпор имеют слабое представление об этом устройстве. А ведь помимо хранения\r\nтекстовых и аудио-файлов в этом маленьком дивайсе есть большой потенциал возможностей,\r\nкоторый я постараюсь раскрыть ниже. Но начнем с теории...<br><br></i><b>Что такое флешка?</b><br>USB Flash\r\nDrive&nbsp;или просто «флешка» - это тип внешнего носителя информации, которым\r\nоснащены все мобильные телефоны, MP3 плееры, цифровые камеры, а теперь и\r\nперсональные компьютеры. \r\n\r\nУ\r\nанглийского слова «flash» множество значений: вспышка, проблеск, промелькнуть,\r\nвспыхнуть, озарить, быстро прийти в голову. И такое название не случайно, его\r\nдал устройству изобретатель, японский профессор Фудзио Масуока (Fujio Masuoka).<br><br><b>Новые возможности вашей флешки.</b><br>Большинство владельцев\r\nфлеш-устройств используют их только для хранения мультимедийных файлов, но не\r\nмногие знают, что помимо этой функции флешку можно использовать более широко,\r\nчто сделает этот дивайс еще более полезным.\r\n\r\nВо-первых, кроме\r\nфотографий, музыки и фильмов вы можете записать\r\nна флешку свой браузер. Это удобно, если вы знаете, что вам придется\r\nвыходить в сеть вдали от домашнего или рабочего компьютера.<br><br>С помощью\r\nпортативного браузера вы не испытаете неудобств с интерфейсом непривычного\r\nбраузера, а так же все сохраненные вами ссылки и пароли будут под рукой. Для\r\nэтого нужно скачать портативный браузер (например Mozilla Firefox), распаковать\r\nархив на флешку и взять ее с собой. Таким же образом вы можете захватить с собой любимый почтовый клиент.\r\n\r\n\r\nКстати, если вы\r\nхотите сохранить анонимность почтовой переписки,\r\nиспользуя чужой компьютер, флешка и тут придется кстати.<br><br>Существуют бесплатные\r\nпрограммы для шифрования электронной почты (например,CryptoAnywhere).\r\nОна позволяет отсылать и принимать зашифрованные сообщения (для отправки и\r\nприема сообщений используется пароль). Инсталлируйте такую программу, указав\r\nпуть к съемному диску, и вы сможете запросто пользоваться ей на чужом\r\nкомпьютере.\r\n\r\nК тому же, вы\r\nможете использовать свою флешку в\r\nкачестве аварийного загрузочного диска. Для этого нужно убедиться, что BIOS\r\nподдерживает загрузку с USB-устройств (как это делается, можно легко найти в\r\nсети) и воспользоваться утилитой HP USB disk storage tool. Она предназначена\r\nдля сменных дисков от HP, но замечательно работает с устройствами от других\r\nпроизводителей. Просто подключите флэш-диск и запустите утилиту.<br><br>А можно пойти\r\nдальше и записать на USB диск целую\r\nоперационную систему! Для этого можно использовать один из пакетов Linux\r\nLive CD, предназначенных для сменных носителей и работы без необходимости\r\nустановки на компьютер. Преимущества такого способа загрузки очевидны, если\r\nприходится часто работать на разных компьютерах, можно всегда иметь под рукой\r\nсвою рабочую среду с набором необходимых приложений.<br><br>Мы тоже оценили удобство флешки и\r\nпредлагаем различные варианты ее использования. Так, обладатели\r\nинтернет-магазинов от PHPShop уже тестируют <a href="http://www.phpshop.ru/news/ID_217.html">новую утилиту Shop2Flash</a>.<b> Она позволяет скопировать свой\r\nинтернет-магазин на съемный носитель и управлять им с другого компьютера. \r\n\r\nКак мы видим,\r\nогромный потенциал съемного USB-диска только начал открываться\r\nпотребителю.</b><br>\r\n\r\n\r\n', '', 0, 1279547945, '', '', '1', '', '');
INSERT INTO `phpshop_page` VALUES (7, 'Как начать свое дело в Интернете', 'page7', 1, 'создание интернет-магазина, скрипт интернет-магазина, готовый интернет-магазин, интернет-бизнес', '', '<p><span>О преимуществах интернет-торговли сказано и написано достаточно много. Всем известно, что приобретая интернет-магазин, вы, за небольшие деньги, получаете возможность полноценно вести свой бизнес. Это ваша собственная виртуальная торговая площадка, которая функционирует, не требуя затрат.<i><br></i></span>Таким образом, открыть свое дело удивительно просто - нужно лишь\r\nопределиться, что продавать (или поискать идею для бизнеса в сети) и\r\nнепосредственно открыть интернет-магазин.<br><br><b>Шаг 1. Нейминг</b><br></p><span>После того, как вы определились с идеей, придумайте,\r\nкак назвать свой бизнес. Это самая приятная и творческая часть, но от этого не\r\nменее важная. Ведь, как известно, "как корабль назовешь, так он и\r\nпоплывет".<br><br><b>Шаг2. Регистрация домена</b><br><br>Домен-это адрес сайта. Вы вольны придумать любое доменное имя вида<a href="http://www.%D0%B8%D0%BC%D1%8F.ru">www.имя.ru</a>(.com, .net, .org и\r\nт.д.) или зарегистрировать в качестве доменного имя название, придуманное во\r\nвремя предыдущего пункта. Доменное имя должно быть кратким и звучным, это\r\nважно. Выберите красивое имя!<br><br><b>Шаг3. Самый главный шаг</b><br><br>Творчество закончилось и пришло время серьезно подойти к делу. Нам предстоит\r\nответственный шаг - непосредственно выбор интернет-магазина. Ведь\r\nинтернет-магазин - не только красивая витрина для вашего товара, но и рабочее\r\nместо, которое просто обязано быть комфортным для вас!<br><br>Итак, можно пойти двумя дорогами: купить готовый интернет-магазин или\r\nвоспользоваться бесплатным сервисом. Конечно, слово «бесплатный» сразу\r\nпривлекает внимание, но, к сожалению, как показывает практика, эффективность\r\nтаких магазинов стремится к нулю. Ваш магазин тяжело найти и еще тяжелее выделить\r\nиз ряда братьев-близнецов. В итоге вы получаете стандартное рабочее место,\r\nкоторое невозможно настроить, учитывая специфику конкретного бизнеса.<br><br>Рационально купить готовый полноценный интернет-магазин. При сравнительно\r\nнебольшой стоимости вы получаете действительно удобное и гибкое пространство\r\nдля работы. На данный момент есть немало готовых решений – на любой вкус и\r\nкошелек. Достаточно ввести в строку поиска Яндекса «создание интернет-магазина»\r\nили «скрипт интернет-магазина», на первых местах поиска всегда находятся\r\nпрофессионалы. Обратите внимание на цены, задайте интересующие вас вопросы\r\nсотрудникам компании и оцените грамотность ответов.<br><br>Каждый вправе выбирать сам, но я рекомендую<a href="http://www.phpshop.ru/" target="_blank">PHPShop</a>. Посмотреть и «пощелкать» мышкой до покупки можно,\r\nблагодаря наличию<a href="http://www.phpshop.ru/docs/democentre.html" target="_blank">демо-версии</a>. После знакомства становится понятно, что\r\nинтерфейс PHPShop похож на привычный Windows, а разработчики дали достаточную\r\nсвободу для пользователя: можно поставить на компьютер, а можно работать сразу\r\nна сервере, есть возможность загрузить магазин на «флешку» и начинать работу на\r\nлюбом компьютере. Вы оцените все эти опции потом, когда погрузитесь в настоящую\r\nторговлю.<br><br><b>Шаг4. Дизайн</b><br><br>Поверьте, дизайн важен. Дизайн почти полностью формирует мнение о продавце и\r\nтоваре, а значит хороший дизайн-залог высоких продаж. Покупателю должно быть\r\nприятно находиться на вашем сайте и создать эти комфортные условия вам\r\nобязательно предложат при покупке скрипта. Вы можете довериться дизайнерам\r\nкомпании, а можете искать дизайнера на стороне. Обязательно посмотрите\r\nпортфолио уже выполненных работ на сайте компании-производителя магазина, вы\r\nдолжны увидеть по-настоящему интересные проекты.<br><br><b>Шаг5. Способы оплаты</b><br><br>Вы должны позаботится о том, чтобы вашим клиентам было максимально удобно\r\nоплачивать покупки. На сегодняшний день основными способами оплаты являются:<br><br>• Счет в банк - стандартная форма безналичной оплаты для юр. лиц.<br><br>• Обменная касса Interkassa - платежная система Interkassa.<br><br>• Сообщение - стандартное текстовое сообщение,используется для способа оплаты\r\n"курьерская доставка и т.д.".<br><br>• Visa,Mastercard (PayOnlineSystem) - платежная система PayOnlineSystem.<br><br>• Обменная касса Robox - платежная система ROBOXchange.<br><br>• Сбербанк - стандартная форма квитанции Сбербанка России для физ. лиц.<br><br>• Webmoney - платежная система Webmoney.<br><br>• Z-Payment - платежная система Z-Payment.<br><br>Проверяйте наличие поддержки этих способов оплаты у производителя интернет-магазина.\r\nОтсутствие некоторых из них может стать препятствием для покупки и вы потеряете\r\nпотенциальных клиентов.<br><br><b>Шаг6. Доставка</b><br><br>Нужно обязательно продумать, как вы будете доставлять товар покупателю. Можно\r\nделать это самому, с помощью автомобиля и почты, а можно нанять курьеров.\r\nГлавное - гарантировать своевременную и быструю доставку, ведь это то, чего так\r\nхотят все покупатели. Вам должно быть удобно контролировать движение\r\nтовара, узнайте, как этот вопрос решил разработчик магазина, который собираетесь\r\nкупить.<br><br>Всего 6 шагов, и у вас собственный интернет-бизнес! Конечно, это всего лишь\r\nначало пути, но теперь у вас есть все, чтобы начать зарабатывать на любимом\r\nделе. Однако нужно предусмотреть, что успешный бизнес в скором времени\r\nпотребует расширения. Купленный вами интернет-магазин должен включать более\r\nсложные функции, например такие, как связь с программой 1С для того, чтобы\r\nсовершать более масштабные продажи и контролировать их.<br><br>Удачи и процветания вам и вашему делу!</span><br>', '', 1, 1279546370, '', 'Как начать свое дело в Интернете', '1', '', '');
INSERT INTO `phpshop_page` VALUES (1, 'Описание главной страницы', 'page1', 2000, '', '', 'Выбор дизайна<BR>\r\n<DIV style="PADDING-BOTTOM: 10px; PADDING-LEFT: 10px; PADDING-RIGHT: 10px; PADDING-TOP: 10px" align=center>\r\n<TABLE border=0 align=center>\r\n<TBODY>\r\n<TR>\r\n<TD><A title="Посмотреть в действии" href="/?skin=phpshop_4"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="" src="/UserFiles/Image/Trial/def_skin_15_small.gif" width=100 height=80></A></TD>\r\n<TD><A title="Посмотреть в действии" href="/?skin=phpshop_5"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_13_small.gif" width=100 height=80></A></TD>\r\n<TD><A title="Посмотреть в действии" href="/?skin=phpshop_1"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_9_small.gif" width=100 height=80></A></TD>\r\n<TD style="BORDER-BOTTOM: rgb(211,211,211) 1px dashed; BORDER-LEFT: rgb(211,211,211) 1px dashed; PADDING-BOTTOM: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; BORDER-TOP: rgb(211,211,211) 1px dashed; BORDER-RIGHT: rgb(211,211,211) 1px dashed; PADDING-TOP: 5px" align=middle><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 src="/UserFiles/Image/Trial/palette.png"><BR><A class=b title="Создание персонального дизайна" href="http://www.phpshop.ru/docs/service.html#1">Дизайн<BR>под заказ »</A></TD></TR>\r\n<TR>\r\n<TD><A title="Посмотреть в действии" href="/?skin=phpshop_2"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_10_small.gif" width=100 height=80></A></TD>\r\n<TD><A title="Посмотреть в действии" href="/?skin=phpshop_3"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_11_small.gif" width=100 height=80></A></TD>\r\n<TD><A title="Посмотреть в действии" href="/?skin=aeroblue"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_5_small.gif" width=100 height=80></A></TD>\r\n<TD><A title="Посмотреть в действии" href="/?skin=pink" ?=""><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_7_small.gif" width=100 height=80></A></TD></TR>\r\n<TR>\r\n<TD><A title="Посмотреть в действии" href="/?skin=grass"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_6_small.gif" width=100 height=80></A></TD>\r\n<TD><A href="/?skin=phpshop_6"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt=alt= src="/UserFiles/Image/Trial/def_skin_14_small.gif" width=100 height=80 посмотреть="" в="" действии=""></A></TD>\r\n<TD><A href="/?skin=phpshop_7"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt=alt= src="/UserFiles/Image/Trial/def_skin_12_small.gif" width=100 height=80 посмотреть="" в="" действии=""></A></TD>\r\n<TD><A title="Посмотреть в действии" href="/?skin=yellow_classic"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_4_small.gif" width=100 height=80></A></TD></TR>\r\n<TR>\r\n<TD style="BORDER-BOTTOM: rgb(211,211,211) 1px dashed; BORDER-LEFT: rgb(211,211,211) 1px dashed; PADDING-BOTTOM: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; BORDER-TOP: rgb(211,211,211) 1px dashed; BORDER-RIGHT: rgb(211,211,211) 1px dashed; PADDING-TOP: 5px" align=middle><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 src="/UserFiles/Image/Trial/palette.png"><BR><A class=b title="Верстка дизайна заказчика" href="http://www.phpshop.ru/docs/service.html#2">Верстка »</A></TD>\r\n<TD><A title="Посмотреть в действии" href="/?skin=blue_classic"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_1_small.gif" width=100 height=80></A></TD>\r\n<TD><A title="Посмотреть в действии" href="/?skin=red_classic"><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 alt="Посмотреть в действии" src="/UserFiles/Image/Trial/def_skin_2_small.gif" width=100 height=80></A></TD>\r\n<TD style="BORDER-BOTTOM: rgb(211,211,211) 1px dashed; BORDER-LEFT: rgb(211,211,211) 1px dashed; PADDING-BOTTOM: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; BORDER-TOP: rgb(211,211,211) 1px dashed; BORDER-RIGHT: rgb(211,211,211) 1px dashed; PADDING-TOP: 5px" align=middle><IMG class=imgOff onmouseover=ButOn(this) onmouseout=ButOff(this) border=0 src="/UserFiles/Image/Trial/palette.png"><BR><A class=b title="Создание персонального дизайна" href="http://www.phpshop.ru/docs/service.html#vip">VIP-Дизайн »</A></TD></TR></TBODY></TABLE></DIV>', '', 0, 1279195508, '', '', '1', '0', '');
     

CREATE TABLE `phpshop_page_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `num` int(64) NOT NULL default '1',
  `parent_to` int(11) NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parent_to` (`parent_to`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_page_categories` VALUES
(1, 'Полезная информация', 0, 0, '');

CREATE TABLE `phpshop_payment` (
  `uid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `sum` float NOT NULL default '0',
  `datas` int(11) NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `order` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `phpshop_payment_systems` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '1',
  `num` tinyint(11) NOT NULL default '0',
  `message` text NOT NULL,
  `message_header` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_payment_systems` VALUES
(1, 'Счет в банк', 'bank', '1', 0, 'Наши менеджеры свяжутся с вами. Счет доступен в личном кабинете.', 'ВАШ ЗАКАЗ УСПЕШНО ОФОРМЛЕН'),
(2, 'Квитанция Сбербанка', 'sberbank', '1', 0, 'Квитанция Сбербанка доступная в личном кабинете.', 'ВАШ ЗАКАЗ УСПЕШНО ОФОРМЛЕН'),
(3, 'Наличная оплата', 'message', '1', 0, 'В ближайшее время с вами свяжется наш менеджер.', 'ВАШ ЗАКАЗ УСПЕШНО ОФОРМЛЕН '),
(4, 'Visa, Mastercard (PayOnlineSystem)', 'payonlinesystem', '1', 0, 'Спасибо за покупку', 'Ваш заказ оплачен'),
(5, 'WebMoney, Yandex Money (ROBOXchange)', 'robox', '1', 0, 'Ваш заказ оплачен', 'Ваш заказ оплачен'),
(6, 'WebMoney', 'webmoney', '1', 0, 'Ваш заказ оплачен', 'Ваш заказ оплачен'),
(7, 'Visa (ActivePay)', 'activepay', '1', 0, 'Ваш заказ оплачен', 'Ваш заказ оплачен'),
(8, 'Visa, Mastercard, Webmoney, Yandex (Platron)', 'platron', '1', 0, 'Ваш заказ оплачен', 'Ваш заказ оплачен');

CREATE TABLE `phpshop_products` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `category` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `content` text NOT NULL,
  `price` float NOT NULL default '0',
  `price_n` float NOT NULL default '0',
  `sklad` enum('0','1') NOT NULL default '0',
  `p_enabled` enum('0','1') NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '1',
  `uid` varchar(64) NOT NULL default '',
  `spec` enum('0','1') NOT NULL default '0',
  `odnotip` varchar(64) NOT NULL default '',
  `vendor` varchar(255) NOT NULL default '',
  `vendor_array` blob NOT NULL,
  `yml` enum('0','1') NOT NULL default '0',
  `num` int(11) NOT NULL default '1',
  `newtip` enum('0','1') NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `title_enabled` enum('0','1','2') NOT NULL default '0',
  `datas` int(11) NOT NULL default '0',
  `page` varchar(255) NOT NULL default '',
  `user` tinyint(11) NOT NULL default '0',
  `descrip` varchar(255) NOT NULL default '',
  `descrip_enabled` enum('0','1','2') NOT NULL default '0',
  `title_shablon` varchar(255) NOT NULL default '',
  `descrip_shablon` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `keywords_enabled` enum('0','1','2') NOT NULL default '0',
  `keywords_shablon` varchar(255) NOT NULL default '',
  `pic_small` varchar(255) NOT NULL default '',
  `pic_big` varchar(255) NOT NULL default '',
  `yml_bid_array` tinyblob NOT NULL,
  `parent_enabled` enum('0','1') NOT NULL default '0',
  `parent` text NOT NULL,
  `items` int(11) NOT NULL default '0',
  `weight` float NOT NULL default '0',
  `price2` float NOT NULL default '0',
  `price3` float NOT NULL default '0',
  `price4` float NOT NULL default '0',
  `price5` float NOT NULL default '0',
  `files` text NOT NULL,
  `baseinputvaluta` int(11) NOT NULL default '0',
  `ed_izm` varchar(255) NOT NULL default '',
  `dop_cat` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `category` (`category`),
  KEY `enabled` (`enabled`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_products` VALUES
(1, 2, 'Утюг BINATONE SI 2000 A', '<p>Различные режимы парообразования<br>Спрей<br>Различные температурные режимы</p>\r\n', '<div>Утюг с паром SI-2000A<br>\r\n<p>Различные режимы парообразования<br>Спрей<br>Различные температурные режимы</p></div>\r\n<div>Различные температурные режимы<br>Возможность сухой глажки<br>Функция самоочистки<br>Место для намотки шнура<br>Мерный стаканчик в комплекте</div>\r\n', '500', '0', '0', '1', '1', '', '1', '3,37', 'i23-47ii5-9i', 'a:2:{i:23;a:1:{i:0;s:2:\"47\";}i:5;a:1:{i:0;s:1:\"9\";}}', '', 0, '1', '', '0', 1278410795, 'page2,', 1, '', '0', '@Product@', '', 'купить утюг', '1', '', '/UserFiles/Image/Trial/img1_14333s.jpg', '/UserFiles/Image/Trial/img1_14333.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 0, '100', '0', '0', '0', '0', 'N;', 6, '', ''),
(2, 2, 'Утюг BINATONE SI-2000 WG', '<p>Различные режимы парообразования<br>Спрей<br>Различные температурные режимы</p>\r\n', '<p>Различные режимы парообразования<br>Спрей<br>Различные температурные режимы</p>\r\n', '600', '0', '0', '1', '1', '', '1', '3,37', 'i23-45ii5-9i', 'a:2:{i:23;a:1:{i:0;s:2:\"45\";}i:5;a:1:{i:0;s:1:\"9\";}}', '', 0, '1', '', '0', 1278410852, 'page5,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img2_11819s.jpg', '/UserFiles/Image/Trial/img2_11819.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 100, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(3, 2, 'Утюг BINATONE SI 2600 W', '<p>Различные режимы парообразования<br>Спрей<br>Различные температурные режимы</p>\r\n', '<p>Различные режимы парообразования<br>Спрей<br>Различные температурные режимы</p>\r\n', '650', '0', '0', '1', '1', '', '1', '1,2', 'i23-46ii5-10i', 'a:2:{i:23;a:1:{i:0;s:2:\"46\";}i:5;a:1:{i:0;s:2:\"10\";}}', '', 0, '1', '', '0', 1278410927, 'page6,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img3_47552s.jpg', '/UserFiles/Image/Trial/img3_47552.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 34, '400', '0', '0', '0', '0', 'a:1:{i:0;s:26:\"/UserFiles/Image/chars.rar\";}', 6, '', ''),
(6, 3, 'Машинка для стрижки PHILIPS QC 5050', 'Самый простой способ идеально подстричь волосы в домашних условиях. Машинка для стрижки волос Complete Control дает максимальный контроль и прекрасные результаты.\r\n', '<span style=\\\"font-family: Arial;\\\">Самый простой способ идеально подстричь волосы в домашних условиях. Машинка для стрижки волос Complete Control дает максимальный контроль и прекрасные результаты.</span><br><br>\r\n', '1000', '0', '0', '1', '1', '', '1', '7,8', 'i2-1ii14-98ii23-45i', 'a:3:{i:2;a:1:{i:0;s:1:\"1\";}i:14;a:1:{i:0;s:2:\"98\";}i:23;a:1:{i:0;s:2:\"45\";}}', '', 0, '1', '', '0', 1278409987, 'page2,', 1, '', '0', '', '', 'PHILIPS', '1', '', '/UserFiles/Image/Trial/img6_81860s.jpg', '/UserFiles/Image/Trial/img6_81860.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 5, '140', '0', '0', '0', '0', 'N;', 5, '', ''),
(7, 3, 'Машинка для стрижки PHILIPS QC 5070', 'Philishave Super Easy. Простая в использовании машинка для стрижки волос с 8 настройками длины (1-21 мм), гибкой расческой Flexcomb и фиксатором длины. Короткие стрижки в домашних условиях - быстро и просто.\r\n', 'Philishave Super Easy. Простая в использовании машинка для стрижки волос с 8 настройками длины (1-21 мм), гибкой расческой Flexcomb и фиксатором длины. Короткие стрижки в домашних условиях - быстро и просто.\r\n', '2400', '0', '0', '1', '1', '', '1', '5,38', 'i2-1ii14-98ii23-46i', 'a:3:{i:2;a:1:{i:0;s:1:\"1\";}i:14;a:1:{i:0;s:2:\"98\";}i:23;a:1:{i:0;s:2:\"46\";}}', '', 0, '1', '', '0', 1278410429, 'page3,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img7_91321s.jpg', '/UserFiles/Image/Trial/img7_91321.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 45, '200', '0', '0', '0', '0', 'N;', 6, 'шт.', ''),
(8, 3, 'Машинка для стрижки PHILIPS QC 5099', 'Philishave MaxPricision. Вы сможете в домашних условиях создать практически любую профессиональную стрижку. Машинка для стрижки волос MaxPrecision имеет 15 настроек длины (1-41 мм) и включает прецизионный триммер для создания аккуратных контуров.\r\n', 'Philishave MaxPricision. Вы сможете в домашних условиях создать практически любую профессиональную стрижку. Машинка для стрижки волос MaxPrecision имеет 15 настроек длины (1-41 мм) и включает прецизионный триммер для создания аккуратных контуров.\r\n', '2600', '0', '0', '1', '1', '', '1', '38,6', 'i2-2ii14-98ii23-47i', 'a:3:{i:2;a:1:{i:0;s:1:\"2\";}i:14;a:1:{i:0;s:2:\"98\";}i:23;a:1:{i:0;s:2:\"47\";}}', '', 0, '1', '', '0', 1278410487, 'page5,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img8_21440s.jpg', '/UserFiles/Image/Trial/img8_21440.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 38, '300', '0', '0', '0', '0', 'N;', 6, '', ''),
(9, 4, 'Электрочайник BINATONE AEJ-1501 CG|WG', 'Защита от работы без воды Автоотключение при закипании Вместимость 1.5 л Шкала уровня воды Мощность 2000 Вт Световой индикатор работы\r\n\r\n\r\n\r\n\r\n', 'Защита от работы без воды Автоотключение при закипании Вместимость 1.5 л Шкала уровня воды Мощность 2000 Вт Световой индикатор работы\r\n\r\n\r\n\r\n\r\n', '300', '500', '0', '1', '1', '100', '1', '11,12', 'i2-96ii23-47i', 'a:2:{i:2;a:1:{i:0;s:2:\"96\";}i:23;a:1:{i:0;s:2:\"47\";}}', '', 0, '1', '', '0', 1278408998, 'page2,', 1, '', '0', '', '', 'купить электрочайник', '1', '', '/UserFiles/Image/Trial/img9_47633s.jpg', '/UserFiles/Image/Trial/img9_47633.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 50, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(10, 4, 'Электрочайник BINATONE CEJ-1012 CP', 'Тип нагревательного элемента: скрытый (дисковый) Покрытие нагревательного элемента: нержавеющая сталь Мощность: 1500 Вт Объем: 1 л Фильтр против накипи: синтетический\r\n\r\n\r\n', 'Тип нагревательного элемента: скрытый (дисковый) Покрытие нагревательного элемента: нержавеющая сталь Мощность: 1500 Вт Объем: 1 л Фильтр против накипи: синтетический\r\n\r\n\r\n', '700', '965', '0', '1', '1', '', '1', '11,12', 'i2-96ii23-47i', 'a:2:{i:2;a:1:{i:0;s:2:\"96\";}i:23;a:1:{i:0;s:2:\"47\";}}', '', 0, '1', '', '0', 1278409123, 'page3,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img10_20493s.jpg', '/UserFiles/Image/Trial/img10_20493.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 44, '150', '0', '0', '0', '0', 'N;', 6, '', '#1#3'),
(11, 4, 'Электрочайник BINATONE CEJ-3300 CP|SG|T|WG', 'Тип нагревательного элемента: скрытый (дисковый) Покрытие нагревательного элемента: нержавеющая сталь Мощность (Вт): 2200 Объем (л): 2 Фильтр против накипи: синтетический\r\n\r\n\r\n\r\n\r\n\r\n', 'Тип нагревательного элемента: скрытый (дисковый) Покрытие нагревательного элемента: нержавеющая сталь Мощность (Вт): 2200 Объем (л): 2 Фильтр против накипи: синтетический\r\n\r\n\r\n\r\n\r\n\r\n', '1000', '0', '0', '1', '1', '', '1', '9,10', 'i2-1ii23-45i', 'a:2:{i:2;a:1:{i:0;s:1:\"1\";}i:23;a:1:{i:0;s:2:\"45\";}}', '', 0, '1', '', '0', 1278409414, 'page5,page7,page9,page10,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img11_15809s.jpg', '/UserFiles/Image/Trial/img11_15809.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 65, '150', '0', '0', '0', '0', 'N;', 6, 'штука', ''),
(12, 4, 'Электрочайник BINATONE CEJ-3500 BB|BS|CP', 'MAGIC - thermo control, 2200Вт, 2л., новая технология, позволяющая определять температуру воды в чайнике по цвету внутренней подсветки: - синяя - температура воды до 40°C, - желтая - температура воды от 40°C до 80°C,от 80°C - красная, корпус белый\r\n\r\n\r\n', 'MAGIC - thermo control, 2200Вт, 2л., новая технология, позволяющая определять температуру воды в чайнике по цвету внутренней подсветки: - синяя - температура воды до 40°C, - желтая - температура воды от 40°C до 80°C,от 80°C - красная, корпус белый\r\n\r\n\r\n', '875', '0', '0', '1', '1', '', '1', '9,10', 'i2-2ii23-46i', 'a:2:{i:2;a:1:{i:0;s:1:\"2\";}i:23;a:1:{i:0;s:2:\"46\";}}', '', 0, '1', '', '0', 1278409468, 'page6,page10,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img12_78129s.jpg', '/UserFiles/Image/Trial/img12_78129.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 55, '300', '0', '0', '0', '0', 'N;', 6, '', ''),
(13, 6, 'Микроволновая печь DELONGHI MW 355', 'Механическое управление Таймер на 30 мин Звуковой сигнал Встроенные часы Мощность микроволн 700 Вт EMD Количество уровней мощности 5\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 'Механическое управление Таймер на 30 мин Звуковой сигнал Встроенные часы Мощность микроволн 700 Вт EMD Количество уровней мощности 5\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '1578', '0', '0', '1', '1', '', '1', '15,16', 'i14-26ii15-31ii10-19i', 'a:3:{i:14;a:1:{i:0;s:2:\"26\";}i:15;a:1:{i:0;s:2:\"31\";}i:10;a:1:{i:0;s:2:\"19\";}}', '', 0, '1', '', '0', 1278414654, 'page2,', 1, '', '0', '', '', 'микроволновая печь', '1', '', '/UserFiles/Image/Trial/img13_19444s.jpg', '/UserFiles/Image/Trial/img13_19444.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 199, '4', '0', '0', '0', '0', 'N;', 6, 'шт', ''),
(14, 6, 'Микроволновая печь MOULINEX AFM4 43', 'Объём 22 л Тип управления сенсорный Стеклянное блюдо 290 мм Выходная мощность микроволны, Вт 850 Потребляемая мощность гриля, Вт 1100 Нагревательный элемент гриля Тен Уровней мощности - 6\r\n\r\n', 'Объём 22 л Тип управления сенсорный Стеклянное блюдо 290 мм Выходная мощность микроволны, Вт 850 Потребляемая мощность гриля, Вт 1100 Нагревательный элемент гриля Тен Уровней мощности - 6\r\n\r\n', '3000', '0', '0', '1', '1', '', '1', '15,16', 'i14-27ii15-30ii10-19i', 'a:3:{i:14;a:1:{i:0;s:2:\"27\";}i:15;a:1:{i:0;s:2:\"30\";}i:10;a:1:{i:0;s:2:\"19\";}}', '', 0, '1', '', '0', 1278414302, 'page3,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img14_90055s.jpg', '/UserFiles/Image/Trial/img14_90055.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 22, '166', '0', '0', '0', '0', 'N;', 6, 'шт.', ''),
(15, 6, 'Микроволновая печь SAMSUNG C-100 R-5', 'Объем - 20 л, Микроволновая печь с грилем, Мощность СВЧ - 750 Вт, Мощность гриля - 1100 Вт, 6 уровней мощности, Тип управления - механический, Решетка для гриля, БИО-керамическая эмаль, Таймер на 60 мин., Система С.Т.Р.\r\n\r\n\r\n', 'Объем - 20 л, Микроволновая печь с грилем, Мощность СВЧ - 750 Вт, Мощность гриля - 1100 Вт, 6 уровней мощности, Тип управления - механический, Решетка для гриля, БИО-керамическая эмаль, Таймер на 60 мин., Система С.Т.Р.\r\n\r\n\r\n', '1025', '1320', '0', '1', '1', '', '1', '13,14', 'i14-28ii15-30ii10-19i', 'a:3:{i:14;a:1:{i:0;s:2:\"28\";}i:15;a:1:{i:0;s:2:\"30\";}i:10;a:1:{i:0;s:2:\"19\";}}', '', 0, '1', '', '0', 1278414374, 'page5,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img15_14316s.jpg', '/UserFiles/Image/Trial/img15_14316.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '16', 45, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(16, 6, 'Микроволновая печь SAMSUNG CE-2833 NR', 'Микроволновая печь с грилем Объем - 23 л Мощность СВЧ - 850 Вт / гриля - 1110 Вт 6 уровней мощности Тип управления - тактовый +Dial БИО-керамическая эмаль Система С.Т.Р.\r\n', 'Микроволновая печь с грилем Объем - 23 л Мощность СВЧ - 850 Вт / гриля - 1110 Вт 6 уровней мощности Тип управления - тактовый +Dial БИО-керамическая эмаль Система С.Т.Р.\r\n', '2000', '0', '0', '1', '1', '', '1', '13,14', 'i14-28ii15-31ii10-20i', 'a:3:{i:14;a:1:{i:0;s:2:\"28\";}i:15;a:1:{i:0;s:2:\"31\";}i:10;a:1:{i:0;s:2:\"20\";}}', '', 0, '1', '', '0', 1278414433, 'page6,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img16_14316s.jpg', '/UserFiles/Image/Trial/img16_14316.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 68, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(17, 7, 'Стиральная машина WHIRLPOOL AWO|D 43115', 'Технология Silver Nano Керамический нагреватель Класс энергосбережения - A Класс стирки - A Класс отжима - D Загрузка (кг) - 40 / 4,5 Габариты без упаковки (ШхГхВ, мм) 598 x 404 x 850\r\n', 'Технология Silver Nano Керамический нагреватель Класс энергосбережения - A Класс стирки - A Класс отжима - D Загрузка (кг) - 40 / 4,5 Габариты без упаковки (ШхГхВ, мм) 598 x 404 x 850\r\n', '7523', '0', '0', '1', '1', '', '1', '19,20', 'i9-16ii19-41ii21-43i', 'a:3:{i:9;a:1:{i:0;s:2:\"16\";}i:19;a:1:{i:0;s:2:\"41\";}i:21;a:1:{i:0;s:2:\"43\";}}', '', 0, '1', '', '0', 1278416385, 'page2,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img17_13782s.jpg', '/UserFiles/Image/Trial/img17_13782.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 20, '560', '0', '0', '0', '0', 'N;', 6, '', ''),
(18, 7, 'Стиральная машина CANDY Aquamatic 1000T-45', 'Габариты: высота (см) 69.5, ширина (см) 51, глубина (см) 44 Тип загрузки фронтальная Загрузка (кг) 3.5 Отжим (об/мин) 1000 Класс стирки A /Класс А энергоэффективности/ Класс отжима C Cтрана производитель: Италия\r\n', 'Габариты: высота (см) 69.5, ширина (см) 51, глубина (см) 44 Тип загрузки фронтальная Загрузка (кг) 3.5 Отжим (об/мин) 1000 Класс стирки A /Класс А энергоэффективности/ Класс отжима C Cтрана производитель: Италия\r\n', '2500', '3250', '0', '1', '1', '', '1', '19,20', 'i9-17ii19-41ii21-42i', 'a:3:{i:9;a:1:{i:0;s:2:\"17\";}i:19;a:1:{i:0;s:2:\"41\";}i:21;a:1:{i:0;s:2:\"42\";}}', '', 0, '1', '', '0', 1278416443, 'page3,', 1, '', '0', '', '', 'стиральная машинка', '1', '', '/UserFiles/Image/Trial/img18_37948s.jpg', '/UserFiles/Image/Trial/img18_37948.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 24, '600', '0', '0', '0', '0', 'N;', 6, '', ''),
(19, 7, 'Стиральная машина CANDY CNL 105', 'Размеры: высота (см) 85, ширина (см) 60, глубина (см) 52 Тип загрузки фронтальная Загрузка (кг) 5 Отжим (об/мин) 0-1000 Класс стирки A /Класс А энергоэффективности!/ Класс отжима C Cтрана производитель: Италия, Испания\r\n', 'Размеры: высота (см) 85, ширина (см) 60, глубина (см) 52 Тип загрузки фронтальная Загрузка (кг) 5 Отжим (об/мин) 0-1000 Класс стирки A /Класс А энергоэффективности!/ Класс отжима C Cтрана производитель: Италия, Испания\r\n', '8020', '0', '0', '1', '1', '', '1', '17,18', 'i9-18ii19-40i', 'a:2:{i:9;a:1:{i:0;s:2:\"18\";}i:19;a:1:{i:0;s:2:\"40\";}}', '', 0, '1', '', '0', 1278416600, 'page5,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img19_12587s.jpg', '/UserFiles/Image/Trial/img19_12587.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 45, '360', '0', '0', '0', '0', 'N;', 6, 'ш', ''),
(20, 7, 'Стиральная машина CANDY Aquamatic 800T-45', 'Габариты: высота (см) 69.5, ширина (см) 51, глубина (см) 44 Тип загрузки фронтальная Загрузка (кг) 3.5 Отжим (об/мин) 800 Класс стирки A /Класс А энергоэффективности/ Класс отжима D Cтрана производитель: Италия\r\n\r\n', 'Габариты: высота (см) 69.5, ширина (см) 51, глубина (см) 44 Тип загрузки фронтальная Загрузка (кг) 3.5 Отжим (об/мин) 800 Класс стирки A /Класс А энергоэффективности/ Класс отжима D Cтрана производитель: Италия\r\n\r\n', '11230', '0', '0', '1', '1', '', '1', '17,18', 'i9-16ii19-40ii21-42i', 'a:3:{i:9;a:1:{i:0;s:2:\"16\";}i:19;a:1:{i:0;s:2:\"40\";}i:21;a:1:{i:0;s:2:\"42\";}}', '', 0, '1', '', '0', 1278416771, 'page6,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img20_35512s.jpg', '/UserFiles/Image/Trial/img20_35512.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 56, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(21, 8, 'Хлебопечка MOULINEX OW 2000', 'Мощность 610 Вт 12 режимов приготовления 68 различных вариантов приготовления хлеба Съемная чаша с антипригарным покрытием Функция поддержания выпечки в горячем состоянии Объем: 500, 750, 1000 г\r\n\r\n\r\n\r\n', 'Мощность 610 Вт 12 режимов приготовления 68 различных вариантов приготовления хлеба Съемная чаша с антипригарным покрытием Функция поддержания выпечки в горячем состоянии Объем: 500, 750, 1000 г\r\n\r\n\r\n\r\n', '2005', '2350', '0', '1', '1', '', '1', '23,24', 'i2-2ii14-27ii23-46ii10-19i', 'a:4:{i:2;a:1:{i:0;s:1:\"2\";}i:14;a:1:{i:0;s:2:\"27\";}i:23;a:1:{i:0;s:2:\"46\";}i:10;a:1:{i:0;s:2:\"19\";}}', '', 0, '1', '', '0', 1278415795, 'page2,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img21_74428s.jpg', '/UserFiles/Image/Trial/img21_74428.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 23, '670', '1500', '0', '0', '0', 'N;', 6, 'шт', ''),
(22, 8, 'Хлебопечка KENWOOD BM 250', 'Вес выпечки от 500 гр до 1 кг. Функция позволяющая приготовить хлеба за 58 мин. Возможность выбора цвета корочки. Мощность: 480 Вт. 12 программ.\r\n\r\n', 'Вес выпечки от 500 гр до 1 кг. Функция позволяющая приготовить хлеба за 58 мин. Возможность выбора цвета корочки. Мощность: 480 Вт. 12 программ.\r\n\r\n', '2000', '0', '0', '1', '1', '', '1', '23,24', 'i2-1ii14-37ii23-45ii10-19i', 'a:4:{i:2;a:1:{i:0;s:1:\"1\";}i:14;a:1:{i:0;s:2:\"37\";}i:23;a:1:{i:0;s:2:\"45\";}i:10;a:1:{i:0;s:2:\"19\";}}', '', 0, '1', '', '0', 1278415854, 'page3,', 1, '', '0', '', '', 'хлебопечка KENWOOD', '1', '', '/UserFiles/Image/Trial/img22_14431s.jpg', '/UserFiles/Image/Trial/img22_14431.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '23', 34, '456', '0', '0', '0', '0', 'N;', 6, '', ''),
(23, 8, 'Хлебопечка KENWOOD BM 256', 'Серия: Principio Мощность, Вт: 260 Внутреннее покрытие - алюминий Тип управления - механический Режимы работы духовки - 2 Термостат - есть Тип гриля - тен\r\n\r\n', 'Серия: Principio Мощность, Вт: 2600 Внутреннее покрытие - алюминий Тип управления - механический Режимы работы духовки - 2 Термостат - есть Тип гриля - тен\r\n\r\n', '1863', '0', '0', '1', '1', '', '1', '21,22', 'i2-2ii14-37ii23-47ii10-19i', 'a:4:{i:2;a:1:{i:0;s:1:\"2\";}i:14;a:1:{i:0;s:2:\"37\";}i:23;a:1:{i:0;s:2:\"47\";}i:10;a:1:{i:0;s:2:\"19\";}}', '', 0, '1', '', '0', 1278415941, 'page5,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img23_16936s.jpg', '/UserFiles/Image/Trial/img23_16936.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 45, '333', '0', '0', '0', '0', 'N;', 6, 'шт', ''),
(24, 8, 'Хлебопечка KENWOOD BM 350', 'Вес выпечки от 500 гр до 1 кг. Функция позволяющая приготовить хлеба за 58 мин. Возможность выбора цвета корочки. Мощность: 480 Вт. 12 программ. Корпус из нержавеющей стали с белой пластиковой крышкой и контрольной панелью.\r\n', 'Вес выпечки от 500 гр до 1 кг. Функция позволяющая приготовить хлеба за 58 мин. Возможность выбора цвета корочки. Мощность: 480 Вт. 12 программ. Корпус из нержавеющей стали с белой пластиковой крышкой и контрольной панелью.\r\n', '3000', '0', '0', '1', '1', '', '1', '21,22', 'i14-37ii23-45i', 'a:2:{i:14;a:1:{i:0;s:2:\"37\";}i:23;a:1:{i:0;s:2:\"45\";}}', '', 0, '1', '', '0', 1278416004, 'page6,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img24_20598s.jpg', '/UserFiles/Image/Trial/img24_20598.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '22,23', 46, '560', '0', '0', '0', '0', 'N;', 6, '', ''),
(25, 10, 'ЖК-телевизор SONY KDL-52HX900', 'Невероятное кинематографическое отображение фильмов у вас дома 52” (132 см), Full HD 1080, ЖК ТВ 3D с динамической \r\nсветодиодной подсветкой и Motionflow 400 PRO\r\n', 'Невероятное кинематографическое отображение фильмов у вас дома 52” (132 \r\nсм), Full HD 1080, ЖК ТВ 3D с динамической \r\nсветодиодной подсветкой и Motionflow 400 PRO\r\n\r\n', '30000', '0', '0', '1', '1', '', '1', '27,28', 'i2-1ii10-19i', 'a:2:{i:2;a:1:{i:0;s:1:\"1\";}i:10;a:1:{i:0;s:2:\"19\";}}', '', 0, '1', '', '0', 1278419768, 'page2,', 1, '', '0', '', '', 'телевизор SONY', '1', '', '/UserFiles/Image/Trial/img25_17522s.jpg', '/UserFiles/Image/Trial/img25_17522.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 67, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(26, 10, 'ЖК-телевизор SONY KDL-19BX200|W', 'Компактный телевизор High Definition подойдет для любой \r\nкомнаты<br><br>Новая серия S с диагональю 40\\\" - это цифровые ЖК-телевизоры с высоким качеством изображения, которое обеспечивается системой \\\"BRAVIA ENGINE\\\" и высококачественной ЖК-панелью\r\n\r\n\r\n\r\n\r\n\r\n', 'Компактный телевизор High Definition подойдет для любой \r\nкомнаты<br>\r\n<br>\r\nНовая серия S с диагональю 40\\\" - это цифровые ЖК-телевизоры с высоким \r\nкачеством изображения, которое обеспечивается системой \\\"BRAVIA ENGINE\\\" и\r\n высококачественной ЖК-панелью\r\n\r\n\r\n\r\n\r\n\r\n', '25000', '0', '0', '1', '1', '', '1', '27,28', 'i2-96ii10-19i', 'a:2:{i:2;a:1:{i:0;s:2:\"96\";}i:10;a:1:{i:0;s:2:\"19\";}}', '', 0, '1', '', '0', 1278420010, 'page3,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img26_12295s.jpg', '/UserFiles/Image/Trial/img26_12295.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 56, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(27, 10, 'ЖК-телевизор Sony KDL-46NX700', 'Изящество тонкого корпуса и легкость подключения\r\n\r\n\r\n\r\n', '<p>46” (117 см), Full HD 1080, ЖК ТВ BRAVIA Monolith с подсветкой Edge \r\nLED и встр. Wi-Fi®</p><ul><li>BRAVIA Monolith — уникальный стиль и чувство простора</li><li>Простота Wi-Fi®-подключения к онлайновому контенту и услугам</li><li>Четкое и плавное отображение динамичных сцен кино и спортивных \r\nпередач</li></ul>В новой серии V с диагональю 32\\\" и поддержкой формата HD используются эксклюзивные характеристики от Sony: \\\"Live Colour Creation\\\", BRAVIA ENGINE и высококачественная ЖК-панель. Серия также имеет расширенные возможности подключения и удобные функции.\r\n\r\n\r\n\r\n\r\n', '32000', '33800', '0', '1', '1', '', '1', '25,26', 'i2-96i', 'a:1:{i:2;a:1:{i:0;s:2:\"96\";}}', '', 0, '1', '', '0', 1278419894, 'page5,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img27_13427s.jpg', '/UserFiles/Image/Trial/img27_13427.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 56, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(28, 10, 'ЖК-телевизор SONY KDL-32S2000', 'Диагональ 81 см, 2&#215;10, 1366&#215;768, совместим с HDTV, изображениу DNLe, усилитель LNA+, возможность использования PC.', 'Диагональ 81 см, 2&#215;10, 1366&#215;768, совместим с HDTV, изображениу DNLe, усилитель LNA+, возможность использования PC, матрица типа S-PVA, контраст 1000:1, яркость 500 Кд/м&#178;, интерфейс HDMI, функции &lt;Картинка в картинке&gt; и &lt;Двойной экран&gt;, FM-тюнер, стерео NICAM, звучание SRS TruSurround XT, телетекст: 1000 страниц, эргономичный пульт ДУ', '35000', '0', '0', '1', '1', '', '1', '25,26', '', 'N;', '', 0, '1', '', '0', 1278425888, 'page6,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img28_64148s.jpg', '/UserFiles/Image/Trial/img28_64148.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 55, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(29, 11, 'Цифровой фотоаппарат SONY DSC-W380', 'W380Цифровая компактная фотокамера<br><br>Высокоэффективные функции обработки изображения и функция \r\nSweep Panorama в корпусе различных цветов\r\n\r\n\r\n', '<p>14,1 МП, объектив G f/2,4, 5x оптический зум/широкоуг. значение 24 \r\nмм, видео HD, ЖК-экран 6,7 см</p><ul class=\\\"reasonsToBuy\\\"><li>Снимки чрезвычайно высокого уровня разрешения</li><li>Светосильный объектив позволяет получать превосходный результат\r\n съемки при недостаточном освещении</li><li>Удивительно широкие панорамные снимки</li></ul>\r\n', '10000', '800', '0', '1', '1', '', '1', '31,32', 'i2-96ii10-19ii24-51i', 'a:3:{i:2;a:1:{i:0;s:2:\"96\";}i:10;a:1:{i:0;s:2:\"19\";}i:24;a:1:{i:0;s:2:\"51\";}}', '', 0, '1', '', '0', 1278420925, 'page2,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img29_90265s.jpg', '/UserFiles/Image/Trial/img29_90265.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 67, '130', '0', '0', '0', '0', 'N;', 6, '', ''),
(30, 11, 'Цифровой фотоаппарат SONY DSC-TX7', 'Суперкомпактная и стильная фотокамера с функцией Intelligent Sweep \r\nPanorama и продвинутыми возможностями съемки', '<p>Матрица Exmor R™ CMOS, 4x опт. зум/шир. объектив 25 мм, съемка видео \r\n1080i HD, сенс. ЖК-экран 8,8 см</p><ul class=\\\"reasonsToBuy\\\"><li>Тонкий и стильный корпус с яркими цветовыми решениями</li><li>Удивительно большой сенсорный экран</li><li>Фотографии высокого качества и HD-видео формата 1080i</li></ul>', '9800', '0', '0', '1', '1', '', '1', '31,32', 'i2-1ii10-19ii24-51i', 'a:3:{i:2;a:1:{i:0;s:1:\"1\";}i:10;a:1:{i:0;s:2:\"19\";}i:24;a:1:{i:0;s:2:\"51\";}}', '', 0, '1', '', '0', 1278421265, 'page3,', 1, '', '0', '', '', 'купить фотоаппарат', '1', '', '/UserFiles/Image/Trial/img30_80985s.jpg', '/UserFiles/Image/Trial/img30_80985.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 45, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(31, 11, 'Цифровой фотоаппарат SONY DSLR-A45', 'A450&nbsp;Цифровая зеркальная фотокамера<br><br>Необыкновенное качество снимков, высокая скорость отклика и \r\nмощные функции редактирования', '<p>14.2 МП Exmor™ CMOS, 7 кадр/с*, ЖК-экран 6,9 см, SteadyShot INSIDE. \r\nТолько корпус</p><ul class=\\\"reasonsToBuy\\\"><li>Изображение с низким количеством шумов и высокой детализацией</li><li>Высокоскоростная серийная съемка</li><li>Огромная продолжительность работы от аккумулятора</li></ul>', '7963', '0', '0', '1', '1', '', '1', '29,30', 'i2-1ii10-19ii24-50i', 'a:3:{i:2;a:1:{i:0;s:1:\"1\";}i:10;a:1:{i:0;s:2:\"19\";}i:24;a:1:{i:0;s:2:\"50\";}}', '', 0, '1', '', '0', 1278421835, 'page5,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img31_15570s.jpg', '/UserFiles/Image/Trial/img31_15570.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 29, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(32, 11, 'Цифровой фотоаппарат SONY A290L', 'Цифровая зеркальная фотокамера<br>Очень легкая и компактная камера хорошо лежит в руке и обладает \r\nудобными функциями<br>', 'Очень легкая и компактная камера хорошо лежит в руке и обладает \r\nудобными функциями<p>14,2 МП, 2,5 кадров/с, ЖК-экран 6,9 см, технология SteadyShot \r\nINSIDE, HDMI®. Объектив 18-55 мм.</p><ul class=\\\"reasonsToBuy\\\"><li>Легкая, компактная и удобная в дороге</li><li>Даже новички будут удивлены простотой управления</li><li>Очень высокое качество изображения</li></ul>', '5890', '6000', '0', '1', '1', '', '1', '29,30', 'i2-96ii10-19ii24-92i', 'a:3:{i:2;a:1:{i:0;s:2:\"96\";}i:10;a:1:{i:0;s:2:\"19\";}i:24;a:1:{i:0;s:2:\"92\";}}', '', 0, '1', '', '0', 1278422002, 'page6,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img32_19735s.jpg', '/UserFiles/Image/Trial/img32_19735.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 46, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(33, 12, 'Apple iPod nano 8Гб (5G)', '<strong>iPod nano</strong> – это не просто стильный MP3-плеер всемирно \r\nизвестного бренда. Завораживает своим неповторимым \r\nлаконичным и элегантным дизайном, отличающим всю продукцию компании <strong>Apple</strong>.\r\n', 'Поддержка видео - Видео H.264: до 1,5 Мб/с, 640 х\r\n 480 пикселей, 30 кадров в секунду, профиль Baseline Profile Low \r\nComplexity с аудио AAC-LC до 160 кб/с, 48 кГц, стереозвук в форматах \r\n.m4v, .mp4 и .mov; видео H.264 до 2,5 Мб/с, 640 х 480 пикселей, 30 \r\nкадров в секунду, профиль Baseline Profile Level 3.0 с аудио AAC-LC до \r\n160 кб/с, 48 кГц, стереозвук в форматах .m4v, .mp4 и .mov; видео MPEG-4,\r\n до 2,5 Мб/с, 640 х 480 пикселей, 30 кадров в секунду, профиль Simple \r\nProfile с аудио AAC-LC до 160 кб/с, 48 кГц, стереозвук в форматах .m4v, \r\n.mp4 и .mov.\r\n', '1200', '0', '0', '1', '1', '', '1', '35,36', 'i2-1ii10-19ii28-58i', 'a:3:{i:2;a:1:{i:0;s:1:\"1\";}i:10;a:1:{i:0;s:2:\"19\";}i:28;a:1:{i:0;s:2:\"58\";}}', '', 0, '1', '', '0', 1278422732, 'page2,', 1, '', '0', '', '', 'MP3 плеер DEX', '1', '', '/UserFiles/Image/Trial/img33_15359s.jpg', '/UserFiles/Image/Trial/img33_15359.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 11, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(34, 12, 'iPod classic 160GB', '160 ГБ памяти iPod classic — это 40 000 песен, 200 часов видео или 25 \r\n000 фотографий. Более, чем достаточно, чтобы не скучать месяцами...1 а \r\nможет и всю жизнь!', 'Всё, что нужно, чтобы весело провести время, найдётся в iTunes. Удобно \r\nорганизованная медиатека и музыкальный автомат. Простой способ наполнить\r\n iPod classic музыкой, видео, играми и подкастами.', '1110', '1325', '1', '1', '1', '', '1', '35,36', 'i2-2ii28-58i', 'a:2:{i:2;a:1:{i:0;s:1:\"2\";}i:28;a:1:{i:0;s:2:\"58\";}}', '', 0, '1', '', '0', 1278422714, 'page3,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img34_84429s.jpg', '/UserFiles/Image/Trial/img34_84429.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '', 0, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(35, 12, 'Apple iPod touch 32GB (3G)', 'Ультра-современный, тонкий MP3-плеер, с большим количеством функций, \r\nимеющий большой широкоформатный дисплей. Стильный внешний вид плеера в \r\nстиле минимализма радует глаз элегантным дизайном, характерным для всех \r\nпродуктов от Apple.', '<strong>Apple iPod touch</strong> обладает большим сенсорным экраном с \r\nтехнологией \\\"Multi-touch», благодаря чему вся навигация проводиться при \r\nпомощи легкого прикосновения пальцев к дисплею.<br>\r\n<strong> </strong>Большой дисплей iPod touch идеально подходит для \r\nпросмотра фото, видео и телепередач. Встроенный сенсор автоматически \r\nрегулирует яркость дисплея в зависимости от освещения.', '900', '0', '1', '1', '1', '', '1', '33,34', 'i2-96ii28-59i', 'a:2:{i:2;a:1:{i:0;s:2:\"96\";}i:28;a:1:{i:0;s:2:\"59\";}}', '', 0, '1', '', '0', 1278422899, 'page5,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img35_79801s.jpg', '/UserFiles/Image/Trial/img35_79801.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '34,36', 0, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(36, 12, 'iPod Shuffle', 'В iPod shuffle воплощены последние достижения Apple в области защиты \r\nокружающей среды. Он имеет следующие особенности для снижения \r\nвоздействия на окружающую среду. <br>\r\n', 'Аккумулятор и питание<li>Встроенный литиевый аккумулятор с полимерным электролитом</li><li>Время воспроизведения: до 10 часов при полном заряде \r\nаккумулятора</li><li>Зарядка через USB от компьютера или адаптера питания \r\n(продаётся отдельно)</li><li>зарядка на 80% за 2 часа, полная зарядка за 3 часа.</li>\r\n', '1500', '0', '1', '1', '1', '', '1', '33,34', 'i2-1ii10-19ii28-59i', 'a:3:{i:2;a:1:{i:0;s:1:\"1\";}i:10;a:1:{i:0;s:2:\"19\";}i:28;a:1:{i:0;s:2:\"59\";}}', '', 0, '1', '', '0', 1278423912, 'page6,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img36_11726s.jpg', '/UserFiles/Image/Trial/img36_11726.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '33,34', 0, '0', '0', '0', '0', '0', 'N;', 6, '', ''),
(37, 2, 'Утюг BINATONE SI 2660 W', '<p>Различные режимы парообразования<br>Спрей<br>Различные температурные режимы</p>\r\n\r\n', '<p>Различные режимы парообразования<br>Спрей<br>Различные температурные режимы</p>\r\n\r\n', '700', '850', '0', '1', '1', '', '1', '1,2', 'i23-45ii5-84i', 'a:2:{i:23;a:1:{i:0;s:2:\"45\";}i:5;a:1:{i:0;s:2:\"84\";}}', '', 0, '', '', '0', 1278596999, 'page3,', 1, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img37_11242s.jpg', '/UserFiles/Image/Trial/img37_11242.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '3', 22, '300', '0', '0', '0', '0', 'N;', 6, '', ''),
(38, 3, 'Машинка для стрижки PHILIPS QC 5000', 'Самый простой способ идеально подстричь волосы в домашних условиях. Машинка для стрижки волос Complete Control дает максимальный контроль и прекрасные результаты.\r\n', '<p style=\\\"font-family: Arial;\\\">Самый простой способ идеально подстричь волосы в домашних условиях. Машинка для стрижки волос Complete Control дает максимальный контроль и прекрасные результаты. </p>\r\n', '700', '850', '0', '1', '1', '', '1', '6,7', 'i2-2ii14-98ii23-45i', 'a:3:{i:2;a:1:{i:0;s:1:\"2\";}i:14;a:1:{i:0;s:2:\"98\";}i:23;a:1:{i:0;s:2:\"45\";}}', '0', 0, '1', '', '0', 1278410586, 'page6,', 3, '', '0', '', '', '', '0', '', '/UserFiles/Image/Trial/img38_17545s.jpg', '/UserFiles/Image/Trial/img38_17545.jpg', 'a:4:{s:11:\"bid_enabled\";s:0:\"\";s:3:\"bid\";s:0:\"\";s:12:\"cbid_enabled\";s:0:\"\";s:4:\"cbid\";s:0:\"\";}', '0', '7,8', 49, '450', '0', '0', '0', '0', 'N;', 6, 'шт.', '');

CREATE TABLE `phpshop_rating_categories` (
  `id_category` int(11) NOT NULL auto_increment,
  `ids_dir` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '0',
  `revoting` enum('0','1') default NULL,
  PRIMARY KEY  (`id_category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_rating_categories` VALUES
(1, ',2,,3,,4,,6,,7,,8,,10,,11,,12,', 'Товары', '1', '1');

CREATE TABLE `phpshop_rating_charact` (
  `id_charact` int(11) NOT NULL auto_increment,
  `id_category` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `num` int(11) NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id_charact`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_rating_charact` VALUES
(1, 1, 'Внешний вид', 1, '1'),
(2, 1, 'Функциональность', 2, '1'),
(3, 1, 'Качество', 3, '1');

CREATE TABLE `phpshop_rating_votes` (
  `id_vote` int(11) NOT NULL auto_increment,
  `id_charact` int(11) NOT NULL default '0',
  `id_good` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `userip` varchar(16) NOT NULL default '',
  `rate` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id_vote`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_rssgraber` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `link` text NOT NULL,
  `day_num` int(1) NOT NULL default '1',
  `news_num` mediumint(8) NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '1',
  `start_date` int(16) unsigned NOT NULL default '0',
  `end_date` int(16) unsigned NOT NULL default '0',
  `last_load` int(16) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_rssgraber` VALUES (1, 'http://www.phpshop.ru/rss.php', 1, 10, '1', 1307995200, 1322686800, 1);
    


CREATE TABLE `phpshop_rssgraber_jurnal` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `date` int(15) unsigned NOT NULL default '0',
  `link_id` int(11) NOT NULL default '0',
  `status` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 ;


CREATE TABLE `phpshop_search_base` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `uid` varchar(255) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `phpshop_search_jurnal` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `num` tinyint(32) NOT NULL default '0',
  `datas` varchar(11) NOT NULL default '',
  `dir` varchar(255) NOT NULL default '',
  `cat` tinyint(11) NOT NULL default '0',
  `set` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;


CREATE TABLE `phpshop_servers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `host` varchar(255) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_shopusers` (
  `id` int(64) unsigned NOT NULL auto_increment,
  `login` varchar(64) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `datas` varchar(64) NOT NULL default '',
  `mail` varchar(64) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `company` varchar(255) NOT NULL default '',
  `inn` varchar(64) NOT NULL default '',
  `tel` varchar(64) NOT NULL default '',
  `adres` text NOT NULL,
  `enabled` enum('0','1') NOT NULL default '0',
  `status` varchar(64) NOT NULL default '0',
  `kpp` varchar(64) NOT NULL default '',
  `tel_code` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_shopusers_status` (
  `id` tinyint(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `discount` float NOT NULL default '0',
  `price` enum('1','2','3','4','5') NOT NULL default '1',
  `enabled` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_sort` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `category` int(11) unsigned NOT NULL default '0',
  `num` tinyint(11) NOT NULL default '0',
  `page` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_sort` VALUES
(1, 'Зеленый', 2, 1, ''),
(2, 'Синий', 2, 2, ''),
(4, '2000', 3, 0, ''),
(7, 'Есть', 4, 1, ''),
(8, 'Нет', 4, 2, ''),
(9, 'Алюминий', 5, 1, ''),
(10, 'Спец. сплав', 5, 2, ''),
(11, 'SONY', 6, 1, ''),
(12, 'PHILIPS', 6, 2, ''),
(13, '32', 7, 1, ''),
(14, '40', 7, 2, ''),
(15, '42', 7, 3, ''),
(16, '1 л', 9, 1, ''),
(17, '1.5 л', 9, 2, ''),
(18, '2 л', 9, 3, ''),
(19, '1500 Вт', 10, 1, ''),
(20, '2000 Вт', 10, 2, ''),
(22, 'Сеть', 12, 1, ''),
(23, 'аккумулятор', 12, 2, ''),
(30, '750 Вт', 15, 2, ''),
(29, '700 Вт', 15, 1, ''),
(26, 'DELONGHI', 14, 0, ''),
(27, 'MOULINEX ', 14, 0, ''),
(28, 'SAMSUNG', 14, 0, ''),
(31, '850 Вт', 15, 3, ''),
(32, 'Механический', 16, 1, ''),
(33, 'Электронный', 16, 2, ''),
(34, '20 л', 17, 1, ''),
(35, '22 л', 17, 2, ''),
(36, '23 л', 17, 3, ''),
(37, 'KENWOOD', 14, 0, ''),
(38, 'CANDY ', 14, 0, ''),
(39, 'WHIRLPOOL', 14, 0, ''),
(40, 'Фронтальная', 19, 1, ''),
(41, 'Вертикальная', 19, 2, ''),
(42, '3.5 кг', 21, 1, ''),
(43, '4 кг', 21, 2, ''),
(44, '5 кг', 21, 3, ''),
(45, '480 Вт', 23, 2, ''),
(46, '610 Вт', 23, 3, ''),
(47, '260 Вт', 23, 1, ''),
(48, 'Есть', 25, 1, ''),
(49, 'Нет', 25, 2, ''),
(50, '6.0', 24, 1, ''),
(51, '7.2', 24, 2, ''),
(52, 'Есть', 27, 1, ''),
(53, 'Нет', 27, 2, ''),
(54, 'White|Black', 29, 2, ''),
(56, 'Red|Silver', 29, 3, ''),
(57, 'Black|Silver', 29, 4, ''),
(58, '1 Gb', 28, 1, ''),
(59, '2 Gb', 28, 2, ''),
(96, 'красный', 2, 0, ''),
(65, '5000', 3, 0, ''),
(66, '4000', 3, 0, ''),
(67, '12', 7, 0, ''),
(68, '55', 7, 0, ''),
(101, '6 л', 9, 0, ''),
(72, '123', 24, 0, ''),
(92, '22', 24, 0, ''),
(100, 'вес', 23, 0, ''),
(91, '12', 24, 0, ''),
(76, 'оилиори', 12, 0, ''),
(79, 'бренд', 12, 0, ''),
(82, 'размер', 12, 0, ''),
(99, '132', 10, 0, ''),
(84, 'Смола', 5, 0, ''),
(98, 'PHILIPS', 14, 0, '');

CREATE TABLE `phpshop_sort_categories` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `flag` enum('0','1') NOT NULL default '0',
  `num` tinyint(11) NOT NULL default '0',
  `category` int(11) NOT NULL default '-1',
  `filtr` enum('0','1') NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  `goodoption` enum('0','1') NOT NULL,
  `optionname` enum('0','1') NOT NULL,
  `page` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_sort_categories` VALUES
(1, 'Утюги', '0', 1, 0, '0', 'Цвета утюгов', '0', '0', ''),
(2, 'Выбор цвета', '1', 0, 13, '1', 'Цвет утюга', '', '', ''),
(4, 'Турбопар', '', 0, 1, '', 'Наличие турбопара', '1', '1', ''),
(5, 'Материал подошвы', '1', 0, 1, '1', '', '', '', ''),
(6, 'Бренд', '1', 0, 26, '1', '', '', '', ''),
(7, 'Размер экрана', '', 0, 8, '', '', '1', '1', ''),
(8, 'Телевизоры', '0', 0, 0, '0', '', '0', '0', ''),
(9, 'Объём', '1', 0, 20, '1', '', '', '', ''),
(10, 'Выбор мощности', '1', 0, 22, '1', '', '', '', ''),
(11, 'Чайники', '0', 0, 0, '0', '', '0', '0', ''),
(13, 'Машинки для стрижки', '0', 0, 0, '0', '', '0', '0', ''),
(14, 'Производитель', '1', 0, 13, '1', '', '', '', ''),
(15, 'Мощность микроволн', '1', 0, 18, '1', '', '', '', ''),
(16, 'Тип управления', '', 0, 13, '', '', '1', '1', ''),
(17, 'Объём', '1', 1, 13, '', 'для товарных опций', '1', '1', ''),
(18, 'Микроволновые печи', '0', 0, 0, '0', '', '0', '0', ''),
(19, 'Тип загрузки', '1', 0, 20, '1', '', '', '', ''),
(20, 'Стиральные машины', '0', 0, 0, '0', '', '0', '0', ''),
(21, 'Загрузка белья', '1', 0, 20, '1', '', '', '', ''),
(22, 'Хлебопечки', '0', 0, 0, '0', '', '0', '0', ''),
(23, 'Мощность', '1', 0, 13, '1', '', '', '', ''),
(24, 'Кол-во мегапикселей', '1', 0, 26, '1', '', '', '', ''),
(27, 'Стабилизатор изображения', '', 0, 26, '', '', '1', '1', ''),
(26, 'Фотоаппараты', '0', 0, 0, '0', '', '0', '0', ''),
(28, 'Встроенная память', '1', 1, 26, '1', '', '', '', ''),
(29, 'Цвет', '1', 0, 13, '', '', '1', '1', ''),
(31, 'Цвет ', '0', 0, 0, '0', 'опция цвет ', '0', '0', ''),
(32, 'Выбор', '0', 10, 0, '0', '', '0', '0', '');


CREATE TABLE `phpshop_system` (
  `id` int(32) NOT NULL auto_increment,
  `name` text,
  `company` text,
  `num_row` int(10) default NULL,
  `num_row_adm` int(10) default NULL,
  `dengi` tinyint(11) default NULL,
  `percent` varchar(16) NOT NULL default '',
  `skin` varchar(32) default NULL,
  `adminmail2` varchar(64) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `kurs` float NOT NULL default '0',
  `spec_num` tinyint(5) NOT NULL default '0',
  `new_num` tinyint(11) NOT NULL default '0',
  `tel` text NOT NULL,
  `bank` blob NOT NULL,
  `num_vitrina` enum('1','2','3') NOT NULL default '3',
  `width_icon` varchar(11) NOT NULL default '',
  `updateU` varchar(32) NOT NULL default '',
  `nds` varchar(64) NOT NULL default '',
  `nds_enabled` enum('0','1') NOT NULL default '1',
  `admoption` blob NOT NULL,
  `kurs_beznal` tinyint(11) NOT NULL default '0',
  `descrip` varchar(255) NOT NULL default '',
  `descrip_shablon` varchar(255) NOT NULL default '',
  `title_shablon` varchar(255) NOT NULL default '',
  `keywords_shablon` varchar(255) NOT NULL default '',
  `title_shablon2` varchar(255) NOT NULL default '',
  `descrip_shablon2` varchar(255) NOT NULL default '',
  `keywords_shablon2` varchar(255) NOT NULL default '',
  `logo` varchar(255) NOT NULL default '',
  `promotext` text NOT NULL,
  `title_shablon3` varchar(255) NOT NULL default '',
  `descrip_shablon3` varchar(255) NOT NULL default '',
  `keywords_shablon3` varchar(255) NOT NULL default '',
  `rss_use` int(1) unsigned NOT NULL default '1',
  `1c_load_accounts` enum('0','1') NOT NULL default '1',
  `1c_load_invoice` enum('0','1') NOT NULL default '1',
  `1c_option` blob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_system` VALUES
(1, 'Интернет-магазин Ваше Имя', 'Ваше Имя', 10, 2, 6, '0', 'phpshop_7', 'mail@mail.ru', 'Купить технику с бесплатной доставкой', 'детские, товары, купить, бесплатная, доставка, MP3, проигрыватели, автомобильная, электроника, бытовая, техника, аудио, видео, фото, холодильники, телевизоры, плазменные, панели, DEX, SONY, PHILIPS, TEFAL, ROWENTA, SAMSUNG, KRUPS, BINATONE, KENWOOD', 6, 4, 4, '(495) 105-05-50', 'a:9:{s:8:\"org_name\";s:11:\"ООО МАГАЗИН\";s:12:\"org_ur_adres\";s:6:\"Москва\";s:9:\"org_adres\";s:6:\"Москва\";s:7:\"org_inn\";s:0:\"\";s:7:\"org_kpp\";s:0:\"\";s:9:\"org_schet\";s:0:\"\";s:8:\"org_bank\";s:0:\"\";s:7:\"org_bic\";s:0:\"\";s:14:\"org_bank_schet\";s:0:\"\";}', '2', '25', '1278426076', '18', '1', 'a:47:{s:17:\"prevpanel_enabled\";s:1:\"1\";s:12:\"sklad_status\";s:1:\"3\";s:14:\"helper_enabled\";s:1:\"1\";s:13:\"cloud_enabled\";s:1:\"1\";s:23:\"digital_product_enabled\";N;s:13:\"user_calendar\";s:1:\"1\";s:19:\"user_price_activate\";N;s:22:\"user_mail_activate_pre\";N;s:18:\"rss_graber_enabled\";s:1:\"1\";s:17:\"image_save_source\";s:1:\"1\";s:6:\"img_wm\";N;s:5:\"img_w\";s:3:\"300\";s:5:\"img_h\";s:3:\"300\";s:6:\"img_tw\";s:3:\"100\";s:6:\"img_th\";s:3:\"100\";s:14:\"width_podrobno\";s:2:\"90\";s:12:\"width_kratko\";s:2:\"90\";s:15:\"message_enabled\";s:1:\"1\";s:12:\"message_time\";s:2:\"20\";s:15:\"desktop_enabled\";N;s:12:\"desktop_time\";N;s:8:\"oplata_1\";s:1:\"1\";s:8:\"oplata_2\";s:1:\"1\";s:8:\"oplata_3\";s:1:\"1\";s:8:\"oplata_4\";N;s:8:\"oplata_5\";s:1:\"1\";s:8:\"oplata_6\";s:1:\"1\";s:8:\"oplata_7\";s:1:\"1\";s:8:\"oplata_8\";s:1:\"1\";s:14:\"seller_enabled\";N;s:12:\"base_enabled\";N;s:11:\"sms_enabled\";N;s:14:\"notice_enabled\";N;s:14:\"update_enabled\";N;s:7:\"base_id\";s:0:\"\";s:9:\"base_host\";s:0:\"\";s:4:\"lang\";s:7:\"russian\";s:13:\"sklad_enabled\";s:1:\"1\";s:10:\"price_znak\";s:1:\"0\";s:18:\"user_mail_activate\";N;s:11:\"user_status\";s:1:\"0\";s:9:\"user_skin\";s:1:\"1\";s:12:\"cart_minimum\";s:4:\"5000\";s:14:\"editor_enabled\";s:1:\"1\";s:13:\"watermark_big\";a:21:{s:14:\"big_mergeLevel\";i:70;s:11:\"big_enabled\";s:1:\"1\";s:8:\"big_type\";s:3:\"png\";s:12:\"big_png_file\";s:30:\"/UserFiles/Image/shop_logo.png\";s:12:\"big_copyFlag\";s:1:\"0\";s:6:\"big_sm\";i:0;s:16:\"big_positionFlag\";s:1:\"4\";s:13:\"big_positionX\";i:0;s:13:\"big_positionY\";i:0;s:9:\"big_alpha\";i:70;s:8:\"big_text\";s:0:\"\";s:21:\"big_text_positionFlag\";i:0;s:8:\"big_size\";i:0;s:9:\"big_angle\";i:0;s:18:\"big_text_positionX\";i:0;s:18:\"big_text_positionY\";i:0;s:10:\"big_colorR\";i:0;s:10:\"big_colorG\";i:0;s:10:\"big_colorB\";i:0;s:14:\"big_text_alpha\";i:0;s:8:\"big_font\";s:16:\"norobot_font.ttf\";}s:15:\"watermark_small\";a:21:{s:16:\"small_mergeLevel\";i:100;s:13:\"small_enabled\";s:1:\"1\";s:10:\"small_type\";s:3:\"png\";s:14:\"small_png_file\";s:25:\"/UserFiles/Image/logo.png\";s:14:\"small_copyFlag\";s:1:\"0\";s:8:\"small_sm\";i:0;s:18:\"small_positionFlag\";s:1:\"1\";s:15:\"small_positionX\";i:0;s:15:\"small_positionY\";i:0;s:11:\"small_alpha\";i:50;s:10:\"small_text\";s:0:\"\";s:23:\"small_text_positionFlag\";i:0;s:10:\"small_size\";i:0;s:11:\"small_angle\";i:0;s:20:\"small_text_positionX\";i:0;s:20:\"small_text_positionY\";i:0;s:12:\"small_colorR\";i:0;s:12:\"small_colorG\";i:0;s:12:\"small_colorB\";i:0;s:16:\"small_text_alpha\";i:0;s:10:\"small_font\";s:16:\"norobot_font.ttf\";}s:15:\"watermark_ishod\";a:21:{s:16:\"ishod_mergeLevel\";i:100;s:13:\"ishod_enabled\";N;s:10:\"ishod_type\";s:3:\"png\";s:14:\"ishod_png_file\";s:0:\"\";s:14:\"ishod_copyFlag\";s:1:\"0\";s:8:\"ishod_sm\";i:0;s:18:\"ishod_positionFlag\";s:1:\"1\";s:15:\"ishod_positionX\";i:0;s:15:\"ishod_positionY\";i:0;s:11:\"ishod_alpha\";i:0;s:10:\"ishod_text\";s:0:\"\";s:23:\"ishod_text_positionFlag\";i:0;s:10:\"ishod_size\";i:0;s:11:\"ishod_angle\";i:0;s:20:\"ishod_text_positionX\";i:0;s:20:\"ishod_text_positionY\";i:0;s:12:\"ishod_colorR\";i:0;s:12:\"ishod_colorG\";i:0;s:12:\"ishod_colorB\";i:0;s:16:\"ishod_text_alpha\";i:0;s:10:\"ishod_font\";s:16:\"norobot_font.ttf\";}}', 6, 'PHPShop – это готовое решение для быстрого создания Интернет магазина.', '@Podcatalog@, @Catalog@, @System@', '@Podcatalog@ - @Catalog@ - @System@', '@Podcatalog@, @Catalog@, @Generator@', '@Product@ - @Podcatalog@ - @Catalog@', '@Product@, @Podcatalog@, @Catalog@', '@Product@,@System@', '/UserFiles/Image/shop_logo.png', '', '@Catalog@ /', '@Catalog@', '@Catalog@', 0, '', '','');

CREATE TABLE `phpshop_upload_backup` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `date` int(16) NOT NULL default '0',
  `folder` varchar(255) NOT NULL default '',
  `backup_use` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

CREATE TABLE `phpshop_upload_list` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `backup` enum('1','0') NOT NULL default '0',
  `backup_flag` enum('0','1','2','3') NOT NULL default '0',
  `dir` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `phpshop_users` (
  `id` int(11) NOT NULL auto_increment,
  `status` blob NOT NULL,
  `login` varchar(64) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `mail` varchar(64) NOT NULL default '',
  `enabled` enum('0','1') NOT NULL default '0',
  `content` text NOT NULL,
  `skin` varchar(255) NOT NULL default '',
  `skin_enabled` enum('0','1') NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `name_enabled` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;


CREATE TABLE `phpshop_valuta` (
  `id` tinyint(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `code` varchar(64) NOT NULL default '',
  `iso` varchar(64) NOT NULL default '',
  `kurs` varchar(64) NOT NULL default '0',
  `num` tinyint(11) NOT NULL default '0',
  `enabled` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_valuta` VALUES (4, 'Гривны', 'гр.', 'UAU', '0.06', 4, '1');
INSERT INTO `phpshop_valuta` VALUES (5, 'Доллары', '$', 'USD', '0.03', 0, '1');
INSERT INTO `phpshop_valuta` VALUES (6, 'Рубли', 'руб', 'RUR', '1', 1, '1');

