
DROP TABLE IF EXISTS `phpshop_modules_yandexkassa_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexkassa_system` (
  `id` int(11) NOT NULL auto_increment,
  `status` int(11) NOT NULL,
  `title` text NOT NULL,
  `title_end` text NOT NULL,
  `merchant_id` varchar(64) NOT NULL default '',
  `merchant_scid` varchar(64) NOT NULL default '',
  `merchant_sig` varchar(64) NOT NULL default '',
  `pay_variants` blob NOT NULL,
  `serial` varchar(64) NOT NULL default '',
  `version` varchar(64) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_yandexkassa_system` (`id`, `status`, `title`, `title_end`, `merchant_id`, `merchant_scid`, `merchant_sig`, `pay_variants`, `serial`, `version`) VALUES
(1, 0, 'Оплатить сейчас', 'Оплатите пожалуйста свой заказ', '', '', '', 0x613a333a7b693a303b733a323a225043223b693a313b733a323a224143223b693a323b733a323a224750223b7d, '', 1.2);

ALTER TABLE  `phpshop_modules_yandexkassa_system` ADD  `test` ENUM(  '0',  '1' ) NOT NULL DEFAULT  '0' AFTER  `pay_variants` ;

INSERT INTO `phpshop_payment_systems` (`id`, `name`, `path`, `enabled`, `num`, `message`, `message_header`, `yur_data_flag`, `icon`) VALUES
(10004, 'Яндекс.Касса', 'modules', '0', 0, '', '', '', '/UserFiles/Image/Payments/yandex-money.png');

CREATE TABLE IF NOT EXISTS `phpshop_modules_yandexkassa_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;