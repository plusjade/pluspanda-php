<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * set directory paths so we don't repeat ourselves.
 */
 
class r_paths_Core {

/*
 * return the path to the data directory
 * either url path or directory path.
 */
	public static function data($apikey, $type='dir')
	{
		if('url' == $type)
			return url::site("/data/$apikey"); 
		
		if(!is_dir(DOCROOT . "data/$apikey"))
			mkdir(DOCROOT . "data/$apikey");
			
		return DOCROOT . "data/$apikey";
	}

/*
 * return path to the image directory
 * either url path or directory path.
 */	
	public static function image($apikey, $type='dir')
	{
		if('url' == $type)
			return self::data($apikey, 'url') . '/rvs/img';
		
		$dir = self::data($apikey, 'dir');
		if(!is_dir("$dir/rvs"))
			mkdir("$dir/rvs");
		if(!is_dir("$dir/rvs/img"))
			mkdir("$dir/rvs/img");

		return "$dir/rvs/img";
	}	
	
	
	public static function js_cache($apikey)
	{
		$dir = self::data($apikey);
		
		if(!is_dir("$dir/rvs"))
			mkdir("$dir/rvs");
		if(!is_dir("$dir/rvs/js"))
			mkdir("$dir/rvs/js");
			
		return "$dir/rvs/js/view.js";
	}


	
} // end alerts helper

