<?php defined('SYSPATH') OR die('No direct access allowed.');

class Site_Model extends ORM {
  
  // Relationships
  protected $has_and_belongs_to_many = array('owners');
  protected $has_many = array('categories', 'tags');


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
      $this->created = time();
      $this->apikey = text::random('alnum', 8);
      
      # copy stock testimonial css to user data folder
      $src  = DOCROOT .'static/css/testimonials/stock';
      $dest = t_paths::css($this->apikey);
      dir::copy($src, $dest);
    }
    return parent::save();
  }
  


  /**
   * Tests if a username exists in the database. This can be used as a
   * Valdidation rule.
   *
   * @param   mixed    id to check
   * @return  boolean
   */
  public function subdomain_exists($id)
  {
    return (bool) $this->db
      ->where($this->unique_key($id), $id)
      ->count_records($this->table_name);
  }

  
  
  /**
   * Allows a model to be loaded by subdomain or custom_domain
   */
  public function unique_key($id)
  {
    if ( ! empty($id) AND is_string($id) AND ! ctype_digit($id))
    {
      return 'apikey';
    }

    return parent::unique_key($id);
  }
  
  

} // End site Model