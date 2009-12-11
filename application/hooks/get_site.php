<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * This is the Main GateKeeper to PlusPanda.com
 * This site serves 3 purposes:
 * 1. Handles api calls for the widget.
 * 2. Loads the static marketing site.
 * 3. Loads the admin panel.
 */
function get_site()
{	
	# is this an API call?
	if(isset($_GET['apikey']))
	{	
		# fetch the widget environments (should be cached)
		$allowed = array('reviews','testimonials');
		if(isset($_GET['fetch']) AND in_array($_GET['fetch'], $allowed))
		{
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: text/javascript');
			
			if(empty($_GET['jquery']))
				readfile(DOCROOT . 'static/js/jquery.js');		
			readfile(DOCROOT . 'static/js/addon.js');
	
			$js_cache = DOCROOT . $_GET['fetch'].'/js/'.$_GET['apikey'].'.js';	
			if(file_exists($js_cache))
				die(readfile($js_cache));

			# get the account.		
			$site = ORM::factory('site', $_GET['apikey']);
			# should we make this a 404 since its an api call?
			if(!$site->loaded)
				die('invalid api key');
					
			# send to api controller to build and cache js. bye bye!
			if('reviews' == $_GET['fetch'])
				new Reviews_Controller($site, NULL, 'api');
			else
				new Testimonials_Controller($site, 'api');
		}

		# do some work with the appropriate service api
		
		# get the account.		
		$site = ORM::factory('site', $_GET['apikey']);
		# should we make this a 404 since its an api call?
		if(!$site->loaded)
			die('invalid api key');
					
		if(isset($_GET['service']))
			if('reviews' == $_GET['service'])
				new Reviews_Controller($site, NULL, 'api');
			elseif('testimonials' == $_GET['service'])
				new Testimonials_Controller($site, 'api');	
			
		die();
	}	
	
	# this is not the api !!
	
	# get the page_name.
	$url_array = Uri::url_array();
	$page_name = (empty($url_array['0'])) 
		? null
		: $url_array['0'];
	
	switch($page_name)
	{
		case 'admin':
			return FALSE;
			break;

		case 'client':
			return FALSE;
			break;
			
		case 'forum':
			$parent = ORM::factory('forum',1);
			$forum = new Forum_Controller();
			die($forum->index($parent));		
			break;
	}
	
	# load the marketing site.	
	new Home_Controller($page_name);
	die();
	/** default controller is "home" **/
}
Event::add('system.ready', 'get_site');
/* end */