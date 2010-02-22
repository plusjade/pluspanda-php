<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage the testimonials. (admin mode)
 */
  
class Manage_Controller extends Admin_Template_Controller {

  public $active_tag;
  public $active_rating;
  public $active_range;
  public $active_page;
  public $publish;
  
  public function __construct()
  {      
    parent::__construct();
    
    $this->active_tag     = (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
    $this->publish        = (isset($_GET['publish'])) ? $_GET['publish'] : NULL;
    $this->active_rating  = (isset($_GET['rating'])) ? $_GET['rating'] : 'all';
    $this->active_range   = (isset($_GET['range'])) ? $_GET['range'] : 'all';
    $this->active_page    = (isset($_GET['page']) AND is_numeric($_GET['page'])) ?   $_GET['page'] : 1;
    
    $this->testimonial_id = (isset($_GET['id']))
      ? valid::id_key($_GET['id'])
      : NULL;
    
    # prep the ajax response
    $this->rsp = Response::instance();
  }
  
/*
 * manage testimonials panel.
 * this is the public wrapper.
 */
  public function index()
  {  
    $content = new View('admin/testimonials/manage');
    $content->categories    = t_build::tag_select_list($this->tags, $this->active_tag, array('all'=>'All'));
    #$content->ratings       = common_build::rating_select_list($this->active_rating);
    #$content->range         = common_build::range_select_list($this->active_range);
    $content->testimonials  = $this->get_testimonials();
    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->sub_menu = array(
      array('main', '/admin/testimonials/manage','Main Panel',''),    
      array('collect', '/admin/testimonials/form','Collect Form',''),
      array('help', '#help-page','(help)','fb-help'),
    );
    $this->service = 'testimonials';
    $this->active  = 'manage';
    die($this->shell);
  }

  
/*
 * get the testimonials data
 */
  private function get_testimonials()
  {
    $limit = 100;
    $params = array(
      'owner_id' => $this->owner->id,
      'page'     => $this->active_page,
      'tag'      => $this->active_tag,
      'publish'  => $this->publish,
      'limit'    => $limit,
    );
    
    $total_testimonials = ORM::factory('testimonial')
      ->fetch($params, 'count');
      
    $testimonials = ORM::factory('testimonial')
      ->fetch($params);
      
    # build the pagination html
    $pagination = new Pagination(array(
      'base_url'       => "/admin/testimonials/manage?tag=$this->active_tag&rating=$this->active_rating&range=$this->active_range&page=",
      'current_page'   => $this->active_page, 
      'total_items'    => $total_testimonials,
      'style'          => 'tabs',
      'items_per_page' => $limit
    ));
    

    $view = new View('admin/testimonials/data');
    $view->testimonials  = $testimonials;
    $view->pagination    = $pagination;
    $view->tags          = $this->tags;
    $view->active_tag    = $this->active_tag;
    return $view;
  }    
  

/*
 * return valid, singleton testimonial
 * return a blank testimonial object to create new
 */
  private function get_testimonial()
  {
    if(0 == $this->testimonial_id)
    {
      $new = ORM::factory('testimonial');
      $new->owner_id = $this->owner->id;
      return $new;
    }
    
    valid::id_key($this->testimonial_id);
    
    $testimonial = ORM::factory('testimonial')
      ->where('owner_id',$this->owner->id)
      ->find($this->testimonial_id);
    if(!$testimonial->loaded)
    {
      $this->rsp->msg = 'Testimonial does not exist';
      $this->rsp->send(); 
    }
    return $testimonial;
  }
  

/*
 * display view for editing a testimonial
 */ 
  public function edit()
  {
    $testimonial = $this->get_testimonial();
    
    # get questions
    $questions = ORM::factory('question')
      ->where('owner_id',$this->owner->id)
      ->find_all();
    
    $view = new View('admin/testimonials/edit');
    $view->testimonial  = $testimonial;
    $view->info         = json_decode($testimonial->body_edit, TRUE);
    $view->questions    = $questions;
    $view->tags         = $this->tags;
    $view->image_url    = t_paths::image($this->owner->apikey, 'url');
    die($view);
  }
  
  
/* 
 * save a testimonial handler.
 */
  public function save()
  {
    if(!$_POST)
    {
      $this->rsp->msg = 'Nothing Sent';
      $this->rsp->send();
    }
    
    $testimonial = $this->get_testimonial();
    $testimonial->name       = $_POST['name'];
    $testimonial->company    = $_POST['company'];
    $testimonial->c_position = $_POST['position'];
    $testimonial->url        = $_POST['url'];
    $testimonial->location   = $_POST['location'];
    $testimonial->body       = $_POST['body'];  
    $testimonial->tag_id     = $_POST['tag'];
    $testimonial->publish    = (empty($_POST['publish']))
      ? 0
      : 1;
    $testimonial->lock       = (empty($_POST['lock']))
      ? 0
      : 1;
    $testimonial->rating     = $_POST['rating'];
    $testimonial->save();

    $this->rsp->status   = 'success';
    $this->rsp->msg      = 'Testimonial Saved!';
    $this->rsp->id       = $testimonial->id;
    $this->rsp->exists   = (0 < $this->testimonial_id) ? true : false;
    $this->rsp->image    = $testimonial->image;
    $this->rsp->rowHtml  = t_build::admin_table_row($testimonial, $this->owner->apikey);
    $this->rsp->send();
  }

/* 
 * save an uploaded image.
 * we do this separately so we can insta-download it.
 * also because the save form somehow corrupts the json response
 * if i try to send a file ?? =(
 */  
  public function save_image()
  {
    if(isset($_FILES) AND !empty($_FILES['image']['tmp_name']))
    {
      $testimonial = $this->get_testimonial();
      $response = $testimonial
        ->save_image(
          $this->owner->apikey,
          $_FILES
        );
      $this->rsp->status  = key($response);
      $this->rsp->msg     = current($response);
      $this->rsp->id      = $testimonial->id;
      $this->rsp->image   = $testimonial->image;
      $this->rsp->exists  = (0 < $this->testimonial_id) ? true : false;
    }
    else
      $this->rsp->msg = 'Nothing sent.';
    
    $this->rsp->send();
  }
  
  
/* 
 * delete a testimonial.
 */  
  public function delete()
  {
    $this->get_testimonial()->delete();
    $this->rsp->status  = 'success';
    $this->rsp->msg     = 'Testimonial Deleted!';
    $this->rsp->send();
  }   

/* 
 * save testimonial position order
 */  
  public function positions()
  {
    if(empty($_GET['tstml']))
    {
      $this->rsp->msg    = 'Nothing to Save.';
      $this->rsp->send();
    }
    
    $db = Database::Instance();
    foreach($_GET['tstml'] as $position => $id)
      $db->update('testimonials', array('position' => "$position"), "id = '$id' AND owner_id = '$this->owner->id'");

    $this->rsp->status = 'success';
    $this->rsp->msg    = 'Order Saved!';
    $this->rsp->send();
  }   
/*
 * view and handler
 * for saving a thumbnail image of the original image
 */
  public function crop()
  {
    if($_POST)
    {
      echo $this->get_testimonial()->save_crop(
              $this->owner->apikey,
              explode('|',$_POST['params'])
            );
      die;
    }
    
    # display the crop view.
    die(t_build::crop_view($this->owner->apikey));
  }

  
  
    
} // End testimonials Controller
