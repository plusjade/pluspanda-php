<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Renders the testimonials Customer testimonials Engine.
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
		parent::__construct();
	
		$this->site				= $site;
		$this->apikey			= $site->apikey;
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
			$add_testimonial = self::submit_handler('normal');
		else
		{
			$add_testimonial = new View('testimonials/add_testimonial');
			$add_testimonial->tags = $this->site->tags->select_list('id','name');
		}

		# setup the shell
		$shell = new View('testimonials/shell');
	
		$content = new View('testimonials/wrapper');
		$content->site = $this->site;
		$content->set_global('active_tag', $this->active_tag);
		$content->set_global('active_sort', $this->active_sort);
		$content->get_testimonials = $this->get_testimonials();
		#$content->add_testimonial = $add_testimonial;
		$shell->content = $content;
		echo $shell->render();
	}

	
/* ------------- modular methods (ajaxable) -------------  */
	
/*
 * get the testimonials data depending on how we are asking for it.
 */
	private function get_testimonials($format=NULL)
	{
		# defaults
		$where	= array('publish' => 1);
		$sort		= array('created' => 'desc');
		
		# filter by tag
		if(is_numeric($this->active_tag))
			$where['tag_id'] = $this->active_tag;
		else
			$where['site_id'] = $this->site_id;
		
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

		# get full count of testimonials for this tag.
		$total_testimonials = ORM::factory('testimonial')
			->where($where)
			->orderby($sort)
			->count_all();
		
		# get the appropriate testimonials based on page.
		$limit = 2;
		$offset = ($this->active_page*$limit) - $limit;
		$testimonials = ORM::factory('testimonial')
			->where($where)
			->orderby($sort)
			->limit($limit, $offset)
			->find_all();
		

		/*
		# build the pagination html
		$pagination = new Pagination(array(
			'base_url'			 => "/?tag=$this->active_tag&sort=$this->active_sort&page=",
			'current_page'	 => $this->active_page, 
			'total_items'    => $total_testimonials,
			'style'          => 'testimonials' ,
			'items_per_page' => $limit
		));
		*/
		
		
		# Return Standalone Ajax - 
		# testimonials_data (sorters & pagination).
		if(!$this->is_api AND 'ajax' == $format AND isset($_GET['sort']))
		{
			$view = new View('testimonials/testimonials_data');
			$view->testimonials = $testimonials;
			$view->pagination = $pagination;
			die($view);
		}

		# else we are returning an entirely new tag view.
		
		# Return JSON to widget
		if($this->is_api)
		{
			$testimonial_array = array();
			foreach($testimonials as $testimonial)
			{
				$data = $testimonial->as_array();
				$data['name']			= $testimonial->customer->name;
				$data['position'] = $testimonial->customer->position;
				$data['company'] 	= $testimonial->customer->company;
				$data['location'] = $testimonial->customer->location;
				$data['url']			= $testimonial->customer->url;
				$data['tag_name'] = $testimonial->tag->name;
				$testimonial_array[]		= $data;
			}

			# should we specify a next page link?
			if($total_testimonials > $offset + $limit)
			{
				$next_page = $this->active_page+1;
				$page_vars = "'$next_page', '$this->active_tag', '$this->active_sort'";
			}
			else
				$page_vars = '';
			
			
			$json_testimonials = json_encode($testimonial_array);
			#pagination html.
			#$pagination = str_replace(array("\n","\r","\t"), '', $pagination);
				
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			die("pandaDisplayRevs($json_testimonials);pandaPages($page_vars);");
		}
		
		# Return New Tag ajax view.
		# or standalone non-ajax.
		$view = new View('testimonials/get_testimonials');
		$view->testimonials = $testimonials;		
		$view->pagination = $pagination;
		$view->set_global('active_tag', $this->active_tag);
		$view->set_global('active_sort', $this->active_sort);
		return $view;
	}
	
	
/*
 * post testimonial handler.
 * validates and adds the new testimonial to the site.
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
				die('pandaSubmitRsp({"code":5, "msg":"testimonial Not Added! ('. count($post->errors()) .') Missing Fields"})');			

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
		$view = new View('common/status');
		$view->success = true;
		$view->type = 'testimonials';
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
		$tag_list = build_testimonials::tag_list($this->site->tags, $this->active_tag);
		$sorters = build_testimonials::sorters($this->active_tag, $this->active_sort, 'widget');		
		$testimonial_html = build_testimonials::testimonial_html(NULL, $this->site_id);
			
		# build an object to hold the html.
		$html = new StdClass(); 
		$html->tag_list	= str_replace($keys, '', $tag_list);
		$html->sorters	= str_replace($keys, '', $sorters);

		# load the widget_js view and place the html as json.
		$widget_js = new View('testimonials/widget_js');
		$widget_js->theme = $this->theme;
		$widget_js->apikey = $this->apikey;
		$widget_js->asset_url = paths::testimonial_image_url($this->site_id);
		$widget_js->json_html = json_encode($html);
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
		# submit a testimonial via POST, 
		if($_POST)
			die($this->submit_handler('ajaxP'));

		# fetch the widget environment.
		if(isset($_GET['fetch']) AND 'testimonials' == $_GET['fetch'])
			die($this->widget());
			
		# submit a testimonial via GET, return json status
		if(isset($_GET['submit']) AND 'testimonials' == $_GET['submit'])			
			die($this->submit_handler());
			
		# get testimonials in json
		if(isset($_GET['tag']))
			die($this->get_testimonials('ajax'));
			
		die('invalid api parameters');
	}		
	
	
} // End testimonials Controller
