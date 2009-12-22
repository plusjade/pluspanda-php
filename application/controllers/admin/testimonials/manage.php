<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage the testimonials. (admin mode)
 */
  
class Manage_Controller extends Admin_Interface_Controller {

  public $active_tag;
  public $active_rating;
  public $active_range;
  public $active_page;
  public $publish;
  
  public function __construct()
  {      
    parent::__construct();
    if(!$this->owner->logged_in())
      url::redirect('/admin/home');

    $this->active_tag     = (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
    $this->publish        = (isset($_GET['publish'])) ? $_GET['publish'] : NULL;
    $this->active_rating  = (isset($_GET['rating'])) ? $_GET['rating'] : 'all';
    $this->active_range   = (isset($_GET['range'])) ? $_GET['range'] : 'all';
    $this->active_page    = (isset($_GET['page']) AND is_numeric($_GET['page'])) ?   $_GET['page'] : 1;
    
    $this->testimonial_id = (isset($_GET['id']))
      ? $_GET['id']
      : NULL;
  }
  
/*
 * manage testimonials panel.
 * this is the public wrapper.
 */
  public function index()
  {  
    $content = new View('admin/testimonials/wrapper');
    $content->categories    = build::tag_select_list($this->site->tags, $this->active_tag, array('all'=>'All'));
    $content->ratings       = build::rating_select_list($this->active_rating);
    $content->range         = build::range_select_list($this->active_range);
    $content->testimonials  = $this->get_testimonials();
    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->service = 'testimonials';
    $this->shell->active  = 'testimonials';
    die($this->shell);
  }

  
/*
 * get the testimonials data
 */
  private function get_testimonials()
  {
    $sort   = array('created' => 'desc');
    $where  = array();
    
    # filter by publish
    if('yes' == $this->publish)
      $where['publish'] = 1;
    elseif('no' == $this->publish)
      $where['publish'] = 0;
      
      
    # filter by tag
    if(is_numeric($this->active_tag))
      $where['tag_id'] = $this->active_tag;
    else
      $where['site_id'] = $this->site_id;
        
        
    # filter by rating
    if(is_numeric($this->active_rating))
      $where['rating'] = $this->active_rating;
  

    # filter by date
    $now = time();
    $day = 86400;
    switch($this->active_range)
    {
      case 'today':
      
        break;
      case 'last7':
        $where['created >='] = time() - $day*7;
        break;
      case 'last14':
        $where['created >='] = time() - $day*14;
        break;
      case 'last30':
        $where['created >='] = time() - $day*30;
        break;
      case 'ytd':
        $where['created >='] = mktime(0, 0, 0, 1, 1, date("m Y"));
        break;
    }
    
    # get full count of testimonials for this tag.
    $total_testimonials = ORM::factory('testimonial')
      ->where($where)
      ->orderby($sort)
      ->count_all();
      
    # get the appropriate testimonials based on page.
    $offset = ($this->active_page*10) - 10;
    $testimonials = ORM::factory('testimonial')
      ->with(null)
      ->where($where)
      ->orderby($sort)
      ->limit(10, $offset)
      ->find_all();

    # build the pagination html
    $pagination = new Pagination(array(
      'base_url'       => "/admin/testimonials/manage?tag=$this->active_tag&rating=$this->active_rating&range=$this->active_range&page=",
      'current_page'   => $this->active_page, 
      'total_items'    => $total_testimonials,
      'style'          => 'testimonials',
      'items_per_page' => 10
    ));
    

    $view = new View('admin/testimonials/display');
    $view->testimonials  = $testimonials;
    $view->pagination    = $pagination;
    $view->tags          = $this->site->tags;
    $view->active_tag    = $this->active_tag;
    return $view;
  }    
  

/*
 * return valid, singleton testimonial
 */
  private function get_testimonial()
  {
    valid::id_key($this->testimonial_id);
    
    $testimonial = ORM::factory('testimonial')
      ->where('site_id',$this->site_id)
      ->find($this->testimonial_id);
    if(!$testimonial->loaded)
      die('invalid id');  
    
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
      ->where('site_id',$this->site_id)
      ->find_all();
    
    $view = new View('admin/testimonials/edit');
    $view->testimonial  = $testimonial;
    $view->info          = json_decode($testimonial->body_edit, TRUE);
    $view->questions    = $questions;
    $view->tags          = $this->site->tags;
    $view->image_url    = paths::testimonial_image_url($this->site_id);
    die($view);
  }

 
/* 
 * add a new testimonial profile.
 */  
  public function add_new()
  {
    if(!$_POST)
      die('nothing sent');

    # validate the form values.
    $post = new Validation($_POST);
    $post->pre_filter('trim');
    $post->add_rules('name', 'required');
    $post->add_rules('email', 'required');
    
    # on error! this should rarely happen due to client-side js validation...
    if(!$post->validate())
      die('Name and email required');

    $new_testimonial = ORM::factory('testimonial');
    $new_testimonial->site_id           = $this->site_id;
    $new_testimonial->patron->name      = $_POST['name'];
    $new_testimonial->patron->email     = $_POST['email'];
    $new_testimonial->patron->company   = $_POST['company'];
    $new_testimonial->patron->location  = $_POST['location'];
    $new_testimonial->save();
    die('New Testimonial Profile Created');  
  }
  
   
/* 
 * save a testimonial
 */
  public function save()
  {
    if(!$_POST)
      die('nothing sent');

    # validate the form values.
    $post = new Validation($_POST);
    $post->pre_filter('trim');
    $post->add_rules('name', 'required');

    # on error! this should rarely happen due to client-side js validation...
    if(!$post->validate())
      die('invalid post');

    $testimonial = $this->get_testimonial();

    # save image if sent.
    if(isset($_FILES) AND !empty($_FILES['image']['tmp_name']))
      $testimonial->save_image($this->site_id, $_FILES, $testimonial->id);
    
    $testimonial->patron->name     = $_POST['name'];
    $testimonial->patron->company  = $_POST['company'];
    $testimonial->patron->position = $_POST['position'];
    $testimonial->patron->url      = $_POST['url'];
    $testimonial->patron->location = $_POST['location'];
    $testimonial->patron->save();

    $testimonial->body    = $_POST['body'];  
    $testimonial->tag_id  = $_POST['tag'];
    $testimonial->publish = (empty($_POST['publish']))
      ? 0
      : 1;
    #$testimonial->rating  = $_POST['rating'];      
    $testimonial->save();
    die('Testimonial Saved!');
  }

/* 
 * delete a testimonial.
 */  
  public function delete()
  {
    $this->get_testimonial()->delete();
    die('Testimonal Deleted! =/');
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
              $this->site_id,
              explode('|',$_POST['params'])
            );
      die();
    }
    
    # display the crop view.
    die(build_testimonials::crop_view($this->site_id));
  }

  
  
    
} // End testimonials Controller
