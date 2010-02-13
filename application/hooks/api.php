<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
 * Handles api calls for the widget.
 */
function api()
{  
  # is this an API call?
  if(empty($_GET['apikey']))
    return FALSE;
      
  $allowed  = array('reviews','testimonials');
  $fetch    = FALSE;
  # fetch the widget environments (should be cached)
  if(isset($_GET['fetch']) AND in_array($_GET['fetch'], $allowed))
  {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: text/javascript');
    ob_start();
    
    if(empty($_GET['jquery']))
      readfile(DOCROOT . 'static/js/common/jquery.js');    
    
     # attempt to serve the cache.
    if('reviews' == $_GET['fetch'])
      load_reviews_env();
    elseif('testimonials' == $_GET['fetch'])
      load_testimonials_env();
      
    $fetch = TRUE;
  }
  
  # get the account.    
  $owner = ORM::factory('owner')
    ->where('apikey',$_GET['apikey'])
    ->find();
  if(!$owner->loaded)
    die('invalid api key');
  
  # send to api controller to build/cache js. bye bye!
  if($fetch)
    if('reviews' == $_GET['fetch'])
      new Reviews_Controller($owner, NULL, 'api');
    else
      new Testimonials_Controller($owner, 'api');


  # do some work with the appropriate service api
  if(isset($_GET['service']))
    if('reviews' == $_GET['service'])
      new Reviews_Controller($owner, NULL, 'api');
    elseif('testimonials' == $_GET['service'])
      new Testimonials_Controller($owner, 'api');
    
  die;
  /** default controller is "home" **/
}

 # attempt to serve the cache.
function load_testimonials_env()
{
  $js_cache = t_paths::js_cache($_GET['apikey']);
  if(!file_exists($js_cache) OR !file_exists(t_paths::init_cache()))
    return false;

  readfile($js_cache);
  readfile(t_paths::init_cache());
  die('/*=D*/');
}

 # attempt to serve the cache.
function load_reviews_env()
{
  echo file_get_contents(DOCROOT . 'static/js/common/addon.js');

  $js_cache = r_paths::js_cache($_GET['apikey']);
  if(file_exists($js_cache))
    die(readfile($js_cache));
}



Event::add('system.ready', 'api');
/* end */