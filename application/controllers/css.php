<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Compile and manage javascript files for both live and admin sessions.
 * The javascripts scope apply to all site functionality.
 */
 
class Css_Controller {

/*
 * css bundle for main marketing site. 
 */
  public static function marketing()
  {
    header('Content-type: text/css');
    header("Expires: Sat, 26 Jul 1995 05:00:00 GMT");  

    $files = array(
      '/css/marketing/global.css',
      '/css/marketing/pages.css',
      '/css/marketing/forum.css',
    );
    
    ob_start();
    foreach($files as $file)
      if(file_exists(DOCROOT . "static/$file"))
        readfile(DOCROOT . "static/$file");

    die;
  }

/*
 * css bundle for main marketing site. 
 */
  public static function admin()
  {
    header('Content-type: text/css');
    header("Expires: Sat, 26 Jul 1995 05:00:00 GMT");  

    $files = array(
      '/css/common/reset.css',
      '/css/admin/global.css',
      '/css/admin/pages.css',
      '/css/testimonials/edit.css',
      '/css/common/buttons.css',
      '/css/common/facebox.css',
      '/css/common/jcrop.css',
    );
    
    ob_start();
    foreach($files as $file)
      if(file_exists(DOCROOT . "static/$file"))
        readfile(DOCROOT . "static/$file");

    die;
  }
  
  
/*
 * css bundle for main marketing site. 
 */
  public static function t_collect()
  {
    header('Content-type: text/css');
    header("Expires: Sat, 26 Jul 1995 05:00:00 GMT");  

    $files = array(
      '/css/common/reset.css',
      '/css/collect/global.css',
      '/css/testimonials/edit.css',
      '/css/common/facebox.css',
    );
    
    ob_start();
    foreach($files as $file)
      if(file_exists(DOCROOT . "static/$file"))
        readfile(DOCROOT . "static/$file");

    die;
  }
  
  
  public function __call($args, $method)
  {
    Event::run('system.404');
  }
  

  
} /* End  */
