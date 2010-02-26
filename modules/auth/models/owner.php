<?php defined('SYSPATH') OR die('No direct access allowed.');

class Owner_Model extends ORM {

  // Relationships
  protected $has_one = array('tconfig');
  #protected $load_with = array('tconfig');
  
  // Columns to ignore
  protected $ignored_columns = array('password_confirm');

  public function __set($key, $value)
  {
    if ($key === 'password')
    {
      // Use Auth to hash the password
      $value = Auth::instance()->hash_password($value);
    }

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
      $this->apikey  = text::random('alnum', 8);
      $this->token   = $this->create_token();
      parent::save();
      
      # auto create a new tconfig.
      $new_tconfig = ORM::factory('tconfig');
      $new_tconfig->owner_id = $this->id;
      $new_tconfig->save();
      
      # add 3 sample testimonials to get the party started.
      $testimonial = ORM::factory('testimonial');
      $testimonial->owner_id   = $this->id;
      $testimonial->name       = 'Stephanie Lo';
      $testimonial->company    = 'World United';
      $testimonial->c_position = 'Founder';
      $testimonial->url        = 'worldunited.com';
      $testimonial->location   = 'Berkeley, CA';
      $testimonial->rating     = 5;
      $testimonial->body       = 'The interface is simple and directed. I have a super busy schedule and did not want to waste any time learning yet another website. Pluspanda values my time. Thanks!';  
      $testimonial->publish    = 1;
      $testimonial->save();
      
      $testimonial->clear();
      $testimonial->owner_id   = $this->id;
      $testimonial->name       = 'John Doe';
      $testimonial->company    = 'Super Company!';
      $testimonial->c_position = 'President';
      $testimonial->url        = 'supercompany.com';
      $testimonial->location   = 'Atlanta, Georgia';
      $testimonial->rating     = 5;
      $testimonial->body       = 'This is a sample testimonial for all to see.';  
      $testimonial->publish    = 1;
      $testimonial->save();
      
      $testimonial->clear();
      $testimonial->owner_id   = $this->id;
      $testimonial->name       = 'Jane Smith';
      $testimonial->company    = 'Widgets R Us';
      $testimonial->c_position = 'Sales Manager';
      $testimonial->url        = 'widgetsrus.com';
      $testimonial->location   = 'Los Angeles, CA';
      $testimonial->rating     = 5;
      $testimonial->body       = 'Pluspanda makes our testimonials look great! Our widget sales our up 200% Thanks Pluspanda!';  
      $testimonial->publish    = 1;
      $testimonial->save();
      
      # copy stock testimonial css to user data folder
      $src  = DOCROOT .'static/css/testimonials/stock';
      $dest = t_paths::css($this->apikey);
      dir::copy($src, $dest);
      
      return;
    }
    return parent::save();
  }
  

  /**
   * Overload delete to include assets
   * when the object is deleted.
   */
  public function delete($id=NULL)
  {
    if ($id === NULL AND $this->loaded)
    {
      # delete testimonial survey questions
      ORM::factory('question')
        ->where(array('owner_id' => $id))
        ->delete_all();
      echo 'survey questions deleted<br/>';
      
      # delete testimonial tags/categories
      ORM::factory('tag')
        ->where(array('owner_id' => $id))
        ->delete_all();
      echo 'testimonial tags deleted<br/>';
      
      # delete tconfigs
      ORM::factory('tconfig')
        ->where(array('owner_id' => $id))
        ->delete_all();
      echo 'tconfig settings deleted<br/>';
      
      # delete all testimonials
      ORM::factory('testimonial')
        ->where(array('owner_id' => $id))
        ->delete_all();
      echo 'testimonials deleted<br/>';
      
      # delete owner data folder.
      $folder = t_paths::data($this->apikey);
      if(dir::remove($folder))
        echo "data directory $folder deleted<br/>";
    }
    
    return parent::delete($id);
  }
  
  
  
  /**
   * Validates login information from an array, and optionally redirects
   * after a successful login.
   *
   * @param  array    values to check
   * @param  string   URI or URL to redirect to
   * @return boolean
   */
  public function login(array & $array, $redirect = FALSE)
  {
    $array = Validation::factory($array)
      ->pre_filter('trim')
      ->add_rules('username', 'required', 'length[4,127]')
      ->add_rules('password', 'required', 'length[5,42]');

    // Login starts out invalid
    $status = FALSE;

    if ($array->validate())
    {
      // Attempt to load the user
      $this->find($array['username']);

      if ($this->loaded AND Auth::instance()->login($this, $array['password']))
      {
        if (is_string($redirect))
        {
          // Redirect after a successful login
          url::redirect($redirect);
        }

        // Login is successful
        $status = TRUE;
      }
      else
      {
        $array->add_error('username', 'invalid');
      }
    }

    return $status;
  }

  /**
   * Validates an array for a matching password and password_confirm field.
   *
   * @param  array    values to check
   * @param  string   save the user if
   * @return boolean
   */
  public function change_password(array & $array, $save = FALSE)
  {
    $array = Validation::factory($array)
      ->pre_filter('trim')
      ->add_rules('password', 'required', 'length[5,127]')
      ->add_rules('password_confirm', 'matches[password]');

    if ($status = $array->validate())
    {
      // Change the password
      $this->password = $array['password'];

      if ($save !== FALSE AND $status = $this->save())
      {
        if (is_string($save))
        {
          // Redirect to the success page
          url::redirect($save);
        }
      }
    }

    return $status;
  }

  /**
   * Tests if a username exists in the database. This can be used as a
   * Valdidation rule.
   *
   * @param   mixed    id to check
   * @return  boolean
   * 
   */
  public function username_exists($id)
  {
    return $this->unique_key_exists($id);
  }

  /**
   * Does the reverse of unique_key_exists() by returning TRUE if user id is available
   * Validation rule.
   *
   * @param    mixed    id to check 
   * @return   boolean
   */
  public function username_available($username)
  {
    return ! $this->unique_key_exists($username);
  }

  /**
   * Does the reverse of unique_key_exists() by returning TRUE if email is available
   * Validation Rule
   *
   * @param string $email 
   * @return void
   */
  public function email_available($email)
  {
    return ! $this->unique_key_exists($email);
  }

  /**
   * Tests if a unique key value exists in the database
   *
   * @param   mixed        value  the value to test
   * @return  boolean
   */
  public function unique_key_exists($value)
  {
    return (bool) $this->db
      ->where($this->unique_key($value), $value)
      ->count_records($this->table_name);
  }

/**
 * Allows a model to be loaded by apikey or email address.
 */
  public function unique_key($id)
  {
    if ( ! empty($id) AND is_string($id) AND ! ctype_digit($id))
    {
      return valid::email($id) ? 'email' : 'apikey';
    }

    return parent::unique_key($id);
  }

  
  /**
   * Finds a new unique token, using a loop to make sure that the token does
   * not already exist in the database. This could potentially become an
   * infinite loop, but the chances of that happening are very unlikely.
   *
   * @return  string
   */
  protected function create_token()
  {
    while (TRUE)
    {
      // Create a random token
      $token = text::random('alnum', 32);

      // Make sure the token does not already exist
      if ($this->db->select('id')->where('token', $token)->get($this->table_name)->count() === 0)
      {
        // A unique token has been found
        return $token;
      }
    }
  }
  
  
  
  
} // End owner Model