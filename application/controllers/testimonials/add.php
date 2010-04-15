<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Testimonials public add form interface.
 */

class Add_Controller extends Testimonials_Template_Controller {
  
  public function __construct()
  {
    parent::__construct();
    
    $this->index();
  }

/*
 * display and handle the public add form.
 */ 
  public function index()
  {
    $settings = json_decode($this->owner->tconfig->form);
    if(NULL === $settings)
    {
      $settings = new StdClass;
      $settings->email       = true;
      $settings->meta        = false;
      $settings->require_key = false;
    }
    
    $view = new View('testimonials/public/new_form');
    $view->settings = $settings;
    if(isset($_GET))
      $view->values = $_GET;
    
    if($_POST)
    {
      $post = new Validation($_POST);
      $post->pre_filter('trim');
      $post->add_rules('name', 'required');
      
      if($settings->email)
        $post->add_rules('email', 'required', 'valid::email');
      if($settings->require_key)
        $post->add_rules('key', 'required');
      if($settings->meta)
        $post->add_rules('meta', 'required');
        
      if(!$post->validate() OR ($settings->require_key AND $settings->require_key !== $_POST['key']))
      {
        $view->errors = $post->errors();
        $view->values = $_POST;
        $this->render($view);
      }

      $new = ORM::factory('testimonial');
      $new->owner_id = $this->owner->id;
      $new->name = $_POST['name'];
      if($settings->meta)
        $new->meta = $_POST['meta'];
      $new->save();
      
      
      # send new testimonial notification email to owner.
      if(!empty($this->owner->email))
      {
        $body =
          "Hi there, someone created a testimonial using your public pluspanda form.\r\n"
          ."To view and edit it, log in at: http://pluspanda.com/admin/login \r\n"
          ."\r\n"
          ."The name of the submitter is: {$new->name}\r\n"
          ."The submitter is likely editing their testimonial right now so give them some time to finish =)\r\n"
          ."Have questions or need help just email plusjade@gmail.com - Thanks!"
        ;
        
        # to do FIX THE HEADERS.
        $subject = 'you received a new testimonial via pluspanda...';      
        $headers = "From: pluspanda@pluspanda.com \r\n" .
          "Reply-To: Jade \r\n" .
          'X-Mailer: PHP/' . phpversion();
          
        mail($this->owner->email, $subject, $body, $headers);
      }
      
      
      $editor_url = url::site("testimonials/save/{$this->owner->apikey}?ttk={$new->token}");
      url::redirect($editor_url);
    }
    
    $this->render($view);
  }


  
  
} // End testimonials add controller