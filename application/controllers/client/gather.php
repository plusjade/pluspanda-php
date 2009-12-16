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