<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* Manage the review categories. (admin mode)
	*/
	
 class Reviews_Controller extends Admin_Interface_Controller {

 
	public $active_tag;
	public $active_rating;
	public $active_range;
	public $active_page;
	
	public function __construct()
	{			
		parent::__construct();
		if(!$this->owner->logged_in())
			url::redirect('/admin');

		$this->active_tag = (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
		$this->active_rating = (isset($_GET['rating'])) ? $_GET['rating'] : 'all';
		$this->active_range = (isset($_GET['range'])) ? $_GET['range'] : 'all';
		$this->active_page = (isset($_GET['page']) AND is_numeric($_GET['page'])) ?	 $_GET['page'] : 1;
		
	}
	
/*
 * manage reviewable categories.
 * this is the public wrapper.
 */
	public function index($action=FALSE)
	{	
		$content = new View('admin/reviews_wrapper');


		# carry out the action so view is up-to-date.
		if($action)
			$content->response = alerts::display($this->$action());
	
		$site = ORM::factory('site', $this->site_id);
		
		$content->categories = build::tag_select_list($site->tags, $this->active_tag, array('all'=>'All'));
		$content->ratings = build::rating_select_list($this->active_rating);
		$content->range = build::range_select_list($this->active_range);
		$content->reviews = $this->get_reviews();

		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->active['reviews'] = 'class="active"';
		$this->shell->active = $this->active;
		die($this->shell);
	}


	
/*
 * get the reviews data
 */
	private function get_reviews()
	{
		# defaults
		$field	= 'site_id';
		$value	= $this->site_id;
		$sort		= array('created' => 'desc');
		$where = array();
		
		# filter by tag
		if(is_numeric($this->active_tag))
		{
			$field = 'tag_id';
			$value = $this->active_tag;
		}
		$where[$field] = $value;
		
		# filter by rating
		if(is_numeric($this->active_rating))
		{
			$where['rating'] = $this->active_rating;
		}
	
		$now = time();
		#number of seconds in ..
		$day = 86400;		

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
		
		# get full count of reviews for this tag.
		$total_reviews = ORM::factory('review')
		->where($where)
		->orderby($sort)
		->count_all();
		
		# if pagination
		$offset = ($this->active_page*10) - 10;

		# get the appropriate reviews based on page.
		$reviews = ORM::factory('review')
		->where($where)
		->orderby($sort)
		->limit(10, $offset)
		->find_all();

		# build the pagination html
		$pagination = new Pagination(array(
			'base_url'			 => "/admin/reviews?tag=$this->active_tag&rating=$this->active_rating&range=$this->active_range&page=",
			'current_page'	 => $this->active_page, 
			'total_items'    => $total_reviews,
			'style'          => 'digg' ,
			'items_per_page' => 10
		));
		

		$view = new View('admin/reviews_data');
		$view->reviews = $reviews;
		$view->pagination = $pagination;
		return $view;
	}		
	
	
/*
 * add a new category
 */
	private function add()
	{
		if($_POST)
		{
			$max = ORM::factory('tag')
				->select('MAX(position) as highest')
				->where('site_id', $this->site_id)
				->find();		
				
			$tag = ORM::factory('tag');
			$tag->site_id = $this->site_id;
			$tag->name = mysql_real_escape_string($_POST['name']);
			$tag->desc = mysql_real_escape_string($_POST['desc']);
			$tag->position = $max->highest+1;
			$tag->save();
			
			return array('success'=>'Category Added.');
		}
		return array('error'=>'Nothing Sent.');
	}


/*
 * delete an existing category
 */
	private function delete()
	{
		if(empty($_GET['tag_id']))
			return array('error'=>'Nothing Sent.');
			
		valid::id_key($_GET['tag_id']);
		
		ORM::factory('tag')
			->where('site_id',$this->site_id)
			->delete($_GET['tag_id']);
		
		return array('success'=>'Category deleted! =(');
	}
	
/*
 * save changes to a category
 */
	private function save()
	{
		if($_POST)
		{
			valid::id_key($_POST['id']);
			$tag = ORM::factory('tag')
				->where('site_id', $this->site_id)
				->find($_POST['id']);
			if(!$tag->loaded)
				return array('error'=>'Category Does not Exist.');
				
			$tag->name = mysql_real_escape_string($_POST['name']);
			$tag->desc = mysql_real_escape_string($_POST['desc']);
			$tag->save();
			
			return array('success'=>'Changes Saved!');
		}
		return array('error'=>'Nothing sent');
	}
	
/*
 * save order positions for categories.
 */
	private function order()
	{
		if(empty($_GET['cat']))
			return array('error'=>'Nothing Sent.');
			
		$db = new Database;
		foreach($_GET['cat'] as $position => $id)
			$db->update('tags', array('position' => "$position"), "id = '$id' AND site_id = '$this->site_id'"); 	
			
		
		return array('success'=>'Order Saved!');
	}

	
/* 
 * centralize this controllers interface
 * Non-ajax calls get wrapped via the index.
 * ajax calls get fast tracked to their method calls.
 */
	public function __call($method, $args)
	{
		# white-list methods.
		# note there are methods we don't want in here.
		# echo kohana::debug(get_class_methods($this));
		if(!in_array($method, get_class_methods($this)))
			die('404');
		
		if(request::is_ajax())
			echo alerts::display($this->$method());
		else
			$this->index($method);
		
		die();
	}
	
} // End categories Controller
