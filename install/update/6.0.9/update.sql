ALTER TABLE `phpshop_order_status` ADD `num` INT(11) DEFAULT '0';
ALTER TABLE `phpshop_orders` ADD `date` INT(11) DEFAULT '0';

CREATE TABLE `phpshop_notes` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default '0',
  `message` text ,
  `status` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;