ALTER TABLE `phpshop_modules_avito_system` ADD `preview_description_template` text default null;
ALTER TABLE `phpshop_modules_avito_system` DROP `additional_description`;
ALTER TABLE `phpshop_modules_avito_system` DROP `use_params`;
ALTER TABLE `phpshop_modules_avito_system` ADD `image_url` varchar(255) default '';