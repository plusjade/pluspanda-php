<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * public gathering of data for clients
 */

class Gather_Controller extends Controller {

	public $site;
	public $site_id;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->shell = new View('client/shell');
		
		# get the clients name from url.
		$url_array = Uri::url_array();
		$client = (empty($url_array['3'])) 
			? NULL
			: $url_array['3'];
			
		$this->site = ORM::factory('site',$client);
		$this->site_id = $this->site->id;
		if(!$this->site->loaded)
		{
			$this->shell->content = new View('client/no_site');
			die($this->shell);
		}
		
		# Only registered customers can submit testimonials.
		$this->customer_token = (isset($_GET['ctk']))
			? $_GET['ctk']
			: NULL;
			
			
		$this->testimonial_token = (isset($_GET['ttk']))
			? $_GET['ttk']
			: NULL;
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
		
		$this->shell->content = $content;
		die($this->shell);
	}


/*
 * returns the testimonial form view.
 */ 	
	private function make_form($values=NULL, $errors=NULL)
	{
		# Get a testimonial?
		if(!empty($this->testimonial_token))
		{
			$testimonial = ORM::factory('testimonial')
				->where(array(
					'site_id' => $this->site_id,
					'token'		=> $this->testimonial_token
				))
				->find();
			if(!$testimonial->loaded)
				die('invalid testimonial');

			# does the testimonial belong to the customer?
			if($this->customer_token !== $testimonial->customer->token)
				die('invalid testimonial');	
				
				$fresh = FALSE;
		}
		else
		{
			# make sure the customer is valid.
			$customer = ORM::factory('customer')
				->where(array(
					'site_id' => $this->site_id,
					'token'		=> $this->customer_token
				))
				->find();
			if(!$customer->loaded)
			{
				$this->shell->content = new View('client/blank');
				die($this->shell);
			}
				
			# load the empty testimonial
			$testimonial = ORM::factory('testimonial', 1);
			# hack - update non-customer to real customer.
			foreach($testimonial->customer->as_array() as $field => $v)
				$testimonial->customer->$field = $customer->$field;
				
			$fresh = TRUE;
		}

	
		# get form questions
		$questions = ORM::factory('question')
			->where('site_id',$this->site->id)
			->find_all();
			
		$form = new View('testimonials/add_testimonial');
		$form->questions 		= $questions;
		$form->values 			= $values;
		$form->errors 			= $errors;
		$form->fresh	 			= $fresh;
		$form->tags					= $this->site->tags;
	
	
		$form->info 				= json_decode($testimonial->body_edit, TRUE);
		$form->testimonial 	= $testimonial;		

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
		$post->add_rules('rating', 'required');
		
		# on error
		# this should rarely happen due to client-side js validation...
		if(!$post->validate())
			return $this->make_form($_POST, $post->errors());


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

		# save image if sent.
		if(isset($_FILES))
			if($filename = $this->handle_image($_FILES, $new_testimonial->id))
			{
				$new_testimonial->image = $filename;
				$new_testimonial->save();
			}
			
		# stadalone return status
		$view = new View('common/status');
		$view->success = true;
		$view->type = 'testimonial';
		return $view;
	}

/*
 * handle any uploaded file as image
 */
	private function handle_image($files, $id)
	{
		#echo kohana::debug($files); die();
		$image_types = array(
			'.jpg'	=> 'jpeg',
			'.jpeg'	=> 'jpeg',
			'.png'	=> 'png',
			'.gif'	=> 'gif',
			'.tiff'	=> 'tiff',
			'.bmp'	=> 'bmp',
			);	
		
		$dir = DOCROOT . "data/$this->site_id";
		$img_dir = "$dir/tstml/img";		
		if(!is_dir("$dir/tstml"))
			mkdir("$dir/tstml");		
		if(!is_dir($img_dir))
			mkdir($img_dir);	
			
		# was a file uploaded?
		if(!is_uploaded_file($files['image']['tmp_name']))
			return FALSE;
			
		# get extension
		$ext	= strrchr($files['image']['name'], '.');
		$ext	= strtolower($ext);
		
		# is this an image?
		if(!array_key_exists($ext, $image_types))
			return FALSE;

		# sanitize the filename.
		$filename	= "$id$ext";
					
		# initiliaze image as library object.	
		$image	= new Image($files['image']['tmp_name']);			
		$width	= $image->__get('width');
		$height	= $image->__get('height');

		# Make square thumbnails
		$size = 125;			
		if($width > $height)
			$image->resize($size, $size, Image::HEIGHT)->crop($size, $size);
		else
			$image->resize($size, $size, Image::WIDTH)->crop($size, $size);
		
		$image->save("$img_dir/$filename");

		
		# save an optimized original version.
		# todo. save any apsurdly huge image to a max dimension.
		# if the file is over 300kb its likely not optimized.
		if(300000 < $files['image']['size'])
			$image->quality(75)->save("$img_dir/$filename");
		else
			move_uploaded_file($files['image']['tmp_name'], "$img_dir/orig_$filename");			
	
		return $filename;
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
	
		# submit a review via GET, return json status
		if(isset($_GET['submit']) AND 'testimonials' == $_GET['submit'])			
			die($this->submit_handler());
			
			
		die('invalid gather parameters');
	}			
	
	
	
} // End shell_Controller