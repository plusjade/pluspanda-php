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
    $view->owners  = ORM::factory('owner')
      ->orderby('created', 'desc')
      ->find_all();
    
    /*
    $view->owners = ORM::factory('owner')
      ->select('owners.*, COUNT(testimonials.owner_id) as tstmls')
      ->join('testimonials','testimonials.owner_id', 'owners.id')
      ->groupby('testimonials.owner_id')
      ->orderby('created', 'desc')
      ->find_all();
    echo kohana::debug($view->owners);
    die;
    */
      
    $view->saved = ORM::factory('owner')
      ->where('email !=','')
      ->count_all();

    $this->shell->content = $view;
    die($this->shell);
 }


/*
 * login as any user.
 */
  public function jeckle()
  {
    $this->logged_in();
  
    if(isset($_POST['email']))
    {
      $owner = ORM::factory('owner', $_POST['email']);
      if(!$owner->loaded)
        die;

      Auth::instance()->force_login($owner);
      url::redirect('/admin/testimonials/display');
    }
  
    die('nothing sent');
  }
  
  
  
  
/*
 * delete an owner and all associated assets.
 */
  public function delete()
  {
    $this->logged_in();
    
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

  public function delete_all()
  {
    $this->logged_in();
    
    $time = time() - 90000;

    # unsaved owners older than one day
    $unsaved = ORM::factory('owner')
      ->where(array(
        'created <' => $time,
        'email'     => ''
      ))
      ->orderby('created', 'desc')
      ->find_all();
      
    if(isset($_GET['confirm']))
      foreach($unsaved as $owner)
      {
        $owner->delete();
      }
    else
    {
      echo "There are <b>{$unsaved->count()}</b> unsaved accounts older than 24 hours.";
      echo '<br/><br/>Add confirm to delete all';
    }
  
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
