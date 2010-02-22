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
  public function index()
  {  
    $content = new View('admin/account_wrapper');

    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'account';
    die($this->shell);
  }

/*
 * save a guest account 
 */
  public function save()
  {
    if(!$_POST)
      die;
    
    $this->rsp = Response::instance();

    if(!valid::email($_POST['email']))
    {
      $this->rsp->msg = 'Invalid Email!';
      $this->rsp->send(); 
    }
    
    $pw = text::random('alnum', 8);
    $this->owner->email = $_POST['email'];
    $this->owner->password = $pw;
    $this->owner->save();

    $replyto = 'unknown';
    $body =
      "Hi there, thanks for saving your progess over at http://pluspanda.com \r\n"
      ."Your auto-generated password is: $pw \r\n"
      ."You should change this password as soon as you get a chance!\r\n\n"
      ."Thank you! - Jade from pluspanda";
    
    # to do FIX THE HEADERS.
    $subject = 'Your Pluspanda account information =)';      
    $headers = "From: welcome@pluspanda.com \r\n" .
      "Reply-To: Jade \r\n" .
      'X-Mailer: PHP/' . phpversion();
      
    mail($_POST['email'], $subject, $body, $headers);


    $this->rsp->status = 'success';
    $this->rsp->msg = 'Thanks, Account Saved!';
    $this->rsp->send(); 
  }
  
  
  
  public function change_password()
  {
    if(!$_POST)
      die;
  
      $old_pw  = $_POST['old_pw'];
      $salt    = $this->auth->find_salt($this->owner->password);
      $old_pw  = $this->auth->hash_password($old_pw, $salt);

      $this->rsp = Response::instance();
      
      if($old_pw == $this->owner->password)
      {
        $this->owner->password = $_POST['new_pw'];
        $this->owner->save();
        
        $this->rsp->status = 'success';
        $this->rsp->msg = 'Password Changed!';
        $this->rsp->send(); 
      }
      
      $this->rsp->msg = 'Invalid Password!';
      $this->rsp->send(); 
  }
  
  
  
  
} // End account Controller
