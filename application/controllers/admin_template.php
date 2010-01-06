<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * admin template  
 */

 abstract class Admin_Template_Controller extends Controller {

  // shell view name
  public $shell = 'admin/shell';
  public $site_id = NULL;
  public $service;
  
/**
 * shell loading and setup routine.
 */
  public function __construct()
  {
    parent::__construct();

    $this->owner = Auth::instance();  
    if(!$this->owner->logged_in())
      url::redirect('/admin/login');

    # which service are we using?
    $this->service = (isset($_GET['service']) AND 'reviews' == $_GET['service'])
      ? 'reviews'
      : 'testimonials';

    # Note:this will not work when users can access multiple sites.
    $this->site = $this->owner->get_user()->sites->current();
    $this->site_id = $this->site->id;

    # no need to load the shell if ajax.
    if(request::is_ajax())
      return;

    # Load the shell for non ajax only.
    $this->shell = new View($this->shell);
    $this->shell->service = $this->service;
    
    $this->shell->menu_testimonials = array(
      array('dashboard', '/admin/testimonials/dashboard','Dashboard','jax'),
      array('collect', '/admin/testimonials/collect','Collect','jax'),
      array('testimonials', '/admin/testimonials/manage','Manage','jax'),
      array('tags', '/admin/testimonials/tags','Categories','jax'),
      array('display', '/admin/testimonials/display','Display',''),    
      array('install', '/admin/install/testimonials','Install','jax'),
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


} // End admin template controller