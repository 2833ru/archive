

DROP TABLE IF EXISTS `phpshop_modules_chat_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_chat_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1','2') default '1',
  `title` varchar(64) default '',
  `title_start` text,
  `title_end` text,
  `serial` varchar(64) default '',
  `windows` enum('0','1') default '0',
  `operator` enum('1','2') default '1',
  `skin` varchar(32) default '',
  `upload_dir` varchar(64) default '',
  `chmod` varchar(64) default '0775',
  `version` varchar(64)DEFAULT '1.0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_chat_system` VALUES (1,'1','Чат','Чем Вам помочь?','Оператора нет на месте...','','0','2','default','','0775','1.8');

DROP TABLE IF EXISTS `phpshop_modules_chat_users`;
CREATE TABLE `phpshop_modules_chat_users` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `name` varchar(64) default '',
  `status` enum('1','2') default '1',
  `ip` varchar(64) default '',
  `user_session` varchar(255) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS `phpshop_modules_chat_jurnal`;
CREATE TABLE `phpshop_modules_chat_jurnal` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `user_session` varchar(255) default '',
  `name` varchar(64) default '',
  `content` text,
  `avatar` varchar(255) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE IF EXISTS `phpshop_modules_chat_operators`;
CREATE TABLE `phpshop_modules_chat_operators` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `name` varchar(64) default '',
  `status` enum('1','2') default '1',
  `user_session` varchar(255) default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;