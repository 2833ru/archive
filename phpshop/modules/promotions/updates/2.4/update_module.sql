ALTER TABLE `phpshop_modules_promotions_forms` ADD `statuses` text NOT NULL DEFAULT '';
ALTER TABLE `phpshop_modules_promotions_forms` ADD `status_check` enum('0','1') NOT NULL DEFAULT '0';
UPDATE `phpshop_modules_promotions_system` SET `version` = '2.4' WHERE `id` = 1