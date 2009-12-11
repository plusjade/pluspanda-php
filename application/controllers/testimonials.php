<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Renders the testimonials Customer Reviews Engine.
 * 3 types of data output formats:
		1. Standalone js-disabled Mode.
				if no javascript, functions as normal, outputs complete views.
		2. Standalone Ajax Mode.
				updates via ajax, only outputs data view.
		3. Widget Ajax via JSONP.
				called externally outputs raw json to be formatted other end.
 */
class Testimonials_Controller extends Controller {

	public $active_tag;
	public $active_sort;
	public $active_page;
	public $is_api = FALSE;

	
	public function __construct($site=NULL, $type=FALSE)
	{
		/*
		$site = ORM::factory('site',ROOTSITEID);
		if(empty($site))
			die('invalid');
		*/	
		parent::__construct();
	
			
		$this->site				= $site;
		$this->apikey			= $site->id;
		$this->site_name	= $site->subdomain;
		$this->site_id		= $site->id;
		$this->theme			= (empty($site->theme)) ? 'gray' : $site->theme;

		
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
			$add_review = new View('testimonials/add_testimonial');
			$add_review->tags = $this->site->tags->select_list('id','name');
		}

		# setup the shell
		$shell = new View('testimonials/shell');
	
		$content = new View('testimonials/wrapper');
		$content->site = $this->site;
		$content->set_global('active_tag', $this->active_tag);
		$content->set_global('active_sort', $this->active_sort);
		$content->get_reviews = $this->get_reviews();
		#$content->add_review = $add_review;
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
			$field = 'tag_id';
			$value = $this->active_tag;
		}
		
		# sort by
		switch($this->active_sort)
		{
			case 'newest':
				$sort = array('created' => 'desc');
				break;
			case 'oldest':
				$sort = array('created' => 'asc');
				break;
		}

		# get full count of reviews for this tag.
		$total_testimonials = ORM::factory('testimonial')
		->where($field, $value)
		->orderby($sort)
		->count_all();
		
		# get the appropriate reviews based on page.
		$offset = ($this->active_page*10) - 10;
		$reviews = ORM::factory('testimonial')
		->where($field, $value)
		->orderby($sort)
		->limit(10, $offset)
		->find_all();

		# build the pagination html
		$pagination = new Pagination(array(
			'base_url'			 => "/?tag=$this->active_tag&sort=$this->active_sort&page=",
			'current_page'	 => $this->active_page, 
			'total_items'    => $total_testimonials,
			'style'          => 'testimonials' ,
			'items_per_page' => 10
			
		));
		
		# Return Standalone Ajax - 
		# reviews_data (sorters & pagination).
		if(!$this->is_api AND 'ajax' == $format AND isset($_GET['sort']))
		{
			$view = new View('testimonials/reviews_data');
			$view->reviews = $reviews;
			$view->pagination = $pagination;
			die($view);
		}

		# else we are returning an entirely new tag view.
		
		# Return JSON to widget
		if($this->is_api)
		{
			$review_array = array();
			foreach($reviews as $review)
			{
				$data = $review->as_array();
				$data['name']			= $review->customer->name;
				$data['url']			= $review->customer->url;
				$data['position'] = $review->customer->position;
				$data['tag_name'] = $review->tag->name;
				$review_array[]		= $data;
			}
			$json_reviews = json_encode($review_array);

			#pagination html.
			$pagination = str_replace(array("\n","\r","\t"), '', $pagination);
				
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			#header('Content-type: text/plain');
			header('Content-type: application/json');
			die("pandaDisplayRevs($json_reviews);pandaPages('$pagination')");
		}
		
		# Return New Tag ajax view.
		# or standalone non-ajax.
		$view = new View('testimonials/get_testimonials');
		$view->reviews = $reviews;		
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
		$data = $_POST;
		
		# validate the form values.
		$post = new Validation($data);
		$post->pre_filter('trim');
		$post->add_rules('name', 'required');
		$post->add_rules('email', 'required');
		
		# on error
		if(!$post->validate() OR empty($data['rating']))
		{
			# this should rarely happen due to client-side js validation...
			# widget GET error.
			if($this->is_api)
				die('pandaSubmitRsp({"code":5, "msg":"Review Not Added! ('. count($post->errors()) .') Missing Fields"})');			

			$view = new View('testimonials/add_testimonial');
			$view->errors = $post->errors();
			$view->values = $data;
			$view->tags		= $this->site->tags->select_list('id','name');
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
		}
		$customer->company = $data['company'];
		$customer->position = $data['position'];
		$customer->url = $data['url'];
		$customer->save();
	
		# add testimonial
		$new_testimonial = ORM::factory('testimonial');
		$new_testimonial->site_id			= $this->site_id;
		$new_testimonial->tag_id			= $data['tag'];
		$new_testimonial->customer_id	= $customer->id;
		$new_testimonial->body_edit		= json_encode($data['info']);
		$new_testimonial->rating			= $data['rating'];
		$new_testimonial->save();

		# return what kind of data??
		
		# widget GET 
		if($this->is_api)
			die('pandaSubmitRsp({"code":1, "msg":"Yay!"})');

	
		# stadalone return status
		$view = new View('testimonials/status');
		$view->success = true;
		return $view;
		
		# cross-site post data can't be returned =(
	}

	
	
/*
 * build the embeddable widget javascript environment.
 * cache the result
 */
	private function widget()
	{	
		$keys = array("\n","\r","\t");
		
		# get all the html interfaces.		
		$tag_list = build_testimonials::tag_list($this->site->tags, $this->active_tag);
		$sorters = build_testimonials::sorters($this->active_tag, $this->active_sort, 'widget');		
		$testimonial_html = build_testimonials::testimonial_html();
		
		# add testimonials form
		# get form questions
		$questions = ORM::factory('question')
			->where('site_id',$this->site_id)
			->find_all();
		$form = new View('testimonials/add_testimonial');
		$form->active_tag = $this->active_tag;
		$form->tags = $this->site->tags->select_list('id','name');
		$form->widget = 'yes';
		$form->questions = $questions;
		
		# build an object to hold the html.
		$html = new StdClass(); 
		$html->tag_list			= str_replace($keys, '', $tag_list);
		$html->sorters			= str_replace($keys, '', $sorters);
		$html->form					= str_replace($keys, '', $form->render());
		$html->iframe				= '<iframe name="panda-iframe" id="panda-iframe" style="display:none;"></iframe>';
		
		# build object to hold status msg views.
		$success	= View::factory('testimonials/status', array('success'=>true))->render();
		$error		= View::factory('testimonials/status', array('success'=>false))->render();
		$status = new StdClass();
		$status->success = str_replace($keys, '', $success);
		$status->error	 = str_replace($keys, '', $error);
		
		# load the widget_js view and place the html as json.
		$widget_js = new View('testimonials/widget_js');
		$widget_js->stylesheet = '<link type="text/css" href="http://'.ROOTDOMAIN.'/static/testimonials/css/'. $this->theme .'.css" media="screen" rel="stylesheet" />';
		$widget_js->url = 'http://' . ROOTDOMAIN ."?apikey=$this->apikey&service=testimonials";
		$widget_js->json_html = json_encode($html);
		$widget_js->json_status = json_encode($status);
		$widget_js->testimonial_html = str_replace($keys, '', $testimonial_html);
		
		
		# output the view then cache the result.		
		ob_start();
		echo $widget_js;
		/*
		file_put_contents(
			DOCROOT . "widget/js/$this->apikey.js",
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
	
	
} // End testimonials Controller
