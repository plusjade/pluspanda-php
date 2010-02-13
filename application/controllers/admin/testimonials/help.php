<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * testomoials help page
 */

 class Help_Controller extends Admin_Template_Controller {

  public function __construct()
  {      
    parent::__construct();
  }


/*
 * displays help page
 */
  public function index()
  {
    $content = new View("admin/testimonials/help");

    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->active  = 'help';
    $this->service = 'testimonials';
    die($this->shell);
  }

  
  
} // End testimonials help Controller
