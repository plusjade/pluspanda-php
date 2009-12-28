<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage the review categories. (admin mode)
  */
  
 class Manage_Controller extends Admin_Template_Controller {

 
  public $active_tag;
  public $active_rating;
  public $active_range;
  public $active_page;
  
  public function __construct()
  {      
    parent::__construct();
    
    $this->active_tag     = (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
    $this->active_rating  = (isset($_GET['rating'])) ? $_GET['rating'] : 'all';
    $this->active_range   = (isset($_GET['range'])) ? $_GET['range'] : 'all';
    $this->active_page    = (isset($_GET['page']) AND is_numeric($_GET['page'])) ?   $_GET['page'] : 1;
    
  }
  
/*
 * manage reviewable categories.
 * this is the public wrapper.
 */
  public function index($action=FALSE)
  {  
    $content = new View('admin/reviews/wrapper');


    # carry out the action so view is up-to-date.
    if($action)
      $content->response = alerts::display($this->$action());
  
    $site = ORM::factory('site', $this->site_id);
    
    $content->categories  = r_build::tag_select_list($site->tags, $this->active_tag, array('all'=>'All'));
    $content->ratings     = r_build::rating_select_list($this->active_rating);
    $content->range       = r_build::range_select_list($this->active_range);
    $content->reviews     = $this->get_reviews();

    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'reviews';
    $this->shell->service = 'reviews';
    die($this->shell);
  }


  
/*
 * get the reviews data
 */
  private function get_reviews()
  {
    # defaults
    $field  = 'site_id';
    $value  = $this->site_id;
    $sort    = array('created' => 'desc');
    $where = array('flag_id' => 0);
    
    # filter by tag
    if(is_numeric($this->active_tag))
    {
      $field = 'tag_id';
      $value = $this->active_tag;
    }
    elseif('flagged' == $this->active_tag)
    {
      $where['flag_id'] = '!= 0';
    }
    $where[$field] = $value;
    
    # filter by rating
    if(is_numeric($this->active_rating))
    {
      $where['rating'] = $this->active_rating;
    }
  
    $now = time();
    $day = 86400;    # seconds in day

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
    ->with(null)
    ->where($where)
    ->orderby($sort)
    ->limit(10, $offset)
    ->find_all();

    # build the pagination html
    $pagination = new Pagination(array(
      'base_url'       => "/admin/reviews/manage?tag=$this->active_tag&rating=$this->active_rating&range=$this->active_range&page=",
      'current_page'   => $this->active_page, 
      'total_items'    => $total_reviews,
      'style'          => 'digg' ,
      'items_per_page' => 10
    ));
    

    $view = ('flagged' == $this->active_tag)
      ? new View('admin/reviews/flags_data')
      : new View('admin/reviews/data');
      
    $view->reviews = $reviews;
    $view->pagination = $pagination;
    return $view;
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
