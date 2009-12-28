<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * login to admin panel or show dashboard.
 */
 class Dashboard_Controller extends Admin_Template_Controller {

  public function __construct()
  {      
    parent::__construct();
  }


/*
 * display reviews dashboard
 */
  public function index()
  {
    $reviews = ORM::factory('review')
      ->where('site_id',$this->site_id)
      ->orderby('created','desc')
      ->limit(10)
      ->find_all();

    $customers = ORM::factory('customer')
      ->where('site_id',$this->site_id)
      ->orderby('created','desc')
      ->limit(10)
      ->find_all();
    
    $content = new View("admin/reviews/dashboard");
    $content->categories = $this->site->categories;
    $reviews_data = View::factory('admin/reviews/data');
    $reviews_data->reviews = $reviews;
    $reviews_data->pagination='';
    $content->reviews = $reviews_data;
    $content->customers = $customers;
    $content->owner = $this->owner->get_user();
    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'dashboard';
    $this->shell->service = 'reviews';
    die($this->shell);
  }


} // End reviews dashboard controller
