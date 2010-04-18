<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage customers. (admin mode)
  */
  
 class Display_Controller extends Admin_Template_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->parent_nav_active  = 'display';

    $this->rsp = Response::instance();
  }

  public function index()
  {
    $content = new View("admin/testimonials/display");
    $content->embed_code = t_build::embed_code($this->owner->apikey, NULL, FALSE);
    
    #$tstmls = new Testimonials_Controller($this->owner);
    #$content->testimonials_html = $tstmls->get_html();

    $stylesheet = t_paths::css($this->owner->apikey) .'/'. $this->owner->tconfig->theme . '.css'; 
    $stock  = DOCROOT .'static/css/testimonials/stock/'. $this->owner->tconfig->theme . '.css';
    if(file_exists($stock) AND !file_exists($stylesheet))
      copy($stock, $stylesheet);
    
    $content->stylesheet  = (file_exists($stylesheet))
      ? file_get_contents($stylesheet)
      : '/* no custom file */';
    $content->stock  = (file_exists(DOCROOT .'static/css/testimonials/stock/' . $this->owner->tconfig->theme . '.css'))
      ? file_get_contents(DOCROOT .'static/css/testimonials/stock/' . $this->owner->tconfig->theme . '.css')
      : '/* no stock file */';
    
    if(request::is_ajax())
      die($content);
    
    
    $this->shell->content = $content;
    $this->shell->child_nav = array(
      array('main', '/admin/testimonials/display','Configure Layout',''),    
      array('tags', '/admin/testimonials/tags','Set Categories',''),
    );
    $this->shell->grandchild_nav = array(
      array('main', '/admin/testimonials/display','Main Panel',''),    
      array('css', '#css','Edit CSS','show-css'),
      array('help', '#help-page','(help)','fb-help'),
    );

    die($this->shell);
  }
  
/*
 * get the testimonials data.
 * count omits limit to determine pagination scheme.
 */
  private function get_testimonials($count=FALSE)
  {
    $testimonials = ORM::factory('testimonial') 
      ->where(array(
        'owner_id' => $this->owner->id,
        'publish'  => 1
      ))
      ->orderby(array(
        'tag_id'  => 'asc',
        'created' => 'desc'
      ))
      ->find_all();
    return $testimonials;
    
    
    $this->limit = 10;
    $params = array(
      'owner_id' => $this->owner->id,
      'page'     => $this->active_page,
      'tag'      => $this->active_tag,
      'publish'  => 'yes',
      'created'  => $this->active_sort,
      'limit'    => $this->limit
    );
    
    if($count)
      return ORM::factory('testimonial')->fetch($params, 'count');
      
    return ORM::factory('testimonial')->fetch($params);
  }
  
  


/*
 * save the display settings
 */
  public function save()
  {
    if(!$_POST)
      die;
      
    $this->owner->tconfig->theme    = (isset($_POST['theme'])) ? $_POST['theme'] : null;
    $this->owner->tconfig->sort     = (isset($_POST['sort'])) ? $_POST['sort'] : 'created';
    $this->owner->tconfig->per_page = (isset($_POST['per_page'])) ? $_POST['per_page'] : 10;
    
    $this->owner->tconfig->save();
    
    $this->update_settings_cache();
      
    $this->rsp->status = 'success';
    $this->rsp->msg = 'Theme Saved!';
    $this->rsp->send(); 
  }
  
/*
 * save the display settings
 */
  public function save_css()
  {
    if(empty($_POST['css']))
      die;

    $stylesheet = t_paths::css($this->owner->apikey) .'/'. $this->owner->tconfig->theme . '.css'; 
    file_put_contents($stylesheet, $_POST['css']);
    
    $this->rsp->status = 'success';
    $this->rsp->msg = 'CSS Saved!';
    $this->rsp->send(); 
  }
  
} // End display Controller
