<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin interface 
 */

 abstract class Admin_Interface_Controller extends Controller {

	// shell view name
	public $shell = 'admin/shell';

	/**
	 * shell loading and setup routine.
	 */
	public function __construct()
	{
		parent::__construct();

		# Load the shell
		$this->shell = new View($this->shell);
		
		# Auth Instance for editing site capability
		$this->owner = new Auth();	
		
		/*
		if(!$this->owner->logged_in())
		{
			$this->shell->login = new View('admin/login');
			die($this->shell);
		}
		*/
	}

	
	
} // End shell_Controller