DROP TABLE IF EXISTS `phpshop_modules_multilanguages`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_multilanguages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `prefix` varchar(255) NOT NULL default '',
  `icon` text NOT NULL,
  `enabled` enum('0','1') NOT NULL default '1',
  `num` TINYINT(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

ALTER TABLE  `phpshop_products` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_categories` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_order_status` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_shopusers_status` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_page_categories` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_page` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_photo` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_photo_categories` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_menu` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_news` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_slider` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_sort_categories` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_sort` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_valuta` ADD  `multilanguages` LONGTEXT NOT NULL;
ALTER TABLE  `phpshop_payment_systems` ADD  `multilanguages` LONGTEXT NOT NULL;
