/*623*/
ALTER TABLE `phpshop_newsletter` ADD `servers` INT(11) DEFAULT '0';

/*625*/
ALTER TABLE `phpshop_baners` CHANGE `type` `type` ENUM('0','1','2','3') DEFAULT '0';
