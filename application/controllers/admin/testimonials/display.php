<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage customers. (admin mode)
  */
  
 class Display_Controller extends Admin_Template_Controller {

  public function __construct()
  {
    parent::__construct();
    
    $this->rsp = Response::instance();
  }
  
  public function index()
  {
    $content = new View("admin/testimonials/display");
    $content->embed_code = t_build::embed_code($this->site->apikey, NULL, FALSE);
    
    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active  = 'display';
    $this->shell->service = 'testimonials';
    die($this->shell);
  
  }

  
  
/*
 * save the display settings
 */
  public function save()
  {
    $theme = (isset($_GET['theme'])) ? $_GET['theme'] : null;
    $this->site->theme = $theme;
    $this->site->save();
  
    $this->rsp->status = 'success';
    $this->rsp->msg = 'Theme Saved!';
    $this->rsp->send(); 
  }
  
  
  public function reviews()
  {
    $content = new View('admin/widget');
    $content->embed_code = r_build::embed_code($this->site->apikey, NULL, FALSE);

    # carry out the action so view is up-to-date.
    #if($action)
      #$content->response = alerts::display($this->$action());
  
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'widget';
    $this->shell->service = 'reviews';
    die($this->shell);
  }
  
  public function testimonials()
  {
    $content = new View('admin/widget');
    $content->embed_code = t_build::embed_code($this->site->apikey, NULL, FALSE);
    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'widget';
    $this->shell->service = 'testimonials';
    die($this->shell);
  }  
  
  
  
} // End widget Controller
