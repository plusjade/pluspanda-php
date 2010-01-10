<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Compile and manage javascript files for both live and admin sessions.
 * The javascripts scope apply to all site functionality.
 */
 
class Js_Controller {

/*
 * javascript bundle for public testimonial interactions 
 */
  public static function t_public()
  {
    header('Content-type: text/javascript');
    header("Expires: Sat, 26 Jul 1995 05:00:00 GMT");  

    $files = array(
      'js/common/addon.js',
      'js/common/facebox.js',
      'js/common/jcrop.js',
      'js/common/slider.js',
      'js/testimonials/binds.js',
    );
    
    ob_start();
    foreach($files as $file)
      if(file_exists(DOCROOT . "static/$file"))
        readfile(DOCROOT . "static/$file");

    die;
  }

  
/*
 * javascript bundle for /admin 
 */
  public static function admin()
  {
    header('Content-type: text/javascript');
    header("Expires: Sat, 26 Jul 1995 05:00:00 GMT");  

    $files = array(
      'js/common/addon.js',
      'js/common/jquery.ui.js',
      'js/common/json/json2.js',
      'js/common/facebox.js',
      'js/common/jcrop.js',
      'js/common/binds.js',
      'js/testimonials/binds.js',
      'js/admin/init.js',
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
