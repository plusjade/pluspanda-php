<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage customers. (admin mode)
  */
  
 class Account_Controller extends Admin_Template_Controller {

  public function __construct()
  {
    parent::__construct();
  }
  
/*
 * manage customers.
 * this is the public wrapper.
 */
  public function index($action=FALSE)
  {  
    $content = new View('admin/account_wrapper');

    # carry out the action so view is up-to-date.
    if($action)
      $content->response = alerts::display($this->$action());
  
    $content->owner = $this->owner;

    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'account';
    die($this->shell);
  }

  
} // End account Controller
