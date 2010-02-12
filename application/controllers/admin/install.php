<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage customers. (admin mode)
  */
  
 class Install_Controller extends Admin_Template_Controller {

  public function __construct()
  {
    parent::__construct();
  }
  
/*
 * manage customers.
 * this is the public wrapper.
 */
  public function reviews($action=FALSE)
  {  
    $content = new View('admin/install_wrapper');
    $content->embed_code = r_build::embed_code($this->site->apikey, 'fake');
    $content->embed_code_lite = r_build::embed_code($this->site->apikey, 'fake', FALSE);
      
    # carry out the action so view is up-to-date.
    if($action)
      $content->response = alerts::display($this->$action());
  
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'install';
    $this->shell->service = 'reviews';
    die($this->shell);
  }

  
/*
 * 
 */
  public function testimonials($action=FALSE)
  {  
    $content = new View('admin/install_wrapper');
    $content->embed_code = t_build::embed_code($this->site->apikey, 'fake');
    $content->embed_code_lite = t_build::embed_code($this->site->apikey, 'fake', FALSE);
      
    # carry out the action so view is up-to-date.
    if($action)
      $content->response = alerts::display($this->$action());
  
  
  
  
    $tstmls = new Testimonials_Controller($this->site);
    $content->html = $tstmls->export_html();

    $stylesheet = t_paths::css($this->site->apikey) .'/'. $this->site->theme . '.css'; 
    $content->css  = (file_exists($stylesheet))
      ? file_get_contents($stylesheet)
      : '/* no custom file */';
      
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->active = 'install';
    $this->shell->service = 'testimonials';
    die($this->shell);
  }
  

} // End install Controller
