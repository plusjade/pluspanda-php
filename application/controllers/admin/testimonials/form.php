<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * configure the public form
  */
  
 class Form_Controller extends Admin_Template_Controller {

  public function __construct()
  {
    parent::__construct();
    
    $this->rsp = Response::instance();
  }
  
  public function index()
  {
    $settings = json_decode($this->site->tstml);
    if(NULL === $settings)
    {
      $settings = new StdClass;
      $settings->require_key = false;
      $settings->email       = true;
      $settings->meta        = false;
    }

    $content = new View("admin/testimonials/form");
    $content->settings = $settings;


    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active  = 'form';
    die($this->shell);
  
  }

  
  
/*
 * save the display settings
 */
  public function save()
  {
    $key   = trim($_POST['key']);
    $meta  = trim($_POST['meta']);
    
    $settings = new StdClass;
    $settings->require_key = (empty($key)) ? false : $key;
    $settings->email       = (isset($_POST['email'])) ? true : false;
    $settings->meta        = (empty($meta)) ? false : $meta;
    
    
    $this->site->tstml = json_encode($settings);
    $this->site->tstml_msg = $_POST['tstml_msg'];
    $this->site->save();
    
    $this->rsp->status = 'success';
    $this->rsp->msg = 'Form Settings Saved!';
    $this->rsp->send(); 
  }
  

  
} // End widget Controller
