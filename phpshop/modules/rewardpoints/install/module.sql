ALTER TABLE  `phpshop_products` ADD  `point` INT NOT NULL AFTER  `price5` ;
ALTER TABLE  `phpshop_products` ADD `check_pay` ENUM('0','1') NOT NULL AFTER `point`;
ALTER TABLE  `phpshop_shopusers` ADD  `point` INT NOT NULL AFTER  `status` ;
ALTER TABLE  `phpshop_valuta` ADD  `price_point` VARCHAR( 255 ) NOT NULL AFTER  `enabled` ;

DROP TABLE IF EXISTS `phpshop_modules_rewardpoints_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_rewardpoints_system` (
  `percent` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `daysInterval` int(11) NOT NULL,
  `status_order` int(11) NOT NULL,
  `percent_add` int(11) NOT NULL,
  `serial` varchar(64) NOT NULL default '',
  `status_order_null` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_rewardpoints_system` (`percent`, `days`) VALUES
(50, 90);

CREATE TABLE IF NOT EXISTS `phpshop_modules_rewardpoints_users_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_users` int(11) NOT NULL,
  `operation` enum('0','1') NOT NULL COMMENT '0 списание, 1 - начисление',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `number_points` int(11) NOT NULL,
  `balance_points` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `sum_orders` varchar(255) NOT NULL,
  `type` enum('0','1','2') NOT NULL COMMENT '0 -покупатель, 1 - администратор, 2 - робот',
  `comment_admin` text NOT NULL,
  `confirmation` enum('0','1','2') NOT NULL,
  `cron` enum('0','1') NOT NULL,
  `check_pay` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;