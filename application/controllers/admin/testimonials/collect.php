<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* login to admin panel or show dashboard.
 */

 class Collect_Controller extends Admin_Interface_Controller {

	public function __construct()
	{			
		parent::__construct();
		
		$this->requests = (isset($_GET['requests']) AND is_numeric($_GET['requests'])) ? $_GET['requests'] : FALSE;  
    
	}


/*
 * displays dashboard for logged-in users.
 * executes login logic for non-logged in users.
 */
	public function index()
	{			
		if(!$this->owner->logged_in())
			$this->login();
		
		$params = array(
			'site_id'	=> $this->site_id,
			'requests' => $this->requests
			#'page'		=> $this->active_page,
			#'tag'			=> $this->active_tag,
			#'publish'	=> $this->publish,
			#'created'	=> $this->active_sort,
		);
    $total = ORM::factory('testimonial')
			->fetch($params, 'count');
  		
		$testimonials = ORM::factory('testimonial')
			->fetch($params);
			
		$content = new View("admin/testimonials/collect");
		$content->total = $total;
		$content->testimonials = $testimonials;
		
		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content	= $content;
		$this->shell->active	= 'collect';
		$this->shell->service	= 'testimonials';
		die($this->shell);
	}

	
	
} // End testimonials dashboard Controller
