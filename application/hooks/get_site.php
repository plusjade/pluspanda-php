<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * This is the Main GateKeeper to PlusPanda.
 * fetches and scopes to appropriate site account.
 */
function get_site()
{
	$session = Session::instance();
	$domain_array = explode('.', $_SERVER['HTTP_HOST']);	
	
	# if the url = [subdomain].localhost.net
	if(in_array(ROOTNAME, $domain_array))
	{
		$field_name	= 'subdomain';
		$site_name	= $domain_array['0'];
		
		# if no subdomain, default to marketing website.
		if('2' == count($domain_array))
			$site_name = ROOTACCOUNT;
	}
	else
	{
		# custom domain
		#if ( isset($_SESSION['site_name']) )
			#return TRUE;
			
		$field_name	= 'custom_domain';
		$site_name	= $_SERVER['HTTP_HOST'];
	}
	
	$site = ORM::factory('site')
		->where(array($field_name => $site_name))
		->find();
	if (!$site->loaded)
	{
		header("HTTP/1.0 404 Not Found");
		die('pluspanda site does not exist');
	}
	
	# IMPORTANT: sets the site name & non-sensitive site_data.
	$_SESSION['site_name']	= $site->subdomain;
	$_SESSION['site_id']	= $site->id;
	
/* ---- ROUTE THE REQUEST ---- */

	# get the page name if set.
	$url_array = Uri::url_array();
	$page_name = (empty($url_array['0'])) 
		? null
		: $url_array['0'];

	# hack to make the homepage for the rootaccount available.
	# fix this, this is always true =_0
	if($site_name == ROOTACCOUNT)
	{
		if(empty($page_name))
		{
			$home = new Home_Controller();
			die($home->index());
		}
		$pages = array('start','demo','reviews','contact');
		if(in_array($page_name, $pages))
		{
			$home = new Home_Controller();
			die($home->$page_name());		
		}
		
		/*
		# make reviews available at a page-name.
		if('all_reviews' == $page_name)
		{
			$live = new Live_Controller($page_name);
			die($live->index());
		}
		*/
	}

		
	# is this an API call?
	if('api' == $page_name)
	{
		$live = new Live_Controller(NULL, 'api');
		
		# submit a review via GET, return JSONP
		if(isset($_GET['submit']) AND 'review' == $_GET['submit'])
			die($live->_submit_handler());

		# send to _ajax handler for raw data reponse.
		die($live->_ajax());
	}	

	/** default controller is "live" **/
}
Event::add('system.ready', 'get_site');
/* end */