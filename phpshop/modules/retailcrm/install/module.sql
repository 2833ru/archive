DROP TABLE IF EXISTS `phpshop_modules_retailcrm_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_retailcrm_system` (
  `code` varchar(64) NOT NULL default '',
  `value` text NOT NULL,
  `version` varchar(64) DEFAULT '3.2'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_retailcrm_system` VALUES ('options', '{"status":"0","email":"integration@retailcrm.ru"}', '3.3');

CREATE TABLE IF NOT EXISTS `phpshop_modules_retailcrm_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `message` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

ALTER TABLE `phpshop_orders` ADD `retail_status` enum('1','2') DEFAULT '1';