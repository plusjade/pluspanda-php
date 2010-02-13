<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * public testimonials interaction interface. 
 */
abstract class Testimonials_Template_Controller extends Controller {

  public $shell;
  public $owner;
  public $patron_token;
  
/**
 * shell loading and setup routine.
 */
  public function __construct()
  {
    parent::__construct();

    $this->shell = new View('testimonials/public/shell');
    
    # get the clients apikey from url.
    $url_array = $this->uri->segment_array();
    $client = (empty($url_array['3'])) 
      ? NULL
      : mysql_real_escape_string($url_array['3']);
      
    # is the owner valid?
    $this->owner = ORM::factory('owner', $client);
    if(!$this->owner->loaded)
      $this->render(View::factory('testimonials/public/no_site'));
  }

  
/*
 * output the rendered page
 */
  public function render($content)
  {
    $this->shell->content = $content;
    die($this->shell);
  }  

    
} // End collect template controller