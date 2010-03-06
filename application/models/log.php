<?php defined('SYSPATH') OR die('No direct access allowed.');

class Log_Model extends ORM {
  
  // Relationships

  public function __set($key, $value)
  {
    parent::__set($key, $value);
  }

  
  /**
   * Overload saving to set the created time and to create a new token
   * when the object is saved.
   */
  public function save()
  {
    if ($this->loaded === FALSE)
    {
      $this->time = time();
    }
    return parent::save();
  }
  



} // End tconfig (testimonial config) Model