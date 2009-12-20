<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * set directory paths so we don't repeat ourselves.
 */
 
class t_paths_Core {

/*
 * return the path to the data directory
 * either url path or directory path.
 */
	public static function data($site_id, $type='dir')
	{
		if('url' == $type)
			return 'http://'. ROOTDOMAIN ."/data/$site_id";
		
		return DOCROOT . "data/$site_id";
	}

/*
 * return path to the image directory
 * either url path or directory path.
 */	
	public static function image($site_id, $type='dir')
	{
		if('url' == $type)
			return self::data($site_id, 'url') . '/tstml/img';
		
		$dir = self::data($site_id, 'dir');
		if(!is_dir("$dir/tstml"))
			mkdir("$dir/tstml");
		if(!is_dir("$dir/tstml/img"))
			mkdir("$dir/tstml/img");

		return "$dir/tstml/img";
	}	
	
	
	public static function js_cache($site_id, $type)
	{
		$type = ('testimonials' == $type)
			? 'tstmls'
			: 'rvs';
		$dir = self::data_dir($site_id);
		
		$js_cache = "$dir/$type/js/view.js";
		return $js_cache;
	}


	
} // end alerts helper

