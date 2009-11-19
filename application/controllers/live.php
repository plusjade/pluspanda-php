<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Renders the Live Customer Reviews Engine.
 * 3 types of data output formats:
		1. Standalone js-disabled Mode.
				if no javascript, functions as normal, outputs complete views.
		2. Standalone Ajax Mode.
				updates via ajax, only outputs data view.
		3. Widget Ajax via JSONP.
				called externally outputs raw json to be formatted other end.
 */
class Live_Controller extends Controller {

	public $active_tag;
	public $active_sort;
	public $shell;
	
	public function __construct()
	{
		parent::__construct();

		# setup the shell
		$this->shell = new View('live/shell');
		# setup active states.
		$this->active_tag = (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
		$this->active_sort = (isset($_GET['sort'])) ? strtolower($_GET['sort']) : 'newest';
		$this->active_page = (isset($_GET['page']) AND is_numeric($_GET['page'])) ?	 $_GET['page'] : 1;
	
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
			$add_review = new View('live/add_review');
			$add_review->tags = $site->tags->select_list('id','name');
			$add_review->values = array(
				'body'					=>'',
				'display_name'	=> '',
				'email'					=> ''
			);
		}

		$content = new View('live/wrapper');
		$content->site = $site;
		$content->set_global('active_tag', $this->active_tag);
		$content->set_global('active_sort', $this->active_sort);
		$content->get_reviews = $this->get_reviews();
		$content->add_review = $add_review;
		$this->shell->content = $content;
		echo $this->shell->render();
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
		if(is_numeric($this->active_tag))
		{
			$field = 'tag_id';
			$value = $this->active_tag;
		}
		
		# sort by
		switch($this->active_sort)
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

		# get full count of reviews for this tag.
		$total_reviews = ORM::factory('review')
		->where($field, $value)
		->orderby($sort)
		->count_all();
		
		# if pagination
		$offset = ($this->active_page*10) - 10;

		# get the appropriate reviews based on page.
		$reviews = ORM::factory('review')
		->where($field, $value)
		->orderby($sort)
		->limit(10, $offset)
		->find_all();

		# build the pagination html
		$pagination = new Pagination(array(
			'base_url'			 => "/?tag=$this->active_tag&sort=$this->active_sort&page=",
			'current_page'	 => $this->active_page, 
			'total_items'    => $total_reviews,
			'style'          => 'digg' ,
			'items_per_page' => 10
			
		));
		
		# Return Standalone Ajax - reviews_data (sorters & pagination).
		if(isset($_GET['ajax_output']) AND 'reviews' == $_GET['ajax_output'])
		{
			$view = new View('live/reviews_data');
			$view->reviews = $reviews;
			$view->pagination = $pagination;
			die($view);
		}

		# else we are returning an entirely new tag view.
		
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
			

		# Return JSON to widget
		if('json' == $format)
		{
			$review_array = array();
			foreach($reviews as $review)
			{
				$data = $review->as_array();
				$data['display_name'] = $review->customer->display_name;
				$data['tag_name'] = $review->tag->name;
				$review_array[] = $data;
			}
			# debug: http://test.localhost.net/?tag=1&sort=highest&format=json&jsoncallback=pandaLoadRev
			# echo kohana::debug($review_array);die();
			
			$json_reviews = json_encode($review_array);
			$json_summary = json_encode($ratings_dist); 

			#pagination html.
			$pagination = ereg_replace("[\n\r\t]", '', $pagination);
			
			
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: text/plain');
			#header('Content-type: application/json');
			die("pandaDisplayRevs($json_reviews);pandaDisplaySum($json_summary);pandaPages('$pagination')");
		}

		# Return standalone non-ajax.
				
		$view = new View('live/get_reviews');
		$view->reviews = $reviews;		
		$view->ratings_dist = $ratings_dist;
		$view->pagination = $pagination;
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
			
			$view = new View('live/add_review');
			$view->errors = $post->errors();
			$view->values = $data;
			$view->tags		= $site->tags->select_list('id','name');
			return $view;
		}
		
		# on valid submission:
		
		# load customer
		$customer = ORM::factory('customer');
		
		# if customer does not exist, create him.
		if(!$customer->email_exists($data['email']))
		{
			$customer->site_id = $this->site_id;
			$customer->email = $data['email'];
			$customer->display_name = $data['display_name'];
			$customer->save();
		}
		
		# add review
		$new_review = ORM::factory('review');
		$new_review->site_id	= $this->site_id;
		$new_review->tag_id		= $data['tag'];
		$new_review->customer_id	= $customer->id;
		$new_review->body			= $data['body'];
		$new_review->rating		= $data['rating'];
		$new_review->save();

		# return what kind of data??
		
		# widget GET 
		if('ajaxG' == $type)
			die('pandaSubmitRsp({"code":1, "msg":"Yay!"})');

	
		# stadalone return status
		$view = new View('live/status');
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
