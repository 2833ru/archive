
DROP TABLE IF EXISTS `ddelivery_module_system`;
CREATE TABLE IF NOT EXISTS `ddelivery_module_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(64) NOT NULL DEFAULT '',
  `rezhim` varchar(64) NOT NULL DEFAULT '',
  `declared` varchar(64) NOT NULL DEFAULT '',
  `width` varchar(64) NOT NULL DEFAULT '',
  `height` varchar(64) NOT NULL DEFAULT '',
  `api` varchar(120) NOT NULL,
  `length` varchar(64) NOT NULL,
  `weight` varchar(64) NOT NULL,
  `payment` varchar(64) NOT NULL,
  `status` varchar(64) NOT NULL,
  `famile` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `def_width` varchar(64) NOT NULL,
  `def_lenght` varchar(64) NOT NULL,
  `def_height` varchar(64) NOT NULL,
  `def_weight` varchar(64) NOT NULL,
  `pvz_companies` blob NOT NULL,
  `cur_companies` blob NOT NULL,
  `from1` varchar(64) NOT NULL,
  `to1` varchar(64) NOT NULL,
  `method1` varchar(64) NOT NULL,
  `from2` varchar(64) NOT NULL,
  `to2` varchar(64) NOT NULL,
  `method2` varchar(64) NOT NULL,
  `from3` varchar(64) NOT NULL,
  `to3` varchar(64) NOT NULL,
  `method3` varchar(64) NOT NULL,
  `okrugl` varchar(64) NOT NULL,
  `shag` varchar(64) NOT NULL,
  `zabor` varchar(64) NOT NULL,
  `city1` varchar(64) NOT NULL,
  `curprice1` varchar(64) NOT NULL,
  `city2` varchar(64) NOT NULL,
  `curprice2` varchar(64) NOT NULL,
  `city3` varchar(64) NOT NULL,
  `curprice3` varchar(64) NOT NULL,
  `custom_point` text NOT NULL,
  `methodval1` varchar(64) NOT NULL,
  `methodval2` varchar(64) NOT NULL,
  `methodval3` varchar(64) NOT NULL,
  `ros_price` varchar(64) NOT NULL,
  `ros_duiring` varchar(64) NOT NULL,
  `self_list` blob NOT NULL,
  `courier_list` blob NOT NULL,
  `settings` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `ddelivery_module_system`
--


INSERT INTO `ddelivery_module_system` (`id`, `type`, `rezhim`, `declared`, `width`, `height`, `api`, `length`, `weight`, `payment`, `status`, `famile`, `name`, `def_width`, `def_lenght`, `def_height`, `def_weight`, `pvz_companies`, `cur_companies`, `from1`, `to1`, `method1`, `from2`, `to2`, `method2`, `from3`, `to3`, `method3`, `okrugl`, `shag`, `zabor`, `city1`, `curprice1`, `city2`, `curprice2`, `city3`, `curprice3`, `custom_point`, `methodval1`, `methodval2`, `methodval3`, `ros_price`, `ros_duiring`, `self_list`, `courier_list`, `settings`) VALUES
(1, '0', '0', '100', 'option1', 'option3', '852af44bafef22e96d8277f3227f0998', 'option2', 'weight', '3', '1', 'famile', 'name', '10', '11', '10', '1', 0x613a333a7b693a303b733a313a2231223b693a313b733a323a223235223b693a323b733a323a223531223b7d, 0x613a333a7b693a303b733a313a2231223b693a313b733a323a223235223b693a323b733a323a223531223b7d, '', '', '1', '', '', '1', '', '', '1', '0', '0.5', '0', '', '', '', '', '', '', '', '', '', '', '', '', 0x4e3b, 0x4e3b, 0x7b2273656c665f776179223a5b223134225d2c22636f75726965725f776179223a5b223134225d7d);


DROP TABLE IF EXISTS `ddelivery_module_cache`;
CREATE TABLE `ddelivery_module_cache` (
                      `id`  int NOT NULL,
                      `data_container`  MEDIUMTEXT NULL ,
                      `expired`  datetime NULL,
                      `filter_company` TEXT NULL,
                      PRIMARY KEY (`id`),
                      INDEX `dd_cache` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ddelivery_module_orders`;
CREATE TABLE `ddelivery_module_orders` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `payment_variant` varchar(255) DEFAULT NULL,
                            `shop_refnum` varchar(255) DEFAULT NULL,
                            `local_status` varchar(255) DEFAULT NULL,
                            `dd_status` int(11) DEFAULT NULL,
                            `type` int(11) DEFAULT NULL,
                            `to_city` int(11) DEFAULT NULL,
                            `point_id` int(11) DEFAULT NULL,
                            `date` datetime DEFAULT NULL,
                            `ddeliveryorder_id` int(11) DEFAULT NULL,
                            `delivery_company` int(11) DEFAULT NULL,
                            `order_info` text DEFAULT NULL,
                            `cache` text DEFAULT NULL,
                            `point` text DEFAULT NULL,
                            `add_field1` varchar(255) DEFAULT NULL,
                            `add_field2` varchar(255) DEFAULT NULL,
                            `add_field3` varchar(255) DEFAULT NULL,
                            `cart` text DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `phpshop_products` ADD `option1` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_products` ADD `option2` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_products` ADD `option3` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_products` ADD `option4` VARCHAR(255) NOT NULL;

ALTER TABLE `phpshop_delivery` ADD `is_mod` enum('1','2') DEFAULT '1';