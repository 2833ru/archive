ALTER TABLE  `phpshop_shopusers_status` ADD  `cumulative_discount_check` INT NOT NULL AFTER  `enabled` ,
ADD  `cumulative_discount` BLOB NOT NULL AFTER  `cumulative_discount_check` ;

ALTER TABLE  `phpshop_shopusers` ADD  `cumulative_discount` INT NOT NULL AFTER  `data_adres` ;
ALTER TABLE  `phpshop_order_status` ADD  `cumulative_action` ENUM(  '0',  '1' ) NOT NULL AFTER  `sklad_action` ;
