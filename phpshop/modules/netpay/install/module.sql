

DROP TABLE IF EXISTS `phpshop_modules_netpay_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_netpay_system` (
  `id` int(11) NOT NULL auto_increment,
  `status` int(11) NOT NULL,
  `title` text NOT NULL,
  `title_sub` text NOT NULL,
  `merchant_key` varchar(64) NOT NULL default '',
  `merchant_skey` varchar(64) NOT NULL default '',
  `autosubmit` enum('1','2') NOT NULL default '1',
  `expiredtime` int(11) NOT NULL,
  `hold` enum('1','2') default '1',
  `version` varchar(64) DEFAULT '1.1' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_netpay_system` VALUES (1,0,'Оплатите пожалуйста свой заказ','Заказ находится на ручной проверке.','','','1','15','1','1.1');

INSERT INTO `phpshop_payment_systems` VALUES (10017, 'Visa, Mastercard, Yandex (NetPay)', 'modules', '0', 0, '', '', '', '/UserFiles/Image/Payments/visa.png');