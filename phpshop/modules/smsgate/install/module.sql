CREATE TABLE IF NOT EXISTS `smsgate_modules_sms_message` (
  `id` int(11) NOT NULL auto_increment,
  `domen_api` varchar(50) NOT NULL default '',
  `login_api` varchar(100) NOT NULL default '',
  `password_api` varchar(100) NOT NULL default '',
  `admin_phone` varchar(255) NOT NULL default '',
  `sender` varchar(50) NOT NULL default '',
  `order_template_sms` text NOT NULL default '',
  `done_order_template_sms` text NOT NULL default '',
  `order_template_admin_sms` text NOT NULL default '',
  `change_status_order_template_sms` text NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `smsgate_modules_sms_message` VALUES (1,'phpshop4.incore1.ru','','','','INCORE','','','','');