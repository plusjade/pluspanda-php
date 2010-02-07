<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage customers. (admin mode)
  */
  
 class Display_Controller extends Admin_Template_Controller {

  public function __construct()
  {
    parent::__construct();
    
    $this->rsp = Response::instance();
  }

  public function index()
  {
    $content = new View("admin/testimonials/display");
    $content->embed_code = t_build::embed_code($this->site->apikey, NULL, FALSE);
    
    $tstmls = new Testimonials_Controller($this->site);
    $content->testimonials_html = $tstmls->get_html();

    $stylesheet = t_paths::css($this->site->apikey) .'/'. $this->site->theme . '.css'; 
    $content->stylesheet  = (file_exists($stylesheet))
      ? file_get_contents($stylesheet)
      : '/* no custom file */';
    $content->stock  = (file_exists(DOCROOT .'static/css/testimonials/stock/' . $this->site->theme . '.css'))
      ? file_get_contents(DOCROOT .'static/css/testimonials/stock/' . $this->site->theme . '.css')
      : '/* no stock file */';
    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active  = 'display';
    $this->shell->service = 'testimonials';
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
        'site_id' => $this->site_id,
        'publish' => 1
      ))
      ->orderby(array(
        'tag_id' => 'asc',
        'created' => 'desc'
      ))
      ->find_all();
    return $testimonials;
    
    
    $this->limit = 10;
    $params = array(
      'site_id'  => $this->site_id,
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
      
    $this->site->theme    = (isset($_POST['theme'])) ? $_POST['theme'] : null;
    $this->site->sort     = (isset($_POST['sort'])) ? $_POST['sort'] : 'created';
    $this->site->per_page = (isset($_POST['per_page'])) ? $_POST['per_page'] : 10;
    
    $this->site->save();
    
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

    $stylesheet = t_paths::css($this->site->apikey) .'/'. $this->site->theme . '.css'; 
    file_put_contents($stylesheet, $_POST['css']);
    
    $this->rsp->status = 'success';
    $this->rsp->msg = 'CSS Saved!';
    $this->rsp->send(); 
  }
  
} // End display Controller
