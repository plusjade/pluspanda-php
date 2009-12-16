<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin interface 
 */

 abstract class Admin_Interface_Controller extends Controller {

	// shell view name
	public $shell = 'admin/shell';
	public $site_id = NULL;
	public $service;
	
/**
 * shell loading and setup routine.
 */
	public function __construct()
	{
		parent::__construct();

		# which service are we using?
		$this->service = (isset($_GET['service']) AND 'reviews' == $_GET['service'])
			? 'reviews'
			: 'testimonials';
			
		# Auth Instance for editing site capability
		$this->owner = new Auth();	
		
		# setup vars for logged in users...
		if($this->owner->logged_in())
		{
			$owner = $this->owner->get_user();
			$this->site = ORM::factory('site', $owner->username);
			$this->site_id = $this->site->id;
		}				
				
		# no need to load the shell if ajax.
		if(request::is_ajax())
			return TRUE;

		# Load the shell for non ajax only.
		$this->shell = new View($this->shell);
		$this->shell->service = $this->service;
		
		$this->shell->menu_reviews = array(
			array('dashboard', '/admin/home','Dashboard','ajax'),
			array('categories', '/admin/categories','Categories','ajax'),
			array('reviews', '/admin/reviews','Reviews','ajax'),
			array('customers', '/admin/customers','Customers','ajax'),
			array('widget', '/admin/widget/reviews','View Widget',''),	
			array('install', '/admin/install/reviews','Installation','ajax'),
		);

		$this->shell->menu_testimonials = array(
			array('dashboard', '/admin/home?service=testimonials','Dashboard','ajax'),
			array('tags', '/admin/tags','Tags','ajax'),
			array('testimonials', '/admin/testimonials','Testimonials','jax'),
			
			array('customers', '/admin/customers?service=testimonials','Customers','ajax'),
			array('widget', '/admin/widget/testimonials','View Widget',''),		
			array('install', '/admin/install/testimonials','Installation','ajax'),
		);
	}

	public function __call($method, $args)
	{
	
		die("$method does not exist (admin_interface)");
	}
	
	
} // End shell_Controller