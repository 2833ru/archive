ALTER TABLE `phpshop_news`
  DROP `meta_title`,
  DROP `meta_keywords`,
  DROP `meta_description`;

ALTER TABLE  `phpshop_news` ADD  `meta_title` VARCHAR( 255 ) NOT NULL AFTER  `datau` ,
ADD  `meta_keywords` TEXT NOT NULL AFTER  `meta_title` ,
ADD  `meta_description` TEXT NOT NULL AFTER  `meta_keywords` ;
