<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * perform any necessary work for the new release.
 */

 class Utils_Controller extends Controller {

  
  public function __construct()
  {
    parent::__construct();
    
    if(empty($_GET['pw']) AND 'willow' !== $_GET['pw'])
      die('nothing');
  }

  public function http()
  {
    $testimonials = ORM::factory('testimonial')->find_all();
    foreach($testimonials as $testimonial)
    {
      $testimonial->url = str_replace('http://','', strtolower($testimonial->url));
      $testimonial->save();
    }
    echo 'clean was good';
  }
 

 
 
 
 
} // End utils Controller
