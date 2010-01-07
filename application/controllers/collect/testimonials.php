<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * public collecting of testimonials data for clients.
 */

class Testimonials_Controller extends Collect_Template_Controller {
  
  public function __construct()
  {
    parent::__construct();
    
    # verify testimonial token is sent.
    if(empty($_GET['ttk']))
      $this->render(View::factory('collect/blank'));
    $this->testimonial_token = $_GET['ttk'];

    # make sure the customer token is sent:
    if(empty($_GET['ctk']))
      $this->render(View::factory('collect/blank'));
    $this->patron_token = $_GET['ctk'];
    
    # define the form action url 
    $this->form_url = url::site("/collect/testimonials/{$this->site->subdomain}?ctk=$this->patron_token&ttk=$this->testimonial_token");
        
    # route to method here for better urls =p
    $allowed  = array('crop');
    $action   = (isset($_GET['a']) AND in_array($_GET['a'], $allowed)) 
      ? $_GET['a']
      : 'index';

    die($this->$action());
  }

  
/*
 * display the testimonials form to collect testimonials.
 */ 
  private function index()
  {    
    if($_POST)
    {
      $content = $this->handle_submit();
      $content .= $this->make_form();
    }
    else
      $content = $this->make_form();
    
    $this->render($content);
  }


/*
 * view and handler
 * for saving a thumbnail image of the original image
 */
  private function crop()
  {
    if($_POST)
    {
      $testimonial = $this->get_testimonial();

      die($testimonial->save_crop(
            $this->site->apikey,
            explode('|',$_POST['params'])
      ));
    }
    
    # display the crop view.
    die(t_build::crop_view($this->site->apikey, $this->form_url . "&a=crop"));
  }

  
/*
 * returns the testimonial form view.
 */   
  private function make_form()
  {
    $testimonial = $this->get_testimonial();

    # does the testimonial belong to the patron?
    if($this->patron_token !== $testimonial->patron->token)
      $this->render('invalid patron token');  

    # get form questions
    $questions = ORM::factory('question')
      ->where('site_id',$this->site->id)
      ->find_all();
      
    $form = new View('testimonials/edit');
    $form->questions    = $questions;
    $form->tags         = $this->site->tags;
    $form->info         = json_decode($testimonial->body_edit, TRUE);
    $form->testimonial  = $testimonial;
    $form->image_url    = t_paths::image($this->site->apikey, 'url');
    $form->url          = $this->form_url;
    return $form;
  }


/*
 * post review handler.
 */
  private function handle_submit()
  {
    # valid testimonial are required 
    # no validation so we can save anything coming in.
    $testimonial              = $this->get_testimonial();
    $testimonial->body_edit   = (isset($_POST['info'])) ? json_encode($_POST['info']) : '';
    $testimonial->body        = $_POST['body'];
    $testimonial->tag_id      = $_POST['tag'];
    $testimonial->rating      = $_POST['rating'];        
    $testimonial->save();
    
    $testimonial->patron->name      = $_POST['name'];
    $testimonial->patron->company   = $_POST['company'];
    $testimonial->patron->position  = $_POST['position'];
    $testimonial->patron->location  = $_POST['location'];
    $testimonial->patron->url       = $_POST['url'];
    $testimonial->patron->save();
    # save image if sent.
    if(isset($_FILES) AND !empty($_FILES['image']['tmp_name']))
      $testimonial->save_image($this->site->apikey, $_FILES, $testimonial->id);
    
    $view = new View('common/status');
    $view->success = TRUE;
    $view->type = 'testimonial';
    return $view;
  }


/*
 * return a valid, singleton testimonial
 */
  private function get_testimonial()
  {
    $testimonial = ORM::factory('testimonial')
      ->where(array(
        'site_id'  => $this->site->id,
        'token'    => $this->testimonial_token
      ))
      ->find();
    if(!$testimonial->loaded)
      $this->render('Invalid patron token');  
  
    return $testimonial;
  }
  
  
  
/*
 * ajax handler.
 * routes ajax calls to appropriate private method.
 */   
  public function _ajax()
  {
    # submit a review via POST, 
    if($_POST)
      die($this->handle_submit());


    die('invalid add/testimonial parameters');
  }      
  
  
  
} // End testimonials controller