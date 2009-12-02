<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* Manage customers. (admin mode)
	*/
	
 class Install_Controller extends Admin_Interface_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->owner->logged_in())
			url::redirect('/admin');
	}
	
/*
 * manage customers.
 * this is the public wrapper.
 */
	public function index($action=FALSE)
	{	
		$content = new View('admin/install_wrapper');

		# carry out the action so view is up-to-date.
		if($action)
			$content->response = alerts::display($this->$action());
	
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->active['install'] = 'class="active"';
		$this->shell->active = $this->active;
		die($this->shell);
	}

	
	public function widget()
	{
		$content = new View('admin/widget');
		die($content);
	}
} // End install Controller
