ALTER TABLE `phpshop_modules_pochta_system` ADD `widget_id` int(11) DEFAULT null;
ALTER TABLE `phpshop_modules_pochta_system` ADD `courier_widget_id` int(11) DEFAULT null;
ALTER TABLE `phpshop_modules_pochta_system` DROP  `fee`;
ALTER TABLE `phpshop_modules_pochta_system` DROP  `fee_type`;