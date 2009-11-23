<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin interface 
 */

 abstract class Admin_Interface_Controller extends Controller {

	// shell view name
	public $shell = 'admin/shell';
	public $active;

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
	}

	
	
} // End shell_Controller