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
  public $is_api = FALSE;

  
  public function __construct($site=NULL, $type=FALSE)
  {
    parent::__construct();
  
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
 * get the testimonials data depending on how we are asking for it.
 */
  private function get_testimonials($format=NULL)
  {
    # defaults
    $where  = array('publish' => 1);
    $sort   = array('created' => 'desc');
    
    # filter by tag
    if(is_numeric($this->active_tag))
      $where['tag_id'] = $this->active_tag;
    else
      $where['site_id'] = $this->site_id;
    
    # sort by
    switch($this->active_sort)
    {
      case 'newest':
        $sort = array('created' => 'desc');
        break;
      case 'oldest':
        $sort = array('created' => 'asc');
        break;
    }

    # get full count of testimonials for this tag.
    $total_testimonials = ORM::factory('testimonial')
      ->where($where)
      ->orderby($sort)
      ->count_all();
    
    # get the appropriate testimonials based on page.
    $limit = 2;
    $offset = ($this->active_page*$limit) - $limit;
    $testimonials = ORM::factory('testimonial')
      ->where($where)
      ->orderby($sort)
      ->limit($limit, $offset)
      ->find_all();
    

    /*
    # build the pagination html
    $pagination = new Pagination(array(
      'base_url'       => "/?tag=$this->active_tag&sort=$this->active_sort&page=",
      'current_page'   => $this->active_page, 
      'total_items'    => $total_testimonials,
      'style'          => 'testimonials' ,
      'items_per_page' => $limit
    ));
    */
    
    
    # Return Standalone Ajax - 
    # testimonials_data (sorters & pagination).
    if(!$this->is_api AND 'ajax' == $format AND isset($_GET['sort']))
    {
      $view = new View('testimonials/testimonials_data');
      $view->testimonials = $testimonials;
      $view->pagination = $pagination;
      die($view);
    }

    # else we are returning an entirely new tag view.
    
    # Return JSON to widget
    if($this->is_api)
    {
      $testimonial_array = array();
      foreach($testimonials as $testimonial)
      {
        $data = $testimonial->as_array();
        $data['name']     = $testimonial->patron->name;
        $data['position']	= $testimonial->patron->position;
        $data['company']  = $testimonial->patron->company;
        $data['location'] = $testimonial->patron->location;
        $data['url']      = $testimonial->patron->url;
        $data['tag_name'] = $testimonial->tag->name;
        $testimonial_array[] = $data;
      }

      # should we specify a next page link?
			$page_vars = '';
      if($total_testimonials > $offset + $limit)
      {
        $next_page = $this->active_page+1;
        $page_vars = "'$next_page', '$this->active_tag', '$this->active_sort'";
      } 
            
      $json_testimonials = json_encode($testimonial_array);
      #pagination html.
      #$pagination = str_replace(array("\n","\r","\t"), '', $pagination);
        
      header('Cache-Control: no-cache, must-revalidate');
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
      header('Content-type: application/json');
      die("pandaDisplayRevs($json_testimonials);pandaPages($page_vars);");
    }
    
    # Return New Tag ajax view.
    # or standalone non-ajax.
    $view = new View('testimonials/get_testimonials');
    $view->testimonials = $testimonials;    
    $view->pagination = $pagination;
    $view->set_global('active_tag', $this->active_tag);
    $view->set_global('active_sort', $this->active_sort);
    return $view;
  }
  
  
/*
 * build the embeddable widget javascript environment.
 * cache the result
 */
  private function widget()
  {  
    $keys = array("\n","\r","\t");
    
    # get all the html interfaces.    
    $tag_list = build_testimonials::tag_list($this->site->tags, $this->active_tag);
    $sorters = build_testimonials::sorters($this->active_tag, $this->active_sort, 'widget');    
    $testimonial_html = build_testimonials::testimonial_html(NULL, $this->site_id);
      
    # build an object to hold the html.
    $html = new StdClass(); 
    $html->tag_list  = str_replace($keys, '', $tag_list);
    $html->sorters  = str_replace($keys, '', $sorters);

    # load the widget_js view and place the html as json.
    $widget_js = new View('testimonials/widget_js');
    $widget_js->theme = $this->theme;
    $widget_js->apikey = $this->apikey;
    $widget_js->asset_url = paths::testimonial_image_url($this->site_id);
    $widget_js->json_html = json_encode($html);
    $widget_js->testimonial_html = str_replace($keys, '', $testimonial_html);
    
    
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
      die($this->get_testimonials('ajax'));
      
    die('invalid api parameters');
  }    
  
  
} // End testimonials Controller
