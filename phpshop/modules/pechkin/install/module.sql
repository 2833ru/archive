DROP TABLE IF EXISTS `phpshop_modules_pechkin_autoload`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_pechkin_autoload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `base_name` text NOT NULL,
  `where` text NOT NULL,
  `param` text NOT NULL,
  `login` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `enabled` enum('0','1') NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;