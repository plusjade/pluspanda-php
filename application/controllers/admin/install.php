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
	public function reviews($action=FALSE)
	{	
		$content = new View('admin/install_wrapper');
		$content->embed_code = build::embed_code($this->site_id, 'fake');
		$content->embed_code_lite = build::embed_code($this->site_id, 'fake', FALSE);
			
		# carry out the action so view is up-to-date.
		if($action)
			$content->response = alerts::display($this->$action());
	
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->shell->active = 'install';
		$this->shell->service = 'reviews';
		die($this->shell);
	}

	
/*
 * 
 */
	public function testimonials($action=FALSE)
	{	
		$content = new View('admin/install_wrapper');
		$content->embed_code = build_testimonials::embed_code($this->site_id, 'fake');
		$content->embed_code_lite = build_testimonials::embed_code($this->site_id, 'fake', FALSE);
			
		# carry out the action so view is up-to-date.
		if($action)
			$content->response = alerts::display($this->$action());
	
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->shell->active = 'install';
		$this->shell->service = 'testimonials';
		die($this->shell);
	}
	

} // End install Controller
