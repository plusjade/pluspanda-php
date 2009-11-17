<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
	
 * 3 types of data output formats:
		1. normal standalone output. if no javascript, functions as normal,
				outputs complete views.
		
		2. ajax standalone. updates via ajax, only outputs data view.
		
		3. ajax json. called externally outputs raw json to be formatted
				at other end.
 */
class Home_Controller extends Template_Controller {


	// Set the name of the template to use
	public $template = 'template';
	
	public $active_tag;
	public $active_sort;
	public function __construct()
	{
		parent::__construct();

		# setup active states.
		$this->active_tag = (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
		$this->active_sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'newest';
	}


/* 
 * The index is only a wrapper for standalone mode.
 * All widget functionality will not use this at all.
 */
	public function index()
	{
		$site = ORM::factory('site', $this->site_id);

		if($_POST)
			$add_review = self::_submit_handler('normal');
		else
		{
			$add_review = new View('add_review');
			$add_review->values = array(
				'body'					=>'',
				'display_name'	=> '',
				'email'					=> ''
			);
		}
		

		$content = new View('wrapper');
		$content->site = $site;
		$content->set_global('active_tag', $this->active_tag);
		$content->set_global('active_sort', $this->active_sort);
		$content->get_reviews = $this->get_reviews();
		$content->add_review = $add_review;
		
		$this->template->content = $content;
	}

	
/*
 * get the tags data depending on how we are asking for it.
 */
	private function get_tags()
	{
		$site = ORM::factory('site', $this->site_id);
		
	}

/*
 * get the reviews data depending on how we are asking for it.
 */
	
	private function get_reviews()
	{
		# defaults
		$format	= (isset($_GET['format'])) ? $_GET['format'] : 'normal';
		$field	= 'site_id';
		$value	= $this->site_id;
		$sort		= array('created' => 'desc');
		
		# filter by tag
		if(isset($_GET['tag']) AND is_numeric($_GET['tag']))
		{
			$field = 'tag_id';
			$value = $_GET['tag'];
		}
		
		# sort by ...
		if(isset($_GET['sort']))
		{
			$sort_by = strtolower($_GET['sort']);
			switch($sort_by)
			{
				case 'oldest':
					$sort = array('created' => 'asc');
					break;
				case 'highest':
					$sort = array('rating' => 'desc');
					break;
				case 'lowest':
					$sort = array('rating' => 'asc');
					break;
			}
		}
		
		# pagination.
		if(isset($_GET['page']))
		{
		
		}

		$reviews = ORM::factory('review')
		->where($field, $value)
		->orderby($sort)
		->find_all();
		

		### HOW DO WE RETURN IT ??
		
		# return JSON? to widget
		if('json' == $format)
		{
			$review_array = array();
			foreach($reviews as $review)
				$review_array[] = $review->as_array();

			#echo kohana::debug($review_array);
			$json = json_encode($review_array);
			#echo kohana::debug($json);
			
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: text/plain');
			#header('Content-type: application/json');
			die("pandaLoadRev($json)");
		}
		

		# send as view.
		if(isset($_GET['ajax_output']) AND 'reviews' == $_GET['ajax_output'])
		{
			$view = new View('reviews_data');
			$view->reviews = $reviews;
			die($view);
		}
		
		# TEST summary: TODO distribution as function of time??
		$summary = ORM::factory('review')
		->select('*, COUNT(reviews.id) AS total')
		->where($field, $value)
		->orderby(array('rating' => 'desc'))
		->groupby('rating')
		->find_all();
		
		$view = new View('get_reviews');
		$view->reviews = $reviews;		
		$view->summary = $summary;
		$view->set_global('active_tag', $this->active_tag);
		$view->set_global('active_sort', $this->active_sort);
		return $view;
	}
	
/*
 * post review handler.
 * validates and adds the new review to the site.
 * $type specifies the way in which the submission is coming.
 * normal = non javascript request on standalone site.
		ajaxP = posted via ajax
		ajaxG = get via ajax
 */
	public function _submit_handler($type)
	{
		$data = ('ajaxG' == $type) ? $_GET	: $_POST;
			
		# validate the form values.
		$post = new Validation($data);
		$post->pre_filter('trim');
		$post->add_rules('body', 'required');
		$post->add_rules('display_name', 'required');
		$post->add_rules('email', 'required');
		
		# on error
		if(!$post->validate())
		{
			# this should rarely happen due to client-side js validation...
			if('ajaxG' == $type)
				die('pandaSubmitRev({"code":5, "msg":"Review Not Added! ('. count($post->errors()) .') Missing Fields"})');			

			$view = new View('add_review');
			$view->errors = $post->errors();
			$view->values = $data;
			return $view;
		}
		
		# on valid submission:
		
		# load user
		$user = ORM::factory('user');
		
		# if user does not exist, create him.
		if(!$user->email_exists($data['email']))
		{
			$user->email = $data['email'];
			$user->display_name = $data['display_name'];
			$user->save();
		}
		
		# add review
		$new_review = ORM::factory('review');
		$new_review->site_id	= $this->site_id;
		$new_review->tag_id		= $data['tag'];
		$new_review->user_id	= $user->id;
		$new_review->body			= $data['body'];
		$new_review->rating		= $data['rating'];
		$new_review->save();

		
		# return what kind of data??
		if('ajaxG' == $type)
			die('pandaSubmitRev({"code":1, "msg":"Yay!"})');

	
		# return status
		$view = new View('submit_status');
		$view->success = true;
		return $view;
	}


/*
 * ajax handler
 */ 	
	public function _ajax()
	{
		# get reviews
		if(isset($_GET['tag']))
			die($this->get_reviews());
			
			
		# submit a review via POST, 
		if($_POST)
		{
			die($this->_submit_handler('ajaxP'));
		}

		
		die('invalid data');
	}	
	
	
	
	
	
	public function __call($method, $arguments)
	{
		// Disable auto-rendering
		$this->auto_render = FALSE;

		// By defining a __call method, all pages routed to this controller
		// that result in 404 errors will be handled by this method, instead of
		// being displayed as "Page Not Found" errors.
		echo 'This text is generated by __call. If you expected the index page, you need to use: welcome/index/'.substr(Router::$current_uri, 8);
	}

} // End home Controller