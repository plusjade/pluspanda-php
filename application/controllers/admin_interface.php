<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin interface 
 */

 abstract class Admin_Interface_Controller extends Controller {

	// shell view name
	public $shell = 'admin/shell';
	public $active;
	public $site_id = null;
	
	/**
	 * shell loading and setup routine.
	 */
	public function __construct()
	{
		parent::__construct();

		# Load the shell
		$this->shell = new View($this->shell);
		$this->active = array(
			'dashboard'		=>'',
			'categories'	=>'',
			'reviews'			=>'',
			'customers'		=>'',
			'install'			=>'',
			'account'			=>'',
			'logout'			=>''
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

	
	
} // End shell_Controller