<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Renders the testimonials Engine.
    Widget Ajax via JSONP.
      called externally outputs raw json to be formatted other end.
 */
class Testimonials_Controller extends Controller {

  public $active_tag;
  public $active_sort;
  public $active_page;
  public $limit;

  
  public function __construct($owner=NULL, $type=FALSE)
  {
    parent::__construct();
  
    if(NULL === $owner)
      Event::run('system.404');
      
    $this->owner      = $owner;
    $this->apikey     = $owner->apikey;
    $this->theme      = (empty($owner->tconfig->theme)) ? 'left' : $owner->tconfig->theme;
    $this->sort       = (empty($owner->tconfig->sort))
      ? 'created'
      : $owner->tconfig->sort;
    $this->limit      = (empty($owner->tconfig->per_page))
      ? 10
      : $owner->tconfig->per_page;

    $this->active_tag   = (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
    $this->active_sort  = (isset($_GET['sort'])) ? strtolower($_GET['sort']) : 'newest';
    $this->active_page  = (isset($_GET['page']) AND is_numeric($_GET['page'])) ?   $_GET['page'] : 1;  
    
    $this->tags  = ORM::factory('tag')
      ->where('owner_id', $this->owner->id)
      ->find_all();
      
    if('api' == $type)
      $this->_ajax();
  }

/* 
 * gets the testimonials as an html view.
 */
  public function get_html()
  {    
    $content = new View('testimonials/wrapper');
    $content->tag_list = t_build::tag_list($this->tags, $this->active_tag);
    $content->limit = $this->limit;
    $content->get_testimonials = $this->get_testimonials();
    return $content; 
    
    # setup the shell
    $shell = new View('testimonials/shell');    
    $shell->content = $content;
    echo $shell->render();
  }

/* 
 * export all testimonials as html view
 */
  public function export_html()
  {    
    $content = new View('testimonials/wrapper');
    $content->tag_list = t_build::tag_list($this->tags, $this->active_tag);
    $content->limit = $this->limit;
    
    $this->limit = 100;
    $content->get_testimonials = $this->get_testimonials();
    return $content; 
    
    # setup the shell
    $shell = new View('testimonials/shell');    
    $shell->content = $content;
    echo $shell->render();
  }
/*
 * get the testimonials data.
 * count omits limit to determine pagination scheme.
 */
  private function get_testimonials($count=FALSE)
  {
    $params = array(
      'owner_id' => $this->owner->id,
      'page'     => $this->active_page,
      'tag'      => $this->active_tag,
      'publish'  => 'yes',
      'sort'     => $this->sort,
      'created'  => $this->active_sort,
      'limit'    => $this->limit
    );
    
    if($count)
      return ORM::factory('testimonial')->fetch($params, 'count');
      
    return ORM::factory('testimonial')->fetch($params);
  }

  
/*
 * output testimonial json data to an api call
 */
  private function send_api()
  {
    $testimonials = $this->get_testimonials();
    $total = $this->get_testimonials(TRUE);
    
    $testimonial_array = array();
    foreach($testimonials as $testimonial)
      $testimonial_array[] = $testimonial->prep_api();

    # should we specify a next page link?
    $page_vars = '';
    $offset = ($this->active_page*$this->limit) - $this->limit;
    if($total > $offset + $this->limit)
    {
      $next_page = $this->active_page+1;
      $page_vars = "'$next_page', '$this->active_tag', '$this->active_sort'";
    } 

    $json_testimonials = json_encode($testimonial_array);

    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    die("pandaDisplayTstmls($json_testimonials);pandaShowMore($page_vars);");
  }
  
  
/*
 * serve the widget javascript files.
 * caches them if they don't exist.
 */
  private function widget()
  {
    $settings_file = t_paths::js_cache($this->apikey);
    $init_file     = t_paths::init_cache();
    if(!file_exists($settings_file))
      $this->cache_settings($settings_file);

    if(!file_exists($init_file))
      self::cache_init();

    readfile($settings_file);
    readfile($init_file);
    die;
  }

  
/*
 * regenerates a fresh widget settings file cache.
 */
  private function cache_settings($file)
  {
    $keys = array("\n","\r","\t");
    
    # get the html based on theme.
    $wrapper = new View("testimonials/themes/$this->theme/wrapper");
    $wrapper->tag_list = t_build::tag_list($this->tags, $this->active_tag);
    
    $item_html  = new View("testimonials/themes/$this->theme/item");
    
    # create the settings javascript file.
    $settings = new View('testimonials/widget_settings');
    $settings->theme           = $this->theme;
    $settings->apikey          = $this->apikey;
    $settings->asset_url       = t_paths::service($this->apikey, 'url');
    $settings->panda_structure = str_replace($keys, '', $wrapper->render());
    $settings->item_html       = str_replace($keys, '', $item_html->render());

    file_put_contents(
      $file,
      $settings->render()."\n/*".date('m.d.y g:ia')."*/"
    );
    
    return;
  }
  
/*
 * regenerates a fresh widget init file cache.
 * this should be static relative to user layout settings.
 */
  private static function cache_init()
  {
    file_put_contents(
      t_paths::init_cache(),
      View::factory('testimonials/widget_init')->render()."\n//".date('m.d.y g:ia')
    );
    return;
  }
  
  
  
/*
 * ajax handler.
 * routes ajax calls to appropriate private method.
 */   
  public function _ajax()
  {
    # fetch the widget environment.
    if(isset($_GET['fetch']))
      die($this->widget());

    # get testimonials in json
    if(isset($_GET['tag']))
      die($this->send_api());
      
    die('invalid api parameters');
  }
  
  
} // End testimonials Controller
