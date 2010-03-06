

-- log external widget requests.
CREATE TABLE IF NOT EXISTS `logs` (
  `apikey` varchar(9) NOT NULL,
  `url` varchar(256) NOT NULL,
  `time` int(12) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



UPDATE `version` SET  `at` =8;