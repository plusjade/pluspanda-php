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
    $settings = json_decode($this->owner->tconfig->form);
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
    $this->shell->sub_menu = array(
      array('main', '/admin/testimonials/manage','Main Panel',''),    
      array('collect', '/admin/testimonials/form','Collect Form',''),
      array('help', '#help','(help)',''),
    );
    $this->active  = 'manage';
    $this->sub_active = 'collect';
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
    
    
    $this->owner->tconfig->form = json_encode($settings);
    $this->owner->tconfig->msg = $_POST['msg'];
    $this->owner->tconfig->save();
    
    $this->rsp->status = 'success';
    $this->rsp->msg = 'Form Settings Saved!';
    $this->rsp->send(); 
  }
  

  
} // End widget Controller
