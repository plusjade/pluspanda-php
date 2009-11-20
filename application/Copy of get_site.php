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
	if('jade' == ROOTACCOUNT)
	{
		if(empty($page_name))
		{
			die('main homepage.');
			# go to the home page.
		}
		
		# make reviews available at a page-name.
		if('reviews' == $page_name)
		{
			die('main page revies.');
		}
	}

		
	# is this an API call?
	if('api' == $page_name)
	{
		die('asfs');
		
		# submit a review via GET, return JSONP
		if(isset($_GET['submit']) AND 'review' == $_GET['submit'])
		{
			$home = new Live_Controller();
			die($home->_submit_handler('ajaxG'));
		}	

		$debug = false;
		# Route ajax requests
		if($debug OR request::is_ajax())
		{	
			# hack to make sure ajax_output is only sent to an ajax call.
			if(isset($_GET['output']))
				$_GET['ajax_output'] = $_GET['output'];
			else
				$_GET['ajax_output'] = '';
				
			# send to tool _ajax handler. we expect raw data output.
			$home = new Live_Controller();
			die($home->_ajax());
		}
		die('no request');
	}	

	/*
	# if a controller is being called, we are done here.
	if(!empty($page_name) OR 'admin' == $page_name)
		return true;
	*/
	
}
Event::add('system.ready', 'get_site');
/* end */