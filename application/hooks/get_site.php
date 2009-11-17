<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * This is the Main GateKeeper to PlusPanda.
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
	# submit a review via GET, return jsonp
	if(isset($_GET['submit']) AND 'review' == $_GET['submit'])
	{
		$home = new Home_Controller();
		die($home->_submit_handler('ajaxG'));
	}	

	$debug = false;
	# Route ajax requests
	if($debug OR request::is_ajax())
	{	
		if(isset($_GET['output']))
			$_GET['ajax_output'] = $_GET['output'];
		else
			$_GET['ajax_output'] = '';
			
		# send to tool _ajax handler. we expect raw data output.
		$home = new Home_Controller();
		die($home->_ajax());
	}	
	
	
}
Event::add('system.ready', 'get_site');
/* end */