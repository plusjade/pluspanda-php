<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * output nice alerts.
 */
 
class navigation_Core {


  public static function render($links, $active)
  {
    if(!$links)
      return;
      
    ob_start();
    echo "<ul>\n";
    foreach($links as $data)
    {
      list($name, $url, $text, $class) = $data;
      $class = (empty($class)) ? '' : $class;
      if($name == $active)
        echo "<li class=\"$class $name\"><a href=\"$url\" class=\"active\">$text</a></li>\n";
      else
        echo "<li class=\"$class $name\"><a href=\"$url\">$text</a></li>\n";
    }
    echo "</ul>\n";
    return ob_get_clean();
  }

  
} // end navigation_Core helper

