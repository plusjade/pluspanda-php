<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * testomoials dashboard
 */

 class Dashboard_Controller extends Admin_Template_Controller {

  public function __construct()
  {      
    parent::__construct();
  }


/*
 * displays dashboard for logged-in users.
 */
  public function index()
  {
    $content = new View("admin/testimonials/dashboard");

    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'dashboard';
    $this->shell->service = 'testimonials';
    die($this->shell);
  }

  
  
} // End testimonials dashboard Controller
