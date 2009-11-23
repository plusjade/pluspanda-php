<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
	* Manage the review categories. (admin mode)
	*/
	
 class Categories_Controller extends Admin_Interface_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->owner->logged_in())
			url::redirect('/admin');
	}
	
/*
 * manage reviewable categories.
 * this is the public wrapper.
 */
	public function index($action=FALSE)
	{	
		$content = new View('admin/categories_wrapper');
		
		# carry out the action so view is up-to-date.
		if($action)
			$content->response = alerts::display($this->$action());
			
		$site = ORM::factory('site', $this->site_id);
		$content->tags = $site->tags;
		
		if(request::is_ajax())
			die($content);
		
		$this->shell->content = $content;
		$this->active['categories'] = 'class="active"';
		$this->shell->active = $this->active;
		die($this->shell);
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
