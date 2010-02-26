<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * all the marketing site pages are here.
 */

 class Marketing_Controller extends Marketing_Template_Controller {

  
  public function __construct()
  {      
    parent::__construct();

    # get the page_name.
    $url_array = $this->uri->segment_array();
    $page_name = (empty($url_array['1'])) 
      ? NULL
      : $url_array['1'];
    
    $this->shell->active  = $page_name;
    
    # route the page_name
    if(empty($page_name) OR 'home' == $page_name)
      $this->index();

    $pages = array('pricing','cases','faq','contact');
    if(in_array($page_name, $pages))
      $this->$page_name();
    else
      $this->_custom_404();
  }


/*
 * plupanda.com homepage.
 */
 private function index()
 {
    $this->shell->meta = 'Embedable customer testimonials and reviews for your business website.';
    $this->shell->content = new View('marketing/testimonials/home');    
    $this->shell->title = 'Easily Collect, Manage, and Display Customer Testimonials On Your Website';
    die($this->shell);
 }
 

/*
 * display start page 
 * and handle the create account logic
 */
 private function pricing()
 {
    $this->shell->meta = 'Plans and pricing for testimonial and review layouts and templates for your website';
    $this->shell->content = new View('marketing/testimonials/start');
    $this->shell->title = 'Plans and Pricing';
    
    if(empty($_POST))
      die($this->shell);
      
    # handle the POST.
    $this->shell->content->values = $_POST;

    $post = new Validation($_POST);
    $post->pre_filter('trim');
    $post->add_rules('email', 'required', 'valid::email'); 
    $post->add_rules('password', 'required', 'matches[password2]', 'valid::alpha_dash');  
    if(!$post->validate())
    {
      $this->shell->content->errors = $post->errors();
      die($this->shell);
    }
    
    $new_owner = ORM::factory('owner');
    
    # unique email.
    if(!$new_owner->email_available($_POST['email']))
    {
      $this->shell->content->errors = 'Email Already Exists!';
      die($this->shell);      
    }

    $new_owner->email     = $_POST['email'];
    $new_owner->password  = $_POST['password'];
    $new_owner->save();
  
    
    # log the user in and take to admin
    $this->auth->force_login($new_owner);
    url::redirect('/admin/login');
 }
 
/*
 * display demo page.
 */
  private function cases()
  {
    $this->shell->content = new View('marketing/testimonials/cases');
    $this->shell->title = 'Tons of businesses use customer testimonials as a proven marketing strategy';
    die($this->shell);
  }

/*
 * display faq page.
 */
  private function faq()
  {
    $this->shell->meta = 'Pluspanda website testimonial template builder frequently asked questions';
    $this->shell->content = new View('marketing/testimonials/faq');
    $this->shell->title = 'Frequenty Asked Questions | PlusPanda';
    die($this->shell);
  }
  
/*
 * display contact page.
 */
  private function contact()
  {
    $this->shell->meta = 'Pluspanda website testimonials builder contact information';
    $this->shell->content = new View('marketing/contact');
    $this->shell->title = 'Contact me';
    die($this->shell);
  }
  
/*
 * 404 page
 */
  public function _custom_404()
  {
    header("HTTP/1.0 404 Not Found");
    $this->shell->title = '404 not found';
    $this->shell->content = new View('marketing/404');    
    die($this->shell);
  }
    
} // End marketing Controller
