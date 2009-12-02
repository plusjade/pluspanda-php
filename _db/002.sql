

CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(7) unsigned NOT NULL,
  `name` varchar(25) CHARACTER SET utf8 NOT NULL,
  `type` varchar(25) NOT NULL,
  `view` varchar(25) CHARACTER SET utf8 NOT NULL,
  `params` varchar(25) CHARACTER SET utf8 NOT NULL,
  `sticky_posts` varchar(50) CHARACTER SET utf8 NOT NULL,
  `attributes` varchar(35) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_cats`
--

CREATE TABLE IF NOT EXISTS `forum_cats` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` int(7) unsigned NOT NULL,
  `fk_site` int(7) unsigned NOT NULL,
  `url` varchar(60) NOT NULL,
  `name` varchar(60) NOT NULL,
  `position` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_cat_posts`
--

CREATE TABLE IF NOT EXISTS `forum_cat_posts` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(9) unsigned NOT NULL,
  `forum_cat_id` int(9) unsigned NOT NULL,
  `title` varchar(72) NOT NULL,
  `forum_cat_post_comment_id` int(7) NOT NULL,
  `comment_count` int(7) NOT NULL DEFAULT '0',
  `last_active` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_cat_post_comments`
--

CREATE TABLE IF NOT EXISTS `forum_cat_post_comments` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `fk_site` int(9) unsigned NOT NULL,
  `forum_cat_post_id` int(9) unsigned NOT NULL,
  `account_user_id` int(9) unsigned NOT NULL,
  `body` text NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `vote_count` int(9) NOT NULL,
  `is_post` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_comment_votes`
--

CREATE TABLE IF NOT EXISTS `forum_comment_votes` (
  `account_user_id` int(9) NOT NULL,
  `forum_cat_post_comment_id` int(9) NOT NULL,
  `fk_site` int(7) NOT NULL,
  KEY `author_id` (`account_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
