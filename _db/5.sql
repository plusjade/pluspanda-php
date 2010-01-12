

ALTER TABLE  `sites` DROP  `subdomain` ,
DROP  `custom_domain` ,
DROP  `homepage` ;

-- add tstml meta data for public form.
ALTER TABLE  `sites` ADD  `tstml` TEXT NOT NULL AFTER  `theme` ,
ADD  `tstml_msg` TEXT NOT NULL AFTER  `tstml`;


ALTER TABLE  `patrons` ADD  `meta` VARCHAR( 255 ) NOT NULL AFTER  `id`;

-- add locking
ALTER TABLE  `testimonials` ADD  `lock` INT( 1 ) NOT NULL;





UPDATE `version` SET  `at` =5;