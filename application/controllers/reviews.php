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
class Reviews_Controller extends Controller {

	public $active_tag;
	public $active_sort;
	public $active_page;
	public $is_api = FALSE;
	public $page_name;
	
	public function __construct($site=null, $page_name='', $type=FALSE)
	{
		if(empty($site))
			die('invalid');
			
		parent::__construct();

		$this->site				= $site;
		$this->apikey			= $site->apikey;
		$this->site_name	= $site->subdomain;
		$this->site_id		= $site->id;
		$this->theme			= (empty($site->theme)) ? 'gray' : $site->theme;
		$this->page_name	= $page_name;
		
		# setup active states.
		$this->active_tag		= (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
		$this->active_sort	= (isset($_GET['sort'])) ? strtolower($_GET['sort']) : 'newest';
		$this->active_page	= (isset($_GET['page']) AND is_numeric($_GET['page'])) ?	 $_GET['page'] : 1;	
		
		if('api' == $type)
		{
			$this->is_api = TRUE;
			$this->_ajax();
		}
	}

/* 
 * The index is only a wrapper for Standalone js-disabled mode mode.
 * any ajax or widget functionality will not use this at all.
 */
	public function index()
	{	
		if($_POST)
			$add_review = self::submit_handler('normal');
		else
		{
			$add_review = new View('reviews/add_review');
			$add_review->page_name = $this->page_name;
			$add_review->categories = $this->site->categories->select_list('id','name');
			$add_review->values = array(
				'body'	=>'',
				'name'	=> '',
				'email'	=> ''
			);
		}

		# setup the shell
		$shell = new View('reviews/shell');
	
		$content = new View('reviews/wrapper');
		$content->site = $this->site;
		$content->set_global('active_tag', $this->active_tag);
		$content->set_global('active_sort', $this->active_sort);
		$content->get_reviews = $this->get_reviews();
		$content->add_review = $add_review;
		$shell->content = $content;
		echo $shell->render();
	}

	
/* ------------- modular methods (ajaxable) -------------  */
	
/*
 * get the reviews data depending on how we are asking for it.
 */
	private function get_reviews($format=NULL)
	{
		# defaults
		$field	= 'site_id';
		$value	= $this->site_id;
		$sort		= array('created' => 'desc');

		# filter by tag
		if(is_numeric($this->active_tag))
		{
			$field = 'category_id';
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
		
		# get the appropriate reviews based on page.
		$offset = ($this->active_page*10) - 10;
		$reviews = ORM::factory('review')
		->where($field, $value)
		->orderby($sort)
		->limit(10, $offset)
		->find_all();

		# build the pagination html
		$pagination = new Pagination(array(
			'base_url'			 => "/$this->page_name?tag=$this->active_tag&sort=$this->active_sort&page=",
			'current_page'	 => $this->active_page, 
			'total_items'    => $total_reviews,
			'style'          => 'reviews' ,
			'items_per_page' => 10
			
		));
		
		# Return Standalone Ajax - 
		# reviews_data (sorters & pagination).
		if(!$this->is_api AND 'ajax' == $format AND isset($_GET['sort']))
		{
			$view = new View('reviews/reviews_data');
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
		if($this->is_api)
		{
			$review_array = array();
			foreach($reviews as $review)
			{
				$data = $review->as_array();
				$data['name'] = $review->customer->name;
				$data['category_name'] = $review->category->name;
				$review_array[] = $data;
			}

			$json_reviews = json_encode($review_array);
			$json_summary = json_encode($ratings_dist); 
			$pagination		= str_replace(array("\n","\r","\t"), '', $pagination);
				
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: text/plain');
			#header('Content-type: application/json');
			die("pandaDisplayRevs($json_reviews);pandaDisplaySum($json_summary);pandaPages('$pagination')");
		}
		
		# Return New Tag ajax view.
		# or standalone non-ajax.
				
		$view = new View('reviews/get_reviews');
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
		ajaxG = GET via widget
 */
	private function submit_handler()
	{
		$data = ($this->is_api) ? $_GET : $_POST;

		# validate the form values.
		$post = new Validation($data);
		$post->pre_filter('trim');
		$post->add_rules('body', 'required');
		$post->add_rules('name', 'required');
		$post->add_rules('email', 'required');
		
		# on error
		if(!$post->validate() OR empty($data['rating']))
		{
			# this should rarely happen due to client-side js validation...
			# widget GET error.
			if($this->is_api)
				die('pandaSubmitRsp({"code":5, "msg":"Review Not Added! ('. count($post->errors()) .') Missing Fields"})');			

			$view = new View('reviews/add_review');
			$view->errors = $post->errors();
			$view->values = $data;
			$view->categories		= $this->site->categories->select_list('id','name');
			$view->page_name = $this->page_name;
			return $view;
		}
		
		# on valid submission:
		
		# load customer
		$customer = ORM::factory('customer')
			->where('site_id', $this->site_id)
			->find($data['email']);
		
		# if customer does not exist, create him.
		if(!$customer->loaded)
		{
			$customer = ORM::factory('customer');
			$customer->site_id = $this->site_id;
			$customer->email = $data['email'];
			$customer->name = $data['name'];
			$customer->save();
		}
		
		# add review
		$new_review = ORM::factory('review');
		$new_review->site_id	= $this->site_id;
		$new_review->category_id	= $data['category'];
		$new_review->customer_id	= $customer->id;
		$new_review->body			= $data['body'];
		$new_review->rating		= $data['rating'];
		$new_review->save();

		# return what kind of data??
		
		# widget GET 
		if($this->is_api)
			die('pandaSubmitRsp({"code":1, "msg":"Yay!"})');

	
		# stadalone return status
		$view = new View('common/status');
		$view->success = TRUE;
		$view->type = 'review';
		return $view;
	}

	
	
/*
 * build the embeddable widget javascript environment.
 * cache the result
 */
	private function widget()
	{			
		$keys = array("\n","\r","\t");

		# get all the html interfaces.		
		$tag_list			= build::tag_filter($this->site->categories, $this->active_tag);
		$add_wrapper	= build::add_wrapper();
		$summary			= build::summary(array(1));
		$sorters			= build::sorters($this->active_tag, $this->active_sort,'', 'widget');
		$review_html	= build::review_html();
		
		# add review form
		$form = new View('reviews/add_review');
		$form->active_tag = $this->active_tag;
		$form->categories = $this->site->categories->select_list('id','name');
		$form->widget = 'yes';
		
		
		# build an object to hold the html.
		$html = new StdClass(); 
		$html->tag_list			= str_replace($keys, '', $tag_list);
		$html->add_wrapper	= str_replace($keys, '', $add_wrapper);
		$html->summary			= str_replace($keys, '', $summary);
		$html->form					= str_replace($keys, '', $form->render());
		$html->sorters			= str_replace($keys, '', $sorters);
		$html->iframe				= '<iframe name="panda-iframe" id="panda-iframe" style="display:none"></iframe>';

		# build object to hold status msg views.
		$success	= View::factory('common/status', array('success'=>TRUE, 'type'=>'review'))->render();
		$error		= View::factory('common/status', array('success'=>FALSE, 'type'=>'review'))->render();
		$status = new StdClass();
		$status->success = str_replace($keys, '', $success);
		$status->error	 = str_replace($keys, '', $error);
		
		# load the widget_js view and place the html as json.
		$widget_js = new View('reviews/widget_js');
		$widget_js->url = url::site() ."?apikey=$this->apikey&service=reviews";
		$widget_js->stylesheet = '<link type="text/css" href="'. url::site() .'/static/widget/css/'. $this->theme .'.css" media="screen" rel="stylesheet" />';
		$widget_js->review_html = str_replace($keys, '', $review_html);
		
		$widget_js->json_html = json_encode($html);
		$widget_js->json_status = json_encode($status);

		# output the view then cache the result.		
		ob_start();
		echo $widget_js;
		
		$dir = paths::data_dir($this->site_id);
		if(!is_dir("$dir/rvs"))
			mkdir("$dir/rvs");
		if(!is_dir("$dir/rvs/js"))
			mkdir("$dir/rvs/js");
			
			/*
		file_put_contents(
			paths::js_cache($this->site_id,'reviews'),
			ob_get_contents()."\n//cached ".date('m.d.y g:ia e')
		);
		*/
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
		if(isset($_GET['fetch']) AND 'reviews' == $_GET['fetch'])
			die($this->widget());
			
		# submit a review via GET, return json status
		if(isset($_GET['submit']) AND 'review' == $_GET['submit'])			
			die($this->submit_handler());
			
		# get reviews in json
		if(isset($_GET['tag']))
			die($this->get_reviews('ajax'));
			
		die('invalid parameters');
	}		
	
	
} // End reviews Controller
