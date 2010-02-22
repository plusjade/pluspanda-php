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
 * 
 */
  public function index()
  {  
    $content = new View('admin/testimonials/install');
    $content->embed_code = t_build::embed_code($this->owner->apikey, 'fake');
    $content->embed_code_lite = t_build::embed_code($this->owner->apikey, 'fake', FALSE);

    $tstmls = new Testimonials_Controller($this->owner);
    $content->html = $tstmls->export_html();

    $stylesheet = t_paths::css($this->owner->apikey) .'/'. $this->owner->tconfig->theme . '.css'; 
    $content->css  = (file_exists($stylesheet))
      ? file_get_contents($stylesheet)
      : '/* no custom file */';
      
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->shell->sub_menu = array(
      array('main', '/admin/testimonials/install','Main Panel',''),    
      array('help', '#help-page','(help)','fb-help'),
    );
    $this->active = 'install';
    $this->service = 'testimonials';
    die($this->shell);
  }
  

} // End testimonial install Controller
