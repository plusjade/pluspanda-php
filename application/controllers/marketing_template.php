<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * the template controller for the public marketing site.
 */

 abstract class Marketing_Template_Controller extends Controller {

  public $shell;
  public $auth;

  public function __construct()
  {
    parent::__construct();

    $this->auth = Auth::instance();  

    $this->shell = new View('marketing/shell');
    $this->shell->login_link = ($this->auth->logged_in())
      ? 'Admin'
      : 'Login';
    $this->shell->links = array(
      'home'    => 'Home',
      'pricing' => 'Pricing',
      #'cases'   => 'Use-Cases'
      'faq'     => 'FAQ',
      'contact' => 'Contact'
    );
  }

} // End Marketing_Controller