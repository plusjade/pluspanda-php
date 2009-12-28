<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * testomoials dashboard
 */

 class Dashboard_Controller extends Admin_Template_Controller {

  public function __construct()
  {      
    parent::__construct();
  }


/*
 * displays dashboard for logged-in users.
 * executes login logic for non-logged in users.
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
    
    $content = new View("admin/testimonials/dashboard");
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
    $this->shell->service = 'testimonials';
    die($this->shell);
  }

  
  
} // End testimonials dashboard Controller
