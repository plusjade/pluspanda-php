<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin template  
 */

abstract class Admin_Template_Controller extends Controller {

  // shell view name
  public $shell   = 'admin/shell';
  public $service = 'testimonials';
  
/**
 * shell loading and setup routine.
 */
  public function __construct()
  {
    parent::__construct();

    $this->auth = Auth::instance();  
    if(!$this->auth->logged_in())
      url::redirect('/admin/login');

    $this->owner = $this->auth->get_user();
    $this->tags  = ORM::factory('tag')
      ->where('owner_id', $this->owner->id)
      ->find_all();
      
    # no need to load the shell if ajax.
    if(request::is_ajax())
      return;

    # Load the shell for non ajax only.
    $this->shell = new View($this->shell);

    $this->shell->menu_testimonials = array(
      array('display', '/admin/testimonials/display','1. Choose Layout',''),    
      array('tags', '/admin/testimonials/tags','Categories','jax'),
      array('testimonials', '/admin/testimonials/manage','2. Add Testimonials','jax'),
      array('form', '/admin/testimonials/form','Collect Form','jax'),
      array('install', '/admin/testimonials/install','3. Install','jax'),
      array('dashboard', '/admin/testimonials/dashboard','(Help)','jax'),
    );
    
    $this->shell->menu_reviews = array(
      array('dashboard', '/admin/reviews/dashboard','Dashboard','jax'),
      array('categories', '/admin/reviews/categories','Categories','jax'),
      array('reviews', '/admin/reviews/manage','Reviews','jax'),
      array('customers', '/admin/reviews/customers','Customers','jax'),
      array('widget', '/admin/widget/reviews','Display',''),  
      array('install', '/admin/install/reviews','Installation','jax'),
    );
  }

/*
 * refresh the settings cache
 */
  public function update_settings_cache()
  {
    $settings = t_paths::js_cache($this->owner->apikey);
    if(file_exists($settings))
      unlink($settings);
    return;
  }
  
  
  
  
} // End admin template controller