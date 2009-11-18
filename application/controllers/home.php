<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
	
 * 3 types of data output formats:
		1. Standalone js-disabled Mode.
				if no javascript, functions as normal, outputs complete views.
		2. Standalone Ajax Mode.
				updates via ajax, only outputs data view.
		3. Widget Ajax via JSONP.
				called externally outputs raw json to be formatted other end.
 */
class Home_Controller extends Controller {

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
 * The index is only a wrapper for Standalone js-disabled mode mode.
 * any ajax or widget functionality will not use this at all.
 */
	public function index()
	{
		$site = ORM::factory('site', $this->site_id);

		if($_POST)
			$add_review = self::_submit_handler('normal');
		else
		{
			$add_review = new View('add_review');
			$add_review->tags = $site->tags->select_list('id','name');
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
		echo $this->template->render();
	}

	
/* ------------- modular methods (ajaxable) -------------  */
	
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
		
		# sort by
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

		# get reviews data.
		$reviews = ORM::factory('review')
		->where($field, $value)
		->orderby($sort)
		->find_all();

		# send as view (for standalone ajax updating).
		if(isset($_GET['ajax_output']) AND 'reviews' == $_GET['ajax_output'])
		{
			$view = new View('reviews_data');
			$view->reviews = $reviews;
			die($view);
		}

		
		# get summary data: TODO distribution as function of time??
		$summary = ORM::factory('review')
		->select('*, COUNT(reviews.id) AS total')
		->where($field, $value)
		->orderby(array('rating' => 'desc'))
		->groupby('rating')
		->find_all();
		# build a ratings distribution array.
		$ratings_dist = array();
		foreach($summary as $rating)
			$ratings_dist[$rating->rating] = $rating->total;
			
			
		### HOW DO WE RETURN IT ??
		
		# return JSON? to widget
		if('json' == $format)
		{
			$review_array = array();
			foreach($reviews as $review)
			{
				$data = $review->as_array();
				$data['display_name'] = $review->user->display_name;
				$data['tag_name'] = $review->tag->name;
				$review_array[] = $data;
			}
			# debug: http://test.localhost.net/?tag=1&sort=highest&format=json&jsoncallback=pandaLoadRev
			# echo kohana::debug($review_array);die();
			
			$json_reviews = json_encode($review_array);
			$json_summary = json_encode($ratings_dist);
			
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: text/plain');
			#header('Content-type: application/json');
			die("pandaDisplayRevs($json_reviews);pandaDisplaySum($json_summary)");
		}

		
		$view = new View('get_reviews');
		$view->reviews = $reviews;		
		$view->ratings_dist = $ratings_dist;
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
		$data = ('ajaxG' == $type) ? $_GET : $_POST;

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
				die('pandaSubmitRsp({"code":5, "msg":"Review Not Added! ('. count($post->errors()) .') Missing Fields"})');			

			# get tags.
			$site = ORM::factory('site', $this->site_id);
			
			$view = new View('add_review');
			$view->errors = $post->errors();
			$view->values = $data;
			$view->tags		= $site->tags->select_list('id','name');
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
		
		# widget GET 
		if('ajaxG' == $type)
			die('pandaSubmitRsp({"code":1, "msg":"Yay!"})');

	
		# stadalone return status
		$view = new View('status');
		$view->success = true;
		return $view;
	}


/*
 * ajax handler. routes ajax calls to appropriate private method.
 */ 	
	public function _ajax()
	{
		# get reviews
		if(isset($_GET['tag']))
			die($this->get_reviews());

		# submit a review via POST, 
		if($_POST)
			die($this->_submit_handler('ajaxP'));

		die('invalid data');
	}	
	
} // End home Controller
