<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* login to admin panel or show dashboard.
 */

 class Admin_Controller extends Admin_Interface_Controller {

	public function __construct()
	{			
		parent::__construct();
		
	}


/*
 * executes login logic.
 */
	public function index()
	{
		$action = (isset($_GET['action'])) ? $_GET['action'] :''; 
		if('force' == $action)
			$this->force();
		if(!$this->owner->logged_in())
			$this->login();
		if('logout' == $action)
			$this->logout();
		
		
		$content = new View('admin/dashboard');

	
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
			
		$content->tags = $site->tags;
		$reviews_data = View::factory('admin/reviews_data');
		$reviews_data->reviews = $reviews;
		$reviews_data->pagination='';
		$content->reviews = $reviews_data;
		$content->customers = $customers;
		$content->owner = $this->owner->get_user();
		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->active['dashboard'] = 'class="active"';
		$this->shell->active = $this->active;
		
		die($this->shell);
	}

/* 
 * attempt to login an owner
 */ 
	private function login()
	{
		$this->shell->login = new View('admin/login');
		
		if(empty($_POST))
			die($this->shell);			
		
		$post = new Validation($_POST);
		$post->pre_filter('trim');
		$post->add_rules('username', 'required', 'valid::alpha_numeric');
		$post->add_rules('password', 'required', 'valid::alpha_dash');
		$values = array(
			'username'	=> '',
			'password'	=> ''
		);
		$values	= arr::overwrite($values, $post->as_array()); 			

		# if Post is good, atttempt to log owner in.		
		if($post->validate())
			if($this->owner->login($_POST['username'], $_POST['password'], 0))
			{	
				if(isset($_GET['ref']))
					url::redirect($_GET['ref']);
				
				url::redirect('/admin');
			}
			
		# error
		$this->shell->login->alert = alerts::display(array('error'=>'Invalid Username or Password.'));
		$this->shell->login->values = $_POST;
		die($this->shell);	
	}
	
	
/*
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
 
	private function logout()
	{
		$this->owner->logout(TRUE);
		url::redirect('/');
	}
	
 
} // End admin Controller
