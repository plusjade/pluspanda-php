<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * global dashboard.
  */

 class Pinky_Controller extends Controller {


  public function __construct()
  { 
    parent::__construct();

    $this->session  = Session::instance();
    $this->shell    = new View('pinky/shell');
    $this->owner_id = (isset($_GET['id']))
      ? $_GET['id']
      : FALSE;
  }


/*
 * login to the stat dashboard
 */
 public function index()
 {
    if(!isset($_GET['pw']) OR 'willow' !== $_GET['pw'])
      Event::run('system.404'); 
      
    $this->session->set('to_dashboard', TRUE);
    url::redirect('/pinky/dashboard');
 }
 

/*
 * display dashboard
 */
 public function dashboard()
 {
    $this->logged_in();

    $view = new View('pinky/dashboard');
    $view->total  = ORM::factory('owner')->count_all();
    $view->owners = ORM::factory('owner')
      ->select('owners.*, COUNT(testimonials.owner_id) as tstmls')
      ->join('testimonials','testimonials.owner_id', 'owners.id')
      ->groupby('testimonials.owner_id')
      ->orderby('created', 'desc')
      ->find_all();

    $this->shell->content = $view;
    die($this->shell);
 }


/*
 * delete an owner and all associated assets.
 */
  public function delete()
  {
    if(!$this->owner_id)
      die('owner id not specified');
    if(!isset($_GET['confirm']))
      die('confirm to continue');
    
    $owner = ORM::factory('owner', $this->owner_id);
    if(!$owner->loaded)
      die('Owner does not exist');
      
    $owner->delete();
    die('owner successfully deleted');
  }



/*
 * check login
 */
 private function logged_in()
 {
    if(TRUE !== $this->session->get('to_dashboard'))
      die('not logged in');

    return;
 }
 
/*
 * logout
 */
 public function logout()
 {
    Session::instance()->destroy();
    die('logged out.');
 }
 
 
} // End pinky Controller
