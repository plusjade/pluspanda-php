<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* Manage customers. (admin mode)
	*/
	
 class Widget_Controller extends Admin_Interface_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->owner->logged_in())
			url::redirect('/admin');
	}
	

	public function reviews()
	{
		$content = new View('admin/widget');
		$content->embed_code = build::embed_code($this->site_id);

		# carry out the action so view is up-to-date.
		#if($action)
			#$content->response = alerts::display($this->$action());
	
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->shell->active = 'widget';
		$this->shell->service = 'reviews';
		die($this->shell);
	}
	
	public function testimonials()
	{
		$content = new View('admin/widget');
		$content->embed_code = build_testimonials::embed_code($this->site->apikey, NULL, FALSE);
		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->shell->active = 'widget';
		$this->shell->service = 'testimonials';
		die($this->shell);
	}	
	
	
	
} // End widget Controller
