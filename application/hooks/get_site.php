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
		# fetch the widget environment (should be cached)
		if(isset($_GET['fetch']) AND 'widget' == $_GET['fetch'])
		{
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: text/javascript');
			
			if(empty($_GET['jquery']))
				readfile(DOCROOT . 'static/js/jquery.js');		
			readfile(DOCROOT . 'static/js/addon.js');
			
			$js_cache = DOCROOT . "widget/js/".$_GET['apikey'].'.js';
			if(file_exists($js_cache))
			{
				readfile($js_cache);
				die();
			}
		}

		# get the account.		
		$site = ORM::factory('site', $_GET['apikey']);
		# should we make this a 404 since its an api call?
		if(!$site->loaded)
			die('invalid api key');
		
		# send to the live controller! bye bye!
		$live = new Live_Controller($site, NULL, 'api');		
	}	
	
	# get the page_name.
	$url_array = Uri::url_array();
	$page_name = (empty($url_array['0'])) 
		? null
		: $url_array['0'];
	
	# if admin, we are done here.
	if('admin' == $page_name)
		return FALSE;
	
	if('forum' == $page_name)
	{
		$parent = ORM::factory('forum',1);
		$forum = new Forum_Controller();
		die($forum->index($parent));
	}
	
	# load the marketing site.	
	new Home_Controller($page_name);
	die();
	/** default controller is "home" **/
}
Event::add('system.ready', 'get_site');
/* end */