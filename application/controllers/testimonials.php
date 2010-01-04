<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Renders the testimonials patron testimonials Engine.
 * 3 types of data output formats:
    1. Standalone js-disabled Mode.
        if no javascript, functions as normal, outputs complete views.
    2. Standalone Ajax Mode.
        updates via ajax, only outputs data view.
    3. Widget Ajax via JSONP.
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
    $this->site_name  = $site->subdomain;
    $this->site_id    = $site->id;
    $this->theme      = (empty($site->theme)) ? 'gray' : $site->theme;

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
 * The index is only a wrapper for Standalone js-disabled mode mode.
 * any ajax or widget functionality will not use this at all.
 */
  public function index()
  {    
    $content = new View('testimonials/wrapper');
    $content->site = $this->site;
    $content->set_global('active_tag', $this->active_tag);
    $content->set_global('active_sort', $this->active_sort);
    $content->get_testimonials = $this->get_testimonials();

    # setup the shell
    $shell = new View('testimonials/shell');    
    $shell->content = $content;
    echo $shell->render();
  }

  
/* ------------- modular methods (ajaxable) -------------  */
  
/*
 * get the testimonials data.
 * count omits limit to determine pagination scheme.
 */
  private function get_testimonials($count=FALSE)
  {
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
 * send data to an api call
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
 * build the embeddable widget javascript environment.
 * cache the result
 */
  private function widget()
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

    # load the widget_js view and place the html as json.
    $widget_js = new View('testimonials/widget_js');
    $widget_js->theme     = $this->theme;
    $widget_js->apikey    = $this->apikey;
    $widget_js->asset_url = t_paths::service($this->apikey, 'url');
    $widget_js->json_html = json_encode($html);
    $widget_js->item_html = str_replace($keys, '', $item_html);
    
    
    # output the view then cache the result.    
    ob_start();
    echo $widget_js;
    /*
    file_put_contents(
      DOCROOT . "widget/js/$this->apikey.js",
      ob_get_contents()."\n//cached ".date('m.d.y g:ia e')
    );
    */
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
