

-- remove forum tables
DROP TABLE  `forum_cats` ,
`forum_cat_posts` ,
`forum_cat_post_comments` ,
`forum_comment_votes` ;

UPDATE `version` SET  `at` =7;