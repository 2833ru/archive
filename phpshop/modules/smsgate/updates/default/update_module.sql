ALTER TABLE `smsgate_modules_sms_message` ADD `cascade_domen_api` VARCHAR(50) NOT NULL DEFAULT 'phpshop5.incore1.ru' AFTER `change_status_order_template_sms`;
ALTER TABLE `smsgate_modules_sms_message` ADD `cascade_sender` VARCHAR(50) NOT NULL DEFAULT 'Com-info' AFTER `cascade_domen_api`;
ALTER TABLE `smsgate_modules_sms_message` ADD `cascade_enabled` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `cascade_sender`;
ALTER TABLE `smsgate_modules_sms_message` ADD `order_template_viber` TEXT NOT NULL DEFAULT '' AFTER `cascade_enabled`;
ALTER TABLE `smsgate_modules_sms_message` ADD `order_template_viber_button_text` TEXT NOT NULL DEFAULT '' AFTER `order_template_viber`;
ALTER TABLE `smsgate_modules_sms_message` ADD `order_template_viber_button_url` TEXT NOT NULL DEFAULT '' AFTER `order_template_viber_button_text`;
ALTER TABLE `smsgate_modules_sms_message` ADD `order_template_viber_image_url` TEXT NOT NULL DEFAULT '' AFTER `order_template_viber_button_url`;
ALTER TABLE `smsgate_modules_sms_message` ADD `order_template_admin_viber` TEXT NOT NULL DEFAULT '' AFTER `order_template_viber_image_url`;
ALTER TABLE `smsgate_modules_sms_message` ADD `order_template_admin_viber_button_text` TEXT NOT NULL DEFAULT '' AFTER `order_template_admin_viber`;
ALTER TABLE `smsgate_modules_sms_message` ADD `order_template_admin_viber_button_url` TEXT NOT NULL DEFAULT '' AFTER `order_template_admin_viber_button_text`;
ALTER TABLE `smsgate_modules_sms_message` ADD `order_template_admin_viber_image_url` TEXT NOT NULL DEFAULT '' AFTER `order_template_admin_viber_button_url`;
ALTER TABLE `smsgate_modules_sms_message` ADD `change_status_order_template_viber` TEXT NOT NULL DEFAULT '' AFTER `order_template_admin_viber_image_url`;
ALTER TABLE `smsgate_modules_sms_message` ADD `change_status_order_template_viber_button_text` TEXT NOT NULL DEFAULT '' AFTER `change_status_order_template_viber`;
ALTER TABLE `smsgate_modules_sms_message` ADD `change_status_order_template_viber_button_url` TEXT NOT NULL DEFAULT '' AFTER `change_status_order_template_viber_button_text`;
ALTER TABLE `smsgate_modules_sms_message` ADD `change_status_order_template_viber_image_url` TEXT NOT NULL DEFAULT '' AFTER `change_status_order_template_viber_button_url`;
ALTER TABLE `smsgate_modules_sms_message` ADD `version` FLOAT(2) DEFAULT '2.0' NOT NULL AFTER `change_status_order_template_viber_image_url`;

UPDATE `smsgate_modules_sms_message` SET `cascade_domen_api` = 'phpshop5.incore1.ru', `cascade_sender` = 'Com-info', `cascade_enabled` = '0' WHERE `id` = 1;