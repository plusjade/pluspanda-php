<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * set directory paths so we don't repeat ourselves.
 */
 
class t_paths_Core {

  public static $folder = 'tstmls';
  
/*
 * return the path to the data directory
 * either url path or directory path.
 */
  public static function data($apikey, $type='dir')
  {
    if('url' == $type)
      return url::site("/data/$apikey"); 
    
    if(!is_dir(DOCROOT . "data/$apikey"))
      mkdir(DOCROOT . "data/$apikey");
      
    return DOCROOT . "data/$apikey";
  }

/*
 * return path to the image directory
 * either url path or directory path.
 */  
  public static function image($apikey, $type='dir')
  {
    if('url' == $type)
      return self::data($apikey, 'url') . '/'. self::$folder .'/img';
    
    $dir = self::data($apikey, 'dir');
    $service_dir = $dir .'/'. self::$folder;
    if(!is_dir($service_dir))
      mkdir($service_dir);
    if(!is_dir("$service_dir/img"))
      mkdir("$service_dir/img");

    return "$service_dir/img";
  }  
  
  
  public static function js_cache($apikey)
  {
    $dir = self::data($apikey, 'dir');
    $service_dir = $dir .'/'. self::$folder;

    if(!is_dir($service_dir))
      mkdir($service_dir);
    if(!is_dir("$service_dir/js"))
      mkdir("$service_dir/js");

    return "$service_dir/js/view.js";
  }


  
} // end t_paths helper

