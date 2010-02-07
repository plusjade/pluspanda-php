

ALTER TABLE  `sites` DROP  `subdomain` ,
DROP  `custom_domain` ,
DROP  `homepage` ;

-- add tstml meta data for public form.
ALTER TABLE  `sites` ADD  `tstml` TEXT NOT NULL AFTER  `theme` ,
ADD  `tstml_msg` TEXT NOT NULL AFTER  `tstml`;


ALTER TABLE  `patrons` ADD  `meta` VARCHAR( 255 ) NOT NULL AFTER  `id`;

-- add locking
ALTER TABLE  `testimonials` ADD  `lock` INT( 1 ) NOT NULL;


ALTER TABLE  `sites` ADD  `per_page` INT( 2 ) NOT NULL AFTER  `theme`;
ALTER TABLE  `sites` DROP  `name`;
ALTER TABLE  `sites` ADD  `sort` VARCHAR( 55 ) NOT NULL AFTER  `theme`;
ALTER TABLE  `testimonials` DROP  `requests`;


UPDATE `version` SET  `at` =5;
