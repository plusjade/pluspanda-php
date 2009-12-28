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
    
    if(empty($_GET['jquery']))
      readfile(DOCROOT . 'static/js/jquery.js');    
    
    if('reviews' == $_GET['fetch'])
      readfile(DOCROOT . 'static/js/addon.js');

    # attempt to serve the cache.
    $js_cache = ('testimonials' == $_GET['fetch'])
      ? t_paths::js_cache($_GET['apikey'])
      : r_paths::js_cache($_GET['apikey']);
      
    if(file_exists($js_cache))
      die(readfile($js_cache));
      
    $fetch = TRUE;
  }
  
  # get the account.    
  $site = ORM::factory('site')
    ->where('apikey',$_GET['apikey'])
    ->find();
  # should we make this a 404 since its an api call?
  if(!$site->loaded)
    die('invalid api key');
  
  # send to api controller to build/cache js. bye bye!
  if($fetch)
    if('reviews' == $_GET['fetch'])
      new Reviews_Controller($site, NULL, 'api');
    else
      new Testimonials_Controller($site, 'api');


  # do some work with the appropriate service api
  if(isset($_GET['service']))
    if('reviews' == $_GET['service'])
      new Reviews_Controller($site, NULL, 'api');
    elseif('testimonials' == $_GET['service'])
      new Testimonials_Controller($site, 'api');
    
  die;
  

  /** default controller is "home" **/
}
Event::add('system.ready', 'api');
/* end */