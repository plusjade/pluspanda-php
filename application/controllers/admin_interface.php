<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin interface 
 */

 abstract class Admin_Interface_Controller extends Controller {

	// shell view name
	public $shell = 'admin/shell';
	public $site_id = null;
	public $service;
	
	/**
	 * shell loading and setup routine.
	 */
	public function __construct()
	{
		parent::__construct();

		# Load the shell
		$this->shell = new View($this->shell);
		
		$this->service = (isset($_GET['service']) AND 'testimonials' == $_GET['service'])
		? 'testimonials'
			: 'reviews';
			
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
			array('testimonials', '/admin/testimonials','Testimonials','ajax'),
			array('customers', '/admin/customers?service=testimonials','Customers','ajax'),
			array('widget', '/admin/widget/testimonials','View Widget',''),		
			array('install', '/admin/install/testimonials','Installation','ajax'),
		);
		
		# Auth Instance for editing site capability
		$this->owner = new Auth();	
		
		# setup vars for logged in users...
		if($this->owner->logged_in())
		{
			$owner = $this->owner->get_user();
			$site = ORM::factory('site', $owner->username);
			$this->site_id = $site->id;
		}
		
		
	}

	public function __call($method, $args)
	{
	
		die("$method does not exist (admin_interface)");
	}
	
	
} // End shell_Controller