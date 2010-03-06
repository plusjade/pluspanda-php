<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * track external sites calling the api.
 */
 
class Track_Controller {

/*
 * javascript bundle for public testimonial interactions 
 */
  public function index()
  {
    if(isset($_GET['key']) AND isset($_GET['url']))
    {
      $panda = strtolower(trim(url::site(),'/'));
      $url   = strtolower($_GET['url']);
      if(FALSE === strpos($url, $panda))
      {
        $log = ORM::factory('log');
        $log->apikey = $_GET['key'];
        $log->url    = $url;
        $log->save();
        die('logged');
      }
    }
    die('not logged');
  }



  public function __call($args, $method)
  {
    Event::run('system.404');
  }
  

  
} /* End  */
