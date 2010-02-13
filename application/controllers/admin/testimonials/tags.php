<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * Manage the testimonial tags. (admin mode)
  */
  
 class Tags_Controller extends Admin_Template_Controller {

  public function __construct()
  {
    parent::__construct();

    $this->tag_id = (isset($_GET['id']))
      ? $_GET['id']
      : NULL;
      
    $this->rsp = Response::Instance();
  }
  
/*
 * manage testimonial categories.
 * this is the public wrapper.
 */
  public function index()
  {  
    $content = new View('admin/testimonials/categories_wrapper');
    $content->categories = $this->tags;
    
    if(request::is_ajax())
      die($content);
    
    $this->shell->content = $content;
    $this->active  = 'tags';
    $this->service = 'testimonials';
    die($this->shell);
  }

  
/*
 * return valid, singleton tag
 */
  private function get_tag()
  {
    valid::id_key($this->tag_id);
    
    $tag = ORM::factory('tag')
      ->where('owner_id',$this->owner->id)
      ->find($this->tag_id);
    if(!$tag->loaded)
    {
      $this->rsp->msg = 'Tag does not exist';
      $this->rsp->send(); 
    }
    return $tag;
  }
  
  
/*
 * add a new category handler
 */
  public function add()
  {
    if(!$_POST)
    {
      $this->rsp->msg = 'Nothing Sent';
      $this->rsp->send();
    }
    
    $max = ORM::factory('tag')
      ->select('MAX(position) as highest')
      ->where('owner_id', $this->owner->id)
      ->find();    
      
    $category = ORM::factory('tag');
    $category->owner_id  = $this->owner->id;
    $category->name     = $_POST['name'];
    $category->desc     = $_POST['desc'];
    $category->position = $max->highest+1;
    $category->save();
    
    $this->update_settings_cache();
    
    $this->rsp->status = 'success';
    $this->rsp->msg    = 'Categorgy Added!';
    $this->rsp->send();
  }


/*
 * delete an existing category
 */
  public function delete()
  {
    $this->get_tag()->delete();
    
    $this->update_settings_cache();
    
    $this->rsp->status  = 'success';
    $this->rsp->msg     = 'Tag Deleted!';
    $this->rsp->send();
  }

/*
 * save changes to a category
 */
  public function save()
  {
    if(!$_POST)
    {
      $this->rsp->msg = 'Nothing Sent.';
      $this->rsp->send();
    }
    
    $tag = $this->get_tag();
    $tag->name = $_POST['name'];
    $tag->desc = $_POST['desc'];
    $tag->save();
    
    $this->update_settings_cache();
    
    $this->rsp->status = 'success';
    $this->rsp->msg    = 'Tag Saved!';
    $this->rsp->send();
  }
  
/*
 * save order positions for categories.
 */
  public function order()
  {
    if(empty($_GET['cat']))
    {
      $this->rsp->msg    = 'Nothing Sent.';
      $this->rsp->send();
    }
    
    $db = Database::Instance();
    foreach($_GET['cat'] as $position => $id)
      $db->update('tags', array('position' => "$position"), "id = '$id' AND owner_id = '{$this->owner->id}'");   

    $this->update_settings_cache();
    
    $this->rsp->status = 'success';
    $this->rsp->msg    = 'Order Saved!';
    $this->rsp->send();
  }


  
} // End tags Controller
