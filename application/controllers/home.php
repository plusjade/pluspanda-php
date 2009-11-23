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
		$url_array = Uri::url_array();
		$this->shell->active  = (empty($url_array['0'])) 
			? null
			: $url_array['0'];
	}


/*
 * marketing page
 */
 public function index()
 {
		$this->shell->content = new View('home/home');		
		$this->shell->title = 'Add and Manage Customer Reviews Instantly On Your Website';
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
		$this->shell->content = new View('home/create');
		$this->shell->title = 'Get your free customer reviews system now';
		$this->shell->content->values = array(
			'username'	=> '',
			'email'			=> '',
			'password'	=> '',
		);
		
		if(empty($_POST))
			die($this->shell);
			
		# handle the POST.
		$this->shell->content->values = $_POST;

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
			$this->shell->content->alert = alerts::display(array('error'=>'Invalid Fields'));
			die($this->shell);		
		}
		
		$new_owner = ORM::factory('owner');

		# unique username.
		if(!$new_owner->username_available($_POST['username']))
		{
			$this->shell->content->alert = alerts::display(array('error'=>'Username Already Exists!'));
			die($this->shell);			
		}
		
		# unique email.
		if(!$new_owner->email_available($_POST['email']))
		{
			$this->shell->content->alert = alerts::display(array('error'=>'Email Already Exists!'));
			die($this->shell);			
		}
		
		$new_owner->username	= $_POST['username'];
		$new_owner->email			= $_POST['email'];
		$new_owner->password	= $_POST['password'];
		$new_owner->save();
	
		# create the new site.
		$new_site = ORM::factory('site');
		$new_site->subdomain = $new_owner->username;
		$new_site->add($new_owner);
		$new_site->save();
		
		$url = 'http://'.$new_owner->username .'.'. ROOTDOMAIN . "/admin?action=force&name=$new_owner->username&tkn=$new_owner->token";
		
		url::redirect($url);
 }
 
/*
 * display demo page.
 */
	public function demo()
	{
		$this->shell->content = new View('home/demo');
		$this->shell->title = 'Customer reviews demo';
		die($this->shell);
	}

/*
 * display demo page.
 */
	public function contact()
	{
		$this->shell->content = new View('home/contact');
		$this->shell->title = 'Contact me';
		die($this->shell);
	}
	
/*
 * 404 page
 */
	public function _custom_404()
	{
		header("HTTP/1.0 404 Not Found");
		$this->shell->title = '404 not found';
		$this->shell->content = new View('home/404');		
		die($this->shell);
	}
		
} // End admin Controller
