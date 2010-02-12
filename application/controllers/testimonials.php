<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Renders the testimonials patron testimonials Engine.
    Widget Ajax via JSONP.
      called externally outputs raw json to be formatted other end.
 */
class Testimonials_Controller extends Controller {

  public $active_tag;
  public $active_sort;
  public $active_page;
  public $limit;
  public $is_api = FALSE;

  
  public function __construct($site=NULL, $type=FALSE)
  {
    parent::__construct();
  
    if(NULL === $site)
      Event::run('system.404');
      
    $this->site       = $site;
    $this->apikey     = $site->apikey;
    $this->site_id    = $site->id;
    $this->theme      = (empty($site->theme)) ? 'left' : $site->theme;
    $this->sort       = (empty($this->site->sort))
      ? 'created'
      : $this->site->sort;
    $this->limit      = (empty($this->site->per_page))
      ? 10
      : $this->site->per_page;
    # setup active states.
    $this->active_tag   = (isset($_GET['tag'])) ? $_GET['tag'] : 'all';
    $this->active_sort  = (isset($_GET['sort'])) ? strtolower($_GET['sort']) : 'newest';
    $this->active_page  = (isset($_GET['page']) AND is_numeric($_GET['page'])) ?   $_GET['page'] : 1;  
    
    if('api' == $type)
    {
      $this->is_api = TRUE;
      $this->_ajax();
    }
  }

/* 
 * gets the testimonials as an html view.
 */
  public function get_html()
  {    
    $content = new View('testimonials/wrapper');
    $content->tag_list = t_build::tag_list($this->site->tags, $this->active_tag);
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
    $content->tag_list = t_build::tag_list($this->site->tags, $this->active_tag);
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
      'site_id'  => $this->site_id,
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
    $settings_file = t_paths::js_cache($this->site->apikey);
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
    
    # get all the html interfaces.    
    $tag_list   = t_build::tag_list($this->site->tags, $this->active_tag);
    $sorters    = t_build::sorters($this->active_tag, $this->active_sort, 'widget');    
    $item_html  = t_build::stock_item_html();
      
    # build an object to hold the html.
    $html = new StdClass(); 
    $html->tag_list = str_replace($keys, '', $tag_list);
    $html->sorters  = str_replace($keys, '', $sorters);

    # load the settings view and place the html as json.
    $settings = new View('testimonials/widget_settings');
    $settings->theme     = $this->theme;
    $settings->apikey    = $this->apikey;
    $settings->asset_url = t_paths::service($this->apikey, 'url');
    $settings->json_html = json_encode($html);
    $settings->item_html = str_replace($keys, '', $item_html);

    file_put_contents(
      $file,
      $settings->render()."\n/*".date('m.d.y g:ia')."*/"
    );
    
    return;
  }
  
/*
 * regenerates a fresh widget init file cache.
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
    if(isset($_GET['fetch']) AND 'testimonials' == $_GET['fetch'])
      die($this->widget());

    # get testimonials in json
    if(isset($_GET['tag']))
      die($this->send_api());
      
    die('invalid api parameters');
  }
  
  
} // End testimonials Controller
