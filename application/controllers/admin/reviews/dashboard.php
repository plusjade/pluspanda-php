<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* login to admin panel or show dashboard.
 */

 class Dashboard_Controller extends Admin_Interface_Controller {

	public function __construct()
	{			
		parent::__construct();
	}


/*
 * displays dashboard for logged-in users.
 * executes login logic for non-logged in users.
 */
	public function index()
	{			
		if(!$this->owner->logged_in())
			$this->login();
		
		$site = ORM::factory('site', $this->site_id);
		$reviews = ORM::factory('review')
			->where('site_id',$this->site_id)
			->orderby('created','desc')
			->limit(10)
			->find_all();

		$customers = ORM::factory('customer')
			->where('site_id',$this->site_id)
			->orderby('created','desc')
			->limit(10)
			->find_all();
		
		$content = new View("admin/reviews/dashboard");
		$content->categories = $site->categories;
		$reviews_data = View::factory('admin/reviews/data');
		$reviews_data->reviews = $reviews;
		$reviews_data->pagination='';
		$content->reviews = $reviews_data;
		$content->customers = $customers;
		$content->owner = $this->owner->get_user();
		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->shell->active = 'dashboard';
		$this->shell->service = 'reviews';
		die($this->shell);
	}

/* 
 * attempt to login an owner
 */ 
	private function login()
	{
		$login_shell = new View('admin/login_shell');
		$login_shell->content = new View('admin/login');

		if(empty($_POST))
			die($login_shell);			
			
		$post = new Validation($_POST);
		$post->pre_filter('trim');
		$post->add_rules('username', 'required', 'valid::alpha_numeric');
		$post->add_rules('password', 'required', 'valid::alpha_dash');

		# if Post is good, atttempt to log owner in.		
		if($post->validate())
			if($this->owner->login($_POST['username'], $_POST['password'], 0))
			{	
				if(isset($_GET['ref']))
					url::redirect($_GET['ref']);
				
				url::redirect('/admin/home');
			}
			
		# error
		$login_shell->content->alert = alerts::display(array('error'=>'Invalid Username or Password.'));
		$login_shell->content->values = $_POST;
		die($login_shell);	
	}
	
	
/*
 * NOT IN USE.
 * force an external login from created account screen.
 */
	private function force()
	{
		if(isset($_GET['name']) AND isset($_GET['tkn']))
		{
			$owner = ORM::factory('owner')
				->where(array(
					'username' => $_GET['name'],
					'token' => $_GET['tkn']
				))
				->find();
			if(!$owner->loaded)
				url::redirect('/admin');
				
			if($owner->has(ORM::factory('site', $this->site_id)))
				$this->owner->force_login($_GET['name']);
		}
		
		url::redirect('/admin');
	}
 
 
/*
 * Logs the current user out.
 */ 
	public function logout()
	{
		$this->owner->logout(TRUE);
		url::redirect('/');
	}
	

	
	
	
} // End admin Controller
