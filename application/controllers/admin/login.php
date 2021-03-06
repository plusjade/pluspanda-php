<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * login to admin panel or redirect to dashboard.
 */

 class Login_Controller extends Controller {

  public function __construct()
  {      
    parent::__construct();
    
    $this->owner = Auth::instance();
  }


/*
 * displays dashboard for logged-in users.
 * executes login logic for non-logged in users.
 */
  public function index()
  {
    if($this->owner->logged_in())
      url::redirect('/admin/testimonials/display');

    $login_shell = new View('admin/login_shell');
    $login_shell->content = new View('admin/login');

    if(empty($_POST))
      die($login_shell);      
      
    $post = new Validation($_POST);
    $post->pre_filter('trim');
    $post->add_rules('email', 'required', 'valid::email');
    $post->add_rules('password', 'required', 'valid::alpha_dash');

    # if Post is good, atttempt to log owner in.    
    if($post->validate())
      if($this->owner->login($_POST['email'], $_POST['password'], 0))
      {  
        if(isset($_GET['ref']))
          url::redirect($_GET['ref']);
        
        url::redirect('/admin/testimonials/display');
      }
      
    # error
    $login_shell->content->alert = alerts::display(array('error'=>'Invalid Email or Password.'));
    $login_shell->content->values = $_POST;
    die($login_shell);  
  }



# reset and resend a password
  public function reset()
  {
    if($this->owner->logged_in())
      url::redirect('/admin/testimonials/display');

    $login_shell = new View('admin/login_shell');
    $login_shell->content = new View('admin/reset');

    if(empty($_POST))
      die($login_shell);      
      
    $post = new Validation($_POST);
    $post->pre_filter('trim');
    $post->add_rules('email', 'required', 'valid::email');

    # if Post is good, atttempt to log owner in.    
    if($post->validate())
    {
      $owner =  ORM::factory('owner')->find($_POST['email']);
      if(!$owner->loaded)
        die('email does not have an account');
      
      $pw = text::random('alnum', 8);
      $owner->password = $pw;
      $owner->save();
      
      $replyto = 'unknown';
      $body =
        "Your auto-generated password is: $pw \r\n"
        ."Change your password to something more appropriate by going here:\r\n"
        ."http://pluspanda.com/admin/account?old=$pw \r\n\n"
        ."Thank you! - Jade from pluspanda";
      
      # to do FIX THE HEADERS.
      $subject = 'Your Pluspanda Password Has Been Reset =)';      
      $headers = "From: noreply@pluspanda.com \r\n" .
        "Reply-To: Jade \r\n" .
        'X-Mailer: PHP/' . phpversion();
        
      mail($_POST['email'], $subject, $body, $headers);


      die('Please check your email for your new password!');   
    }
      
    # error
    $login_shell->content->alert = alerts::display(array('error'=>'Invalid Email or Password.'));
    $login_shell->content->values = $_POST;
    die($login_shell);  
  }      

/*
 * NOT IN USE.
 * force an external login from created account screen.
 */
  private function force()
  {
    if(isset($_GET['name']) AND isset($_GET['tkn']))
    {
      $owner = ORM::factory('owner')
        ->where(array(
          'username' => $_GET['name'],
          'token' => $_GET['tkn']
        ))
        ->find();
      if(!$owner->loaded)
        url::redirect('/admin');
        
      if($owner->has(ORM::factory('site', $this->site_id)))
        $this->owner->force_login($_GET['name']);
    }
    
    url::redirect('/admin');
  }
 
 
/*
 * Logs the current user out.
 */ 
  public function logout()
  {
    $this->owner->logout(TRUE);
    url::redirect('/');
  }
  

  
  
  
} // End admin login Controller
