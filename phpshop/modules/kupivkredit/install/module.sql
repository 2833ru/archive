ALTER TABLE `phpshop_products` ADD `kvk_promo` varchar(20) NOT NULL default '';
ALTER TABLE `phpshop_products` ADD `kvk_enabled` enum('0','1') NOT NULL default '0';

DROP TABLE IF EXISTS `phpshop_modules_kupivkredit_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_kupivkredit_system` (
  `id` int(11) NOT NULL auto_increment,
  `shop_id` varchar(50) NOT NULL default '',
  `showcase_id` varchar(50) NOT NULL default '',
  `dev_mode` enum('0','1') NOT NULL default '0',
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_kupivkredit_system` (`id`, `shop_id`, `showcase_id`, `dev_mode`, `version`) VALUES
(1, '', '', '1', 1.0);

INSERT INTO `phpshop_payment_systems` (`id`, `name`, `path`, `enabled`, `num`, `message`, `message_header`, `yur_data_flag`, `icon`) VALUES
(10044, 'TinkoffCredit', 'modules', '0', 0, '', '', '', 'phpshop/modules/kupivkredit/templates/tinkoff.png');

