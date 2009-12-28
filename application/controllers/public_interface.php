<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * the public interface??
 */

 abstract class Public_Interface_Controller extends Controller {

	public $shell;
	public $site_id = NULL;
	
	public function __construct()
	{
		parent::__construct();

		# Auth Instance for login capability
		$this->owner = new Auth();	
		$this->site_id = 1;

		
		$this->shell = new View('home/shell');
		$this->shell->login_link = ($this->owner->logged_in())
			? 'Admin'
			: 'Login';
		$this->shell->links = array(
			'home'		=> 'Home',
			'start'		=> 'Get Started',
			'demo'		=> 'Demo',
			'faq'			=> 'FAQ',
			#'forum'		=> 'Forum',
			'contact'	=> 'Contact'
		);			
			
		/*
		# setup vars for logged in users...
		if($this->owner->logged_in())
		{
			$owner = $this->owner->get_user();
			$site = ORM::factory('site', $owner->username);
			$this->site_id = $site->id;
		}
		*/
		
	}

	
	
} // End Public_Controller