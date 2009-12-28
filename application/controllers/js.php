<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Compile and manage javascript files for both live and admin sessions.
 * The javascripts scope apply to all site functionality.
 */
 
class Js_Controller {

/*
 * javascript bundle for /add/testimonials 
 */
	public static function add_testimonials()
	{
		header('Content-type: text/javascript');
		header("Expires: Sat, 26 Jul 2010 05:00:00 GMT");	

		$files = array(
			'js/addon.js',
			'js/facebox.js',
			'js/jcrop.js',
			'js/slider.js',
		);
		
		ob_start();
		foreach($files as $file)
			if(file_exists(DOCROOT . "static/$file"))
				readfile(DOCROOT . "static/$file");

		die();
	}

	
/*
 * javascript bundle for /admin 
 */
	public static function admin()
	{
		header('Content-type: text/javascript');
		header("Expires: Sat, 26 Jul 2010 05:00:00 GMT");	

		$files = array(
			'js/addon.js',
			'js/jquery.ui.js',
			'js/facebox.js',
			'js/jcrop.js',
			'admin/js/init.js',
		);
		
		ob_start();
		foreach($files as $file)
			if(file_exists(DOCROOT . "static/$file"))
				readfile(DOCROOT . "static/$file");

		die();
	}

	
	public function __call($args, $method)
	{
		Event::run('system.404');
	}
	

	
} /* End  */
