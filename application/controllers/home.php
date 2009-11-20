<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* login to admin panel or show dashboard.
 */

 class Home_Controller extends Controller {

	public $shell;
	
	public function __construct()
	{			
		parent::__construct();

		$this->shell = new View('home/shell');
	}


/*
 * marketing page
 */
 public function index()
 {
		$this->shell->content = new View('home/home');
		die($this->shell);
		
		
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
 

/*
 * create account
 */
 public function start()
 {
		$this->shell->content = new View('admin/create');
		die($this->shell);
		
		
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
 
 public function reviews()
 {
		$this->shell->content = new View('home/reviews');
		die($this->shell);
		
	}
 
} // End admin Controller
