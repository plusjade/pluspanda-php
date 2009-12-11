<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin interface 
 */

class Gather_Controller extends Controller {

	public $site;
	public $site_id;
	
	/**
	 * shell loading and setup routine.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$client = (isset($_GET['client']))
			? $_GET['client']
			: null;
			
		$this->site = ORM::factory('site',$client);
		$this->site_id = $this->site->id;
		
		if(!$this->site->loaded)
			die('invalid site');
		
	}

	
/*
 * display the testimonials form to collect testimonials.
 */ 
	public function testimonials()
	{
		if($_POST)
			$content = self::submit_handler('normal');
		else
			$content = $this->make_form();
		
		$shell = new View('client/shell');
		$shell->content = '<div id="plusPandaYes">'. $content . '</div>';
		die($shell);
	}


/*
 * returns the testimonial form view.
 */ 	
	private function make_form($values=NULL, $errors=NULL)
	{
		# get form questions
		$questions = ORM::factory('question')
			->where('site_id',$this->site->id)
			->find_all();
			
		$form = new View('testimonials/add_testimonial');
		$form->questions = $questions;	
		$form->values = $values;
		$form->errors = $errors;
		return $form;
	}
	
/*
 * post review handler.
 * validates and adds the new review to the site.
 * $type specifies the way in which the submission is coming.
 * normal = non javascript request on standalone site.
		ajaxP = posted via ajax
		ajaxG = GET via widget
 */
	private function submit_handler()
	{		
		# validate the form values.
		$post = new Validation($_POST);
		$post->pre_filter('trim');
		$post->add_rules('name', 'required');
		$post->add_rules('email', 'required');
		$_POST['rating'] = 4;
		# on error
		# this should rarely happen due to client-side js validation...
		if(!$post->validate() OR empty($_POST['rating']))
			return $this->make_form($_POST, $post->errors());

		# on valid submission:
		
		# load customer
		$customer = ORM::factory('customer')
			->where('site_id', $this->site_id)
			->find($_POST['email']);
		
		# if customer does not exist, create him.
		if(!$customer->loaded)
		{
			$customer = ORM::factory('customer');
			$customer->site_id = $this->site_id;
			$customer->email = $_POST['email'];
			$customer->name = $_POST['name'];
		}
		$customer->company = $_POST['company'];
		$customer->position = $_POST['position'];
		$customer->url = $_POST['url'];
		$customer->location = $_POST['location'];
		$customer->save();
	
		# add testimonial
		$new_testimonial = ORM::factory('testimonial');
		$new_testimonial->site_id			= $this->site_id;
		$new_testimonial->customer_id	= $customer->id;
		$new_testimonial->body_edit		= json_encode($_POST['info']);
		$new_testimonial->rating			= $_POST['rating'];
		$new_testimonial->save();
		
		#TODO save the image upload.
		
		# stadalone return status
		$view = new View('testimonials/status');
		$view->success = true;
		return $view;
	}


/*
 * ajax handler.
 * routes ajax calls to appropriate private method.
 */ 	
	public function _ajax()
	{
		# submit a review via POST, 
		if($_POST)
			die($this->submit_handler('ajaxP'));

		# fetch the widget environment.
		if(isset($_GET['fetch']) AND 'testimonials' == $_GET['fetch'])
			die($this->widget());
			
		# submit a review via GET, return json status
		if(isset($_GET['submit']) AND 'testimonials' == $_GET['submit'])			
			die($this->submit_handler());
			
		# get reviews in json
		if(isset($_GET['tag']))
			die($this->get_reviews('ajax'));
			
		die('invalid api parameters');
	}			
	
	
	
} // End shell_Controller