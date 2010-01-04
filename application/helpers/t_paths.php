<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * set directory paths so we get delicious D.R.Y.
 */
 
class t_paths_Core {

  const service_dir = 'tstmls';
  const image_dir   = 'img';
  const css_dir     = 'css';
  const js_dir      = 'js';
  
  
/*
 * return the path to the data directory
 * either url path or directory path.
 */
  public static function data($apikey, $type='dir')
  {
    if(!is_dir(DOCROOT . "data/$apikey"))
      mkdir(DOCROOT . "data/$apikey");
      
    if('url' == $type)
      return url::site("/data/$apikey"); 

    return DOCROOT . "data/$apikey";
  }

  
/*
 * return path to the service directory
 * either url path or directory path.
 */  
  public static function service($apikey, $type='dir')
  {
    $service = self::data($apikey, $type) . '/'. self::service_dir;
    
    if('dir' == $type)
      if(!is_dir($service))
        mkdir($service);
        
    return $service;
  }  
  
/*
 * return path to the image directory
 * either url path or directory path.
 */  
  public static function image($apikey, $type='dir')
  {
    $image = self::service($apikey, $type) . '/'. self::image_dir;
    
    if('dir' == $image)
      if(!is_dir($image))
        mkdir($image);
        
    return $image;
  }  
  
/*
 * return path to the image directory
 * either url path or directory path.
 */  
  public static function css($apikey, $type='dir')
  {
    $css = self::service($apikey, $type) . '/'. self::css_dir;
    
    if('dir' == $css)
      if(!is_dir($css))
        mkdir($css);
        
    return $css;
  }  
/*
 * return path to the javascript actual js cache file.
 */  
  public static function js_cache($apikey, $type='dir')
  {
    $js = self::service($apikey, $type) . '/'. self::js_dir;
    
    if('dir' == $js)
      if(!is_dir($js))
        mkdir($js);
        
    return "$js/view.js";
  }  



  
} // end t_paths helper

