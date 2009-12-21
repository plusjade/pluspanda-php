ALTER TABLE `reviews` ADD `flagged` INT( 1 ) NOT NULL ;

CREATE TABLE `pluspanda`.`flags` (
`id` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`site_id` INT( 9 ) NOT NULL ,
`review_id` INT( 9 ) NOT NULL ,
`reason` VARCHAR( 50 ) NOT NULL ,
`status` VARCHAR( 50 ) NOT NULL
) ENGINE = MYISAM ;


ALTER TABLE `reviews` CHANGE `flagged` `flag_id` INT( 1 ) NOT NULL ;

ALTER TABLE `flags`
  DROP `review_id`;
	
ALTER TABLE `customers` ADD `company` VARCHAR( 144 ) NOT NULL AFTER `display_name` ;
ALTER TABLE `customers` ADD `position` VARCHAR( 144 ) NOT NULL AFTER `company` ;
ALTER TABLE `customers` ADD `url` VARCHAR( 144 ) NOT NULL AFTER `position` ;

ALTER TABLE `customers` CHANGE `display_name` `name` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `reviews` CHANGE `tag_id` `category_id` INT( 9 ) UNSIGNED NOT NULL 

ALTER TABLE `customers` ADD `location` VARCHAR( 155 ) NOT NULL AFTER `url` ;

ALTER TABLE  `customers` ADD INDEX (  `token` );
ALTER TABLE  `testimonials` ADD  `token` VARCHAR( 9 ) NOT NULL AFTER  `customer_id`;


ALTER TABLE  `sites` ADD  `apikey` VARCHAR( 10 ) NOT NULL AFTER  `id`;

ALTER TABLE  `testimonials` ADD  `updated` INT( 10 ) NOT NULL AFTER  `created`;

DROP TABLE `forums`;
ALTER TABLE  `forum_cats` DROP  `fk_site`;
ALTER TABLE  `forum_cats` DROP  `forum_id`;
ALTER TABLE  `forum_cat_posts` DROP  `fk_site`;
ALTER TABLE  `forum_cat_post_comments` DROP  `fk_site`;
ALTER TABLE  `forum_comment_votes` DROP  `fk_site`;





