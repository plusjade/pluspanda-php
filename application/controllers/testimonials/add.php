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
    $settings = json_decode($this->site->tstml);
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
      $new->site_id = $this->site->id;
      $new->patron->name = $_POST['name'];
      if($settings->meta)
        $new->patron->meta = $_POST['meta'];
      $new->save();
      
      $editor_url = url::site("testimonials/save/{$this->site->apikey}?ctk={$new->patron->token}&ttk={$new->token}");
      url::redirect($editor_url);
    }
    
    $this->render($view);
  }


  
  
} // End testimonials add controller