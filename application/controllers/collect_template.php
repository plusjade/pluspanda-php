<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * public client collect template 
 */
 abstract class Collect_Template_Controller extends Controller {

	public $shell;
  public $site;
  public $patron_token;
  
/**
 * shell loading and setup routine.
 */
  public function __construct()
  {
    parent::__construct();

    $this->shell = new View('collect/shell');
    
    # get the clients name from url.
    $url_array = $this->uri->segment_array();
    $client = (empty($url_array['3'])) 
      ? NULL
      : mysql_real_escape_string($url_array['3']);
      
    # is the site valid?
    $this->site = ORM::factory('site', $client);
    if(!$this->site->loaded)
      $this->render(View::factory('collect/no_site'));
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