

DROP TABLE IF EXISTS `phpshop_modules_netpay_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_netpay_system` (
  `id` int(11) NOT NULL auto_increment,
  `status` int(11) NOT NULL,
  `work` enum('1','0') NOT NULL default '0',
  `apikey` varchar(64) NOT NULL default '',
  `auth` varchar(64) NOT NULL default '',
  `title` text NOT NULL,
  `title_sub` text NOT NULL,  
  `autosubmit` enum('1','2') NOT NULL default '1',
  `expiredtime` int(11) NOT NULL,
  `status_paid` int(11) NOT NULL,
  `status_refund` int(11) NOT NULL,
  `online_bill` enum('1','0') NOT NULL default '0',
  `inn` varchar(10) NOT NULL default '',
  `tax` varchar(10) NOT NULL default 'none',
  `hold` enum('1','0') NOT NULL default '0',
  `status_hold` int(11) NOT NULL,
  `version` FLOAT(2) DEFAULT '1.1' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_netpay_system` VALUES (1,0, 0, '', '', 'Для оплаты банковской картой Вы будете перенаправлены на защищенную платежную страницу процессинговой компании <a href="http://net2pay.ru" target=_blank>ООО ”Нэт Пэй”</a>. Страница оплаты отвечает последним международным требованиям безопасности платежных систем Visa и MasterCard.','Заказ проверяется. После уточнения деталей заказа будет доступна кнопка для онлайн-оплаты','1','15','2','1','0','','none','0', 0, '1.1');

INSERT INTO `phpshop_payment_systems` (`id`, `name`, `path`, `enabled`, `num`, `message`, `message_header`, `yur_data_flag`, `icon`) VALUES
(10017, 'Оплата картой без комиссии (VISA, Visa Electron, Maestro, MasterCard, МИР)', 'modules', '0', 0, '<p>Ваш заказ оплачен!</p>', 'Спасибо', '', 'phpshop/modules/netpay/payment/logo.png');