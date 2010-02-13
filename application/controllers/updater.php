<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * perform any necessary work for the new release.
 */

 class Updater_Controller extends Controller {

  
  public function __construct()
  {      
    parent::__construct();
    
    if(empty($_GET['pw']) AND 'willow' !== $_GET['pw'])
      die('nothing');
  }

  public function version_7()
  {
    $testimonials = ORM::factory('testimonial')->find_all();
    foreach($testimonials as $testimonial)
    {
      $testimonial->name       = $testimonial->patron->name;
      $testimonial->company    = $testimonial->patron->company;
      $testimonial->c_position = $testimonial->patron->position;
      $testimonial->location   = $testimonial->patron->location;
      $testimonial->url        = $testimonial->patron->url;
      $testimonial->meta       = $testimonial->patron->meta;
      $testimonial->email      = $testimonial->patron->email;
      $testimonial->save();
    }
  
    die('merge testimonials was good');
    
    
    $owners = ORM::factory('owner')->find_all();
    foreach($owners as $owner)
    {
      $testimonials = ORM::factory('testimonial')
        ->where('owner_id', $owner->site->id)
        ->find_all();
      # echo kohana::debug($testimonials);
      foreach($testimonials as $testimonial)
      {
        $testimonial->owner_id = $owner->id;
        $testimonial->save();
      }
      
      #
      $questions = ORM::factory('question')
        ->where('owner_id', $owner->site->id)
        ->find_all();
      foreach($questions as $question)
      {
        $question->owner_id = $owner->id;
        $question->save();
      }
      
      #
      $tags = ORM::factory('tag')
        ->where('owner_id', $owner->site->id)
        ->find_all();
      foreach($tags as $tag)
      {
        $tag->owner_id = $owner->id;
        $tag->save();
      }
      
    }
    die('all good');
  
    
    # db version 7
    
    # manually executed **
    $owners = ORM::factory('owner')->find_all();
    #die(kohana::debug($owners));
    foreach($owners as $owner)
    {
      foreach($owner->sites as $site)
      {
        $owner->apikey = $site->apikey;
        $owner->save();
        
        $site->owner_id = $owner->id;
        $site->save();      
      }
    }

    
    die('update successful');
  }
 

 public function version_3()
 {
    #db version 3
    $sites = ORM::factory('site')->find_all();
    #die(kohana::debug($sites));
    foreach($sites as $site)
    {
      $site->apikey = text::random('alnum', 7);
      $site->save();
    }
    
    
    $patrons = ORM::factory('patron')->find_all();
    foreach($patrons as $patron)
    {
      $patron->token = text::random('alnum', 7);
      $patron->save();
    }
    
    $testimonials = ORM::factory('testimonial')->find_all();
    foreach($testimonials as $testimonial)
    {
      $testimonial->token = text::random('alnum', 7);
      $testimonial->save();
    }
    
    die('update successful');
 }
 
} // End updater Controller
