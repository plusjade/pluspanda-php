<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * This is the Main GateKeeper to Plusjade.
 * It routes all logic to appropriate controllers.
 
 * 1. Fetches appropriate site from URL.
 * 2. Routes URL to appropriate controller based on config for site name.
 * 	Cases:
		a. is ajax request:		Fetch raw data.
		b. is page_name:		grab tools, Build page, render the page.
		c. is get/controller:	Admin, map to appropriate controller.
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
	
	/*
	 --- Route the URL ---
	 ---------------------
	 * The URL will tell us how to build the page.
	  		a. is ajax request
	 		b. is page_name
				is protected page?
			c. is file request
	  		d. is /get/
	 */

	/*
	# Get page_name
	$url_array = Uri::url_array();
	$page_name = (empty($url_array['0'])) 
		? $site->homepage
		: $url_array['0'];

	*/
			if($_POST)
			{
				echo 'test			
				<script type="text/javascript">
					//alert(parent.location);
					console.log(window.parent);
					//window.parent.location.hash = "TEST";
					window.location.hash = "success";
					alert(window.location.hash);
				</script>
				<div></div>
				'; 
				die();
			}
			if(isset($_GET['add']) AND 'review' == $_GET['add'])
			{
				#test
				die('pandaApiSubmit([{},{}])');
			}
			
			
		$debug = false;
		# Is ajax request?
		# (isset($_GET['get']) AND 
		if($debug OR request::is_ajax())
		{
			die('test([{},{}])');
			

			
			# send to tool _ajax handler. we expect raw data output.
			$home = new Home_Controller();
			die($home->_ajax());
		}	
	
}
Event::add('system.ready', 'get_site');
/* end */