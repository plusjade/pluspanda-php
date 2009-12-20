<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* Manage customers. (admin mode)
	*/
	
 class Customers_Controller extends Admin_Interface_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->owner->logged_in())
			url::redirect('/admin/home');
	}
	
/*
 * manage customers.
 * this is the public wrapper.
 */
	public function index($action=FALSE)
	{	
		$content = new View('admin/testimonials/customers_wrapper');

		$testimonials = ORM::factory('testimonial')
			->where('site_id',$this->site_id)
			->find_all();

			
			
		$content->testimonials = $testimonials;
		$content->pagination = 'blah';
		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->shell->active = 'customers';
		die($this->shell);
	}

	
	
/*
 * add a new category
 */

	


	
/* 
 * centralize this controllers interface
 * Non-ajax calls get wrapped via the index.
 * ajax calls get fast tracked to their method calls.
 */
	public function __call($method, $args)
	{
		# white-list methods.
		# note there are methods we don't want in here.
		# echo kohana::debug(get_class_methods($this));
		if(!in_array($method, get_class_methods($this)))
			die('404');
		
		if(request::is_ajax())
			echo alerts::display($this->$method());
		else
			$this->index($method);
		
		die();
	}
	
} // End categories Controller
