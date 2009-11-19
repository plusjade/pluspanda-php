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
		if(!$post->validate())
		{
			$this->shell->login->alert = alerts::display(array('error'=>'Invalid Username or Password.'));
			$this->shell->login->values = $_POST;
			die($this->shell);
		}

		# atttempt to log owner in.
		if($this->owner->login($_POST['username'], $_POST['password']))
		{
		
			#success!
			url::redirect('/admin');
			
			/*		
			$owner = $this->owner->get_user();
			# can this user edit the site?
			if($owner->has(ORM::factory('site', $this->site_id)))
			{
				# setup credentials via the auth library
				$this->client->force_login($owner);
				url::redirect();
			}
			*/
			$this->shell->login->alert = alerts::display(array('error'=>'Cannot edit this site.'));
			$this->shell->login->values = $_POST;
			die($this->shell);
		}
		
		$this->shell->login->alert = alerts::display(array('error'=>'Invalid Username or Password.'));
		$this->shell->login->values = $_POST;
		die($this->shell);	
	}
	
	
/*
 * create account
	TODO FINISH THIS!!!
 */
 private function create()
 {
		$this->shell->login = new View('admin/create');
		
		if(empty($_POST))
			die($this->shell);
		
		$new_owner = ORM::factory('owner');
		$new_owner->username = $_POST['username'];
		$new_owner->password = $_POST['password'];
		$new_owner->save();
		
		$this->shell->login->alert = alerts::display(array('success'=>'Account Created!!'));
		die($this->shell);
 }
 

	private function logout()
	{
		$this->owner->logout(TRUE);
		url::redirect('/admin');
	}
	
/* 
 * centralize this controllers interface
 * Non-ajax calls get wrapped via the index.
 * ajax calls get fast tracked to their method calls.
 */
	public function __call($method, $args)
	{
		# white-list methods.
		# note there are methods we don't want in here.
		# echo kohana::debug(get_class_methods($this));
		if(!in_array($method, get_class_methods($this)))
			die('404');
		
		if(request::is_ajax())
			echo alerts::display($this->$method());
		else
			$this->index($method);
		
		die();
	}

 
 
} // End admin Controller
