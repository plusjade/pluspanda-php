<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
 * Handles api calls for the widget.
 */
function api()
{  
  # is this an API call?
  if(empty($_GET['apikey']))
    return;
      
  $allowed  = array('reviews','testimonials');
  # fetch the widget environments (should be cached)
  if(isset($_GET['fetch']) AND in_array($_GET['fetch'], $allowed))
  {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: text/javascript');
    ob_start();
    
    if(empty($_GET['jquery']))
      readfile(DOCROOT . 'static/js/common/jquery.js');    
    
    load_testimonials_env();
  }
  
  # get the account.
  $owner = ORM::factory('owner')
    ->where('apikey',$_GET['apikey'])
    ->find();
  if(!$owner->loaded)
    die('invalid api key');
  
  # send to api controller to handle request. bye bye!
  new Testimonials_Controller($owner, 'api');
  die;
}

 # attempt to serve the cache.
function load_testimonials_env()
{
  $js_cache = t_paths::js_cache($_GET['apikey']);
  if(!file_exists($js_cache) OR !file_exists(t_paths::init_cache()))
    return;

  readfile($js_cache);
  readfile(t_paths::init_cache());
  die('/*=D*/');
}

/** default controller is "home" **/

Event::add('system.ready', 'api');
/* end */