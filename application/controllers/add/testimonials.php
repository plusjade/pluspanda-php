<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * public gathering of testimonials data for clients.
 */

class Testimonials_Controller extends Add_Interface_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		# verify testimonial token is sent.
		if(empty($_GET['ttk']))
			$this->render(View::factory('client/blank'));
		$this->testimonial_token = $_GET['ttk'];
		
		# route to method here for better urls =p
		$allowed = array('crop');
		$action = (isset($_GET['a']) AND in_array($_GET['a'], $allowed)) 
			? $_GET['a'] : 'index';

		die($this->$action());
	}

	
/*
 * display the testimonials form to collect testimonials.
 */ 
	private function index()
	{		
		if($_POST)
		{
			$content = $this->handle_submit();
			$content .= $this->make_form();
		}
		else
			$content = $this->make_form();
		
		$this->render($content);
	}


/*
 * view and handler
 * for saving a thumbnail image of the original image
 */
	private function crop()
	{
		if($_POST)
		{
			$testimonial = $this->get_testimonial();

			die($testimonial->save_crop(
						$this->site_id,
						explode('|',$_POST['params'])
			));
		}
		
		# display the crop view.
		die(build_testimonials::crop_view($this->site_id));
	}

	
/*
 * returns the testimonial form view.
 */ 	
	private function make_form()
	{
		$testimonial = $this->get_testimonial();

		# does the testimonial belong to the customer?
		if($this->customer_token !== $testimonial->customer->token)
			$this->render('invalid customer token');	

		# get form questions
		$questions = ORM::factory('question')
			->where('site_id',$this->site->id)
			->find_all();
			
		$form = new View('testimonials/add_testimonial');
		$form->questions 		= $questions;
		$form->tags					= $this->site->tags;
		$form->info 				= json_decode($testimonial->body_edit, TRUE);
		$form->testimonial 	= $testimonial;
		$form->image_url		= paths::testimonial_image_url($this->site_id);
		$form->url					= url::site("/add/testimonials/{$this->site->subdomain}?ctk=$this->customer_token&ttk=$this->testimonial_token");
		return $form;
	}


/*
 * post review handler.
 */
	private function handle_submit()
	{
		## valid testimonial are required 
		# no validation is required since we need to make
		# sure to save any info sent.		
		$testimonial = $this->get_testimonial();
		$testimonial->body_edit		= json_encode($_POST['info']);
		$testimonial->body				= $_POST['body'];
		$testimonial->tag_id			= $_POST['tag'];
		#$testimonial->rating			= $_POST['rating'];				
		$testimonial->save();
		
		$testimonial->customer->name			= $_POST['name'];
		$testimonial->customer->company		= $_POST['company'];
		$testimonial->customer->position	= $_POST['position'];
		$testimonial->customer->location	= $_POST['location'];
		$testimonial->customer->url				= $_POST['url'];
		$testimonial->customer->save();
		# save image if sent.
		if(isset($_FILES) AND !empty($_FILES['image']['tmp_name']))
			$testimonial->save_image($this->site_id, $_FILES, $testimonial->id);
		
		$view = new View('common/status');
		$view->success = TRUE;
		$view->type = 'testimonial';
		return $view;
	}


/*
 * return a valid, singleton testimonial
 */
	private function get_testimonial()
	{
		$testimonial = ORM::factory('testimonial')
			->where(array(
				'site_id'	=> $this->site_id,
				'token'		=> $this->testimonial_token
			))
			->find();
		if(!$testimonial->loaded)
			$this->render('Invalid Customer token');	
	
		return $testimonial;
	}
	
	
	
/*
 * ajax handler.
 * routes ajax calls to appropriate private method.
 */ 	
	public function _ajax()
	{
		# submit a review via POST, 
		if($_POST)
			die($this->handle_submit());
	
		# submit a review via GET, return json status
		if(isset($_GET['submit']) AND 'testimonials' == $_GET['submit'])			
			die($this->handle_submit());
			
			
		die('invalid gather parameters');
	}			
	
	
	
} // End testimonials controller