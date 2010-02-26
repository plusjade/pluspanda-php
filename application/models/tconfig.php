<?php defined('SYSPATH') OR die('No direct access allowed.');

class Tconfig_Model extends ORM {
  
  // Relationships
  protected $belongs_to = array('owner');

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
      if(empty($this->theme))
        $this->theme = 'list';
        
      if(empty($this->sort))
        $this->sort = 'created';
        
      if(empty($this->per_page))
        $this->per_page = 10;
    }
    return parent::save();
  }
  



} // End tconfig (testimonial config) Model