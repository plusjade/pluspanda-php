
ALTER TABLE  `sites` ADD  `per_page` INT( 2 ) NOT NULL AFTER  `theme`;
ALTER TABLE  `sites` DROP  `name`;
ALTER TABLE  `sites` ADD  `sort` VARCHAR( 55 ) NOT NULL AFTER  `theme`;
ALTER TABLE  `testimonials` DROP  `requests`;


UPDATE `version` SET  `at` =6;