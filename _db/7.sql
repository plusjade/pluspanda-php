

-- remove forum tables
DROP TABLE  `forum_cats` ,
`forum_cat_posts` ,
`forum_cat_post_comments` ,
`forum_comment_votes` ;

-- simplify schema
-- (this had to be done manually )
-- ALTER TABLE  `owners` ADD  `apikey` VARCHAR( 10 ) NOT NULL AFTER  `email`;
-- ALTER TABLE  `sites` ADD  `owner_id` INT( 9 ) NOT NULL AFTER  `id`;

ALTER TABLE  `owners` DROP  `cm_id`;
ALTER TABLE  `sites` DROP  `apikey`;
DROP TABLE `owners_sites`;


ALTER TABLE  `questions` CHANGE  `site_id`  `owner_id` INT( 9 ) UNSIGNED NOT NULL;
ALTER TABLE  `tags` CHANGE  `site_id`  `owner_id` INT( 9 ) NOT NULL;
ALTER TABLE  `testimonials` CHANGE  `site_id`  `owner_id` INT( 9 ) UNSIGNED NOT NULL;


RENAME TABLE sites TO tconfigs;
ALTER TABLE  `tconfigs` DROP  `created`;
ALTER TABLE  `tconfigs` CHANGE  `tstml_msg`  `msg` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `tconfigs` CHANGE  `tstml`  `form` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `testimonials` DROP  `featured`;


-- merge patrons into testimonial table

ALTER TABLE  `testimonials` ADD  `name` VARCHAR( 255 ) NOT NULL AFTER  `token` ,
ADD  `company` VARCHAR( 255 ) NOT NULL AFTER  `name` ,
ADD  `c_position` VARCHAR( 255 ) NOT NULL AFTER  `company` ,
ADD  `location` VARCHAR( 255 ) NOT NULL AFTER  `c_position` ,
ADD  `url` VARCHAR( 255 ) NOT NULL AFTER  `location`;

ALTER TABLE  `testimonials` ADD  `email` VARCHAR( 255 ) NOT NULL ,
ADD  `meta` VARCHAR( 255 ) NOT NULL;


UPDATE `version` SET  `at` =7;