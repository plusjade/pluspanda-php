
-- adding reviews flagging functionality.
ALTER TABLE `reviews` ADD `flagged` INT( 1 ) NOT NULL ;


CREATE TABLE `flags` (
`id` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`site_id` INT( 9 ) NOT NULL ,
`review_id` INT( 9 ) NOT NULL ,
`reason` VARCHAR( 50 ) NOT NULL ,
`status` VARCHAR( 50 ) NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `reviews` CHANGE `flagged` `flag_id` INT( 1 ) NOT NULL ;
ALTER TABLE `flags` DROP `review_id`;
	
-- update customers with token	
ALTER TABLE `customers` ADD INDEX (  `token` );
ALTER TABLE `customers` CHANGE `display_name` `name` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;


ALTER TABLE `reviews` CHANGE `tag_id` `category_id` INT( 9 ) UNSIGNED NOT NULL ;

-- adding apikey to main accounts.
ALTER TABLE  `sites` ADD  `apikey` VARCHAR( 10 ) NOT NULL AFTER  `id`;



-- cleanup the unecessary forum data.

DROP TABLE `forums`;
ALTER TABLE  `forum_cats` DROP  `fk_site`;
ALTER TABLE  `forum_cats` DROP  `forum_id`;
ALTER TABLE  `forum_cat_posts` DROP  `fk_site`;
ALTER TABLE  `forum_cat_post_comments` DROP  `fk_site`;
ALTER TABLE  `forum_comment_votes` DROP  `fk_site`;


-- decentralize customers table
-- reviewers -> reviews and patrons -> testimonials
-- ADDING TESTIMONIALS FUNCTIONALITY TABLES.

--
-- Table structure for table `patrons`
--

CREATE TABLE IF NOT EXISTS `patrons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(127) NOT NULL,
  `name` varchar(32) NOT NULL,
  `company` varchar(144) NOT NULL,
  `position` varchar(144) NOT NULL,
  `url` varchar(144) NOT NULL,
  `location` varchar(155) NOT NULL,
  `token` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `site_id` int(9) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `position` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(9) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(155) NOT NULL,
  `meta` text NOT NULL,
  `info` text NOT NULL,
  `req` varchar(25) NOT NULL,
  `position` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(9) unsigned NOT NULL,
  `tag_id` int(9) unsigned NOT NULL,
  `patron_id` int(9) NOT NULL,
  `token` varchar(9) NOT NULL,
  `body` text NOT NULL,
  `body_edit` text NOT NULL,
  `rating` int(1) NOT NULL,
  `image` varchar(50) NOT NULL,
  `created` int(10) NOT NULL,
  `updated` int(10) NOT NULL,
  `featured` int(1) NOT NULL,
  `publish` int(1) NOT NULL,
  `position` int(3) NOT NULL,
  `requests` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;




ALTER TABLE  `version` CHANGE  `at`  `at` INT( 4 ) NOT NULL;

-- update the version
UPDATE version set at=3;







