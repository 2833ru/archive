
DROP TABLE IF EXISTS `phpshop_modules_sms_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_sms_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `title` text NOT NULL,
  `title_end` text NOT NULL,
  `link` varchar(64) NOT NULL,
  `merchant_id` varchar(64) NOT NULL DEFAULT '',
  `merchant_pwd` varchar(64) NOT NULL DEFAULT '',
  `merchant_sig` varchar(64) NOT NULL DEFAULT '',
  `sandbox` enum('1','2') NOT NULL DEFAULT '2',
  `logo_enabled` enum('1','2','3') NOT NULL DEFAULT '1',
  `name` varchar(64) NOT NULL,
  `serial` varchar(64) NOT NULL DEFAULT '',
  `version` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_paypal_system` (`id`, `status`, `title`, `title_end`, `link`, `merchant_id`, `merchant_pwd`, `merchant_sig`, `sandbox`, `logo_enabled`, `message_header`, `message`, `currency_id`, `serial`, `version`) VALUES
(1, 0, 'Платежная система PayPal', 'Форма оплаты будет доступна после ручной обработки заказа менеджером. Просим немного подождать.', 'Оплатить через PayPal', '', '', '', '1', '1', 'Спасибо за покупку', 'Ваш заказ оплачен', 6, '', 1);
