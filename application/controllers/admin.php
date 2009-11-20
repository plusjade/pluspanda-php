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
		if(!$this->owner->logged_in())
		{
			if('create' == $action)
				$this->create();
	
			$this->login();
		}
		if('logout' == $action)
			$this->logout();
		
		
		$content = new View('admin/dashboard');

		$site = ORM::factory('site', $this->site_id);
		$reviews = ORM::factory('review')
			->where('site_id',$this->site_id)
			->find_all();
		
		$content->tags = $site->tags;
		$content->reviews = $reviews;
		$content->owner = $this->owner->get_user();
		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->shell->active = array('dashboard'=>'class="active"','categories'=>'','reviews'=>'','customers'=>'','account'=>'');
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
			if($this->owner->login($_POST['username'], $_POST['password'], $this->site_id))
				url::redirect('/admin');
		
		# error
		$this->shell->login->alert = alerts::display(array('error'=>'Invalid Username or Password.'));
		$this->shell->login->values = $_POST;
		die($this->shell);	
	}
	
	
/*
 * create account
 */
 private function create()
 {
		$this->shell->login = new View('admin/create');
		if(empty($_POST))
			die($this->shell);

		# handle the POST.
		$post = new Validation($_POST);
		$post->pre_filter('trim');
		$post->add_rules('email', 'required', 'valid::email'); 
		$post->add_rules('username', 'required', 'valid::alpha_numeric');
		$post->add_rules('password', 'required', 'matches[password2]', 'valid::alpha_dash');
		$values = array(
			'email'		=> '',
			'username'	=> '',
			'password'	=> '',
			'password2'	=> '',
		);		
		if(!$post->validate())
		{
			$this->shell->login->alert = alerts::display(array('error'=>'Invalid Fields'));
			die($this->shell);		
		}
		
		$new_owner = ORM::factory('owner');

		# unique username.
		if(!$new_owner->username_available($_POST['username']))
		{
			$this->shell->login->alert = alerts::display(array('error'=>'Username Already Exists!'));
			die($this->shell);			
		}
		
		# unique email.
		if(!$new_owner->email_available($_POST['email']))
		{
			$this->shell->login->alert = alerts::display(array('error'=>'Email Already Exists!'));
			die($this->shell);			
		}
		
		$new_owner->username	= $_POST['username'];
		$new_owner->email			= $_POST['email'];
		$new_owner->password	= $_POST['password'];
		$new_owner->save();
		
		$this->shell->login->alert = alerts::display(array('success'=>'Account Created!!'));
		die($this->shell);
 }
 

	private function logout()
	{
		$this->owner->logout(TRUE);
		url::redirect('/admin');
	}
	
 
} // End admin Controller
