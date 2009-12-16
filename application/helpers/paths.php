<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * set directory paths so we don't repeat ourselves.
 */
 
class paths_Core {

/*
 *
 */
	public static function data_dir($site_id)
	{
		$dir = DOCROOT . "data/$site_id";
		return $dir;
	}

	public static function data_url($site_id)
	{
		$dir = 'http://'. ROOTDOMAIN ."/data/$site_id";
		return $dir;
	}
	
	
	public static function testimonial_image_url($site_id)
	{
		$path = self::data_url($site_id) . '/tstml/img';
		return $path;
	}	
	
	public static function testimonial_image($site_id)
	{
		$dir = self::data_dir($site_id);
		if(!is_dir("$dir/tstml"))
			mkdir("$dir/tstml");
		if(!is_dir("$dir/tstml/img"))
			mkdir("$dir/tstml/img");
			
		$path = "$dir/tstml/img";
		return $path;
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

