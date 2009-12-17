<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* Manage the testimonials. (admin mode)
 */
	
class Testimonials_Controller extends Admin_Interface_Controller {

	public $active_tag;
	public $active_rating;
	public $active_range;
	public $active_page;
	public $publish;
	
	public function __construct()
	{			
		parent::__construct();
		if(!$this->owner->logged_in())
			url::redirect('/admin/home');

		$this->active_tag			= (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
		$this->publish				= (isset($_GET['publish'])) ? $_GET['publish'] : NULL;
		$this->active_rating	= (isset($_GET['rating'])) ? $_GET['rating'] : 'all';
		$this->active_range		= (isset($_GET['range'])) ? $_GET['range'] : 'all';
		$this->active_page		= (isset($_GET['page']) AND is_numeric($_GET['page'])) ?	 $_GET['page'] : 1;
		
	}
	
/*
 * manage testimonialable categories.
 * this is the public wrapper.
 */
	public function index()
	{	
		$content = new View('admin/testimonials/wrapper');
		$content->categories = build::tag_select_list($this->site->tags, $this->active_tag, array('all'=>'All'));
		$content->ratings = build::rating_select_list($this->active_rating);
		$content->range = build::range_select_list($this->active_range);
		$content->testimonials = $this->get_testimonials();
		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->shell->service = 'testimonials';
		$this->shell->active = 'testimonials';
		die($this->shell);
	}


	
/*
 * get the testimonials data
 */
	private function get_testimonials()
	{
		$sort		= array('created' => 'desc');
		$where	= array();
		
		# filter by publish
		if('yes' == $this->publish)
			$where['publish'] = 1;
		elseif('no' == $this->publish)
			$where['publish'] = 0;
			
			
		# filter by tag
		if(is_numeric($this->active_tag))
			$where['tag_id'] = $this->active_tag;
		else
			$where['site_id'] = $this->site_id;
				
				
		# filter by rating
		if(is_numeric($this->active_rating))
			$where['rating'] = $this->active_rating;
	
		$now = time();
		$day = 86400;		# seconds in day

		# filter by date
		switch($this->active_range)
		{
			case 'today':
			
				break;
			case 'last7':
				$where['created >='] = time() - $day*7;
				break;
			case 'last14':
				$where['created >='] = time() - $day*14;
				break;
			case 'last30':
				$where['created >='] = time() - $day*30;
				break;
			case 'ytd':
				$where['created >='] = mktime(0, 0, 0, 1, 1, date("m Y"));
				break;
		}
		
		# get full count of testimonials for this tag.
		$total_testimonials = ORM::factory('testimonial')
			->where($where)
			->orderby($sort)
			->count_all();
		

		$offset = ($this->active_page*10) - 10;

		# get the appropriate testimonials based on page.
		$testimonials = ORM::factory('testimonial')
			->with(null)
			->where($where)
			->orderby($sort)
			->limit(10, $offset)
			->find_all();

		# build the pagination html
		$pagination = new Pagination(array(
			'base_url'			 => "/admin/testimonials?tag=$this->active_tag&rating=$this->active_rating&range=$this->active_range&page=",
			'current_page'	 => $this->active_page, 
			'total_items'    => $total_testimonials,
			'style'          => 'testimonials',
			'items_per_page' => 10
		));
		

		$view = new View('admin/testimonials/data');
		$view->testimonials = $testimonials;
		$view->pagination = $pagination;
		$view->tags = $this->site->tags;
		$view->active_tag = $this->active_tag;
		return $view;
	}		
	
	
	public function edit()
	{
		if(empty($_GET['id']))
			die('no id sent');
		valid::id_key($_GET['id']);
		$id = $_GET['id'];
		
		$testimonial = ORM::factory('testimonial')
			->where('site_id',$this->site_id)
			->find($id);
		if(!$testimonial->loaded)
			die('invalid id');
				
		# get questions
		$questions = ORM::factory('question')
			->where('site_id',$this->site_id)
			->find_all($id);
		
		
		$view = new View('admin/testimonials/edit');
		$view->testimonial = $testimonial;
		$view->info = json_decode($testimonial->body_edit, TRUE);
		$view->questions = $questions;
		$view->tags = $this->site->tags;
		$view->image_url = paths::testimonial_image_url($this->site_id);
			
		die($view);
	}

/* 
 * save a testimonial
 */
	public function save()
	{
		if(!$_POST)
			die('nothing sent');

		if(empty($_GET['id']))
			die('invalid id');
		valid::id_key($_GET['id']);
		$id = $_GET['id'];

		
		$testimonial = ORM::factory('testimonial')
			->where('site_id',$this->site_id)
			->find($id);
		if(!$testimonial->loaded)
			die('invalid id');


		# validate the form values.
		$post = new Validation($_POST);
		$post->pre_filter('trim');
		$post->add_rules('name', 'required');
		#$post->add_rules('email', 'required');
		#$post->add_rules('rating', 'required');


		# on error! this should rarely happen due to client-side js validation...
		if(!$post->validate())
			die('invalid post');

		# save image if sent.
		if(isset($_FILES))
			if($filename = $this->handle_image($_FILES, $testimonial->id))
			{
				$testimonial->image = $filename;
				$testimonial->save();
			}
		
		$testimonial->customer->name			= $_POST['name'];
		$testimonial->customer->company		= $_POST['company'];
		$testimonial->customer->position	= $_POST['position'];
		$testimonial->customer->url				= $_POST['url'];
		$testimonial->customer->location	= $_POST['location'];
		$testimonial->customer->save();

		$testimonial->body		= $_POST['body'];	
		$testimonial->tag_id	= $_POST['tag'];
		$testimonial->publish	= (empty($_POST['publish']))
			? 0
			: 1;
		#$testimonial->rating	= $_POST['rating'];			
		$testimonial->save();

		die('Testimonial Saved!');
	}

	
	
/*
 * save a thumbnail image of the original image
 */
	public function crop()
	{
		if($_POST)
		{
			if(empty($_GET['id']))
				die('invalid id');
			valid::id_key($_GET['id']);
			$id = $_GET['id'];
			
			$testimonial = ORM::factory('testimonial')
				->where('site_id',$this->site_id)
				->find($id);
			if(!$testimonial->loaded)
				die('invalid id');
			
			$img_path = paths::testimonial_image($this->site_id). "/full_$testimonial->image";
			$image	= new Image($img_path);			
			$width	= $image->__get('width');
			$height	= $image->__get('height');

			# Make thumbnail from supplied post params.		
			$params = explode('|',$_POST['params']);
			$size = 125;
			$thumb_path = paths::testimonial_image($this->site_id). "/$testimonial->image";
			
			$image
				->crop($params[0], $params[1], $params[2], $params[3])
				->resize($size, $size)
				->sharpen(20)
				->save($thumb_path);
			
			die('Image saved!');
		}
		
		# display the crop view.
		
		if(empty($_GET['image']))
			die('image not available');
			
		$image_dir = paths::testimonial_image($this->site_id);
		if(!file_exists("$image_dir/full_".$_GET['image']))
			die('image not available');
			
		#hack 
		$id = explode('.',$_GET['image']);
		
		$view = new View('admin/testimonials/crop');
		$view->image_url	= paths::testimonial_image_url($this->site_id);
		$view->filename		= $_GET['image'];
		$view->id					= $id[0];
		die($view);
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
		$img_dir = paths::testimonial_image($this->site_id);
		
		# was a file uploaded?
		if(!isset($files['image']['tmp_name']))
			return FALSE;
		if(!is_uploaded_file($files['image']['tmp_name']))
			return FALSE;
			
		# get extension
		$ext	= strtolower(strrchr($files['image']['name'], '.'));
		
		# is this an image?
		if(!array_key_exists($ext, $image_types))
			return FALSE;

		# sanitize the filename.
		$filename	= "$id$ext";

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

		# save original
		if(500 <= $width)
			$image->resize(500, 0, Image::WIDTH);
		$image->save("$img_dir/full_$filename");
		
		return $filename;
	}
		
		
} // End testimonials Controller
