<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin interface 
 */

 abstract class Add_Interface_Controller extends Controller {

	public $site;
	public $site_id;
	public $customer_token;
	
/**
 * shell loading and setup routine.
 */
	public function __construct()
	{
		parent::__construct();

		$this->shell = new View('client/shell');
		
		# get the clients name from url.
		$url_array = Uri::url_array();
		$client = (empty($url_array['2'])) 
			? NULL
			: mysql_real_escape_string($url_array['2']);
			
		# is the site valid?
		$this->site = ORM::factory('site', $client);
		if(!$this->site->loaded)
			$this->render(View::factory('client/no_site'));

		$this->site_id	= $this->site->id;
		
		# Verify a valid customer:
		if(empty($_GET['ctk']))
			$this->render(View::factory('client/blank'));
		$this->customer_token = $_GET['ctk'];

		$customer = ORM::factory('customer')
			->where(array(
				'site_id' => $this->site_id,
				'token'		=> $this->customer_token
			))
			->find();
		if(!$customer->loaded)
			$this->render(View::factory('client/blank'));
	}

	
/*
 * output the rendered page
 */
	public function render($content)
	{
		$this->shell->content = $content;
		die($this->shell);
	}	

	
	
	
	public function __call($method, $args)
	{
	
		die("$method does not exist (admin_interface)");
	}
	
	
} // End shell_Controller