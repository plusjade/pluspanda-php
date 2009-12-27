
ALTER TABLE `tags` ADD `position` INT( 3 ) NOT NULL;



CREATE TABLE IF NOT EXISTS `owners` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(7) NOT NULL,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` char(60) NOT NULL,
  `token` varchar(32) NOT NULL,
  `cm_id` varchar(35) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

RENAME TABLE users TO customers;
ALTER TABLE `reviews` CHANGE `user_id` `customer_id` INT( 9 ) NOT NULL ;


CREATE TABLE `pluspanda`.`owners_sites` (
`owner_id` INT( 9 ) NOT NULL ,
`site_id` INT( 9 ) NOT NULL
) ENGINE = MYISAM ;


ALTER TABLE `owners` ADD `created` INT( 10 ) NOT NULL ;

ALTER TABLE `owners` DROP `site_id` ;

ALTER TABLE `sites` ADD `theme` VARCHAR( 30 ) NOT NULL AFTER `homepage` ;





