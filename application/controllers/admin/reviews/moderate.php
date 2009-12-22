<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* 
	*/
	
 class Moderate_Controller extends Admin_Interface_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->owner->logged_in())
			url::redirect('/admin');
	}
	

	public function index()
	{
		/*
		if(empty($_GET['review_id']) OR !is_numeric($_GET['review_id']))
			die('invalid review id');
		*/	
		
		if($_POST)
		{			
			$flagged = ORM::factory('flag');
			$flagged->site_id = $this->site_id;
			$flagged->reason = $_POST['reason'];
			$flagged->save();

			$review = ORM::factory('review')
				->where('site_id', $this->site_id)
				->find($_POST['review_id']);
			$review->flagged = $flagged->id;
			$review->save();
			
			echo 'flagged';
		}
		die();
				
				
				
		$content = new View('admin/moderate');

		# carry out the action so view is up-to-date.
		#if($action)
			#$content->response = alerts::display($this->$action());
	
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->active['widget'] = 'class="active"';
		$this->shell->active = $this->active;
		die($this->shell);
	}
} // End widget Controller
