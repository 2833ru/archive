DROP TABLE IF EXISTS `phpshop_modules_mandarinhosted_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_mandarinhosted_system` (
  `id` int(11) NOT NULL auto_increment,
  `merchant_key` varchar(64) NOT NULL default '',
  `merchant_skey` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_mandarinhosted_system` VALUES (1,'','');

INSERT INTO `phpshop_payment_systems` (`id`, `name`, `path`, `enabled`, `num`, `message`, `message_header`, `yur_data_flag`, `icon`) VALUES
(10027, 'MandarinPay', 'modules', '0', 0, '<p>Ваш заказ оплачен!</p>', 'Спасибо', '', '/UserFiles/Image/Payments/mandarin_logo.png');
