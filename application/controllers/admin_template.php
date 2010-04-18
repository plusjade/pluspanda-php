<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin template  
 */

abstract class Admin_Template_Controller extends Controller {

  // shell view name
  public $shell                 = 'admin/shell';
  public $parent_nav_active     = 'display';
  public $child_nav_active      = 'main';
  public $grandchild_nav_active = 'main';
  
/**
 * shell loading and setup routine.
 */
  public function __construct()
  {
    parent::__construct();

    $this->auth = Auth::instance();  
    if($this->auth->logged_in())
    {
      $this->owner = $this->auth->get_user(); 
    }
    else
    {
      #url::redirect('/admin/login');
      $this->owner = ORM::factory('owner');
      $this->owner->save();
      $this->auth->force_login($this->owner);
    }

    $this->tags  = ORM::factory('tag')
      ->where('owner_id', $this->owner->id)
      ->find_all();
      
    # no need to load the shell if ajax.
    if(request::is_ajax())
      return;

    # Load the shell for non ajax only.
    $this->shell = new View($this->shell);

    $this->shell->parent_nav = array(
      array('display', '/admin/testimonials/display','1. Choose Layout',''),    
      array('manage', '/admin/testimonials/manage','2. Manage Testimonials','jax'),
      array('install', '/admin/testimonials/install','3. Install On Your Site','jax'),
    );
    $this->shell->child_nav = '';
    $this->shell->grandchild_nav = '';
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