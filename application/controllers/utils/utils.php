<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
  * manual tasks.
 */

 class Utils_Controller extends Controller {

  
  public function __construct()
  {
    parent::__construct();
    
    if(empty($_GET['pw']) AND 'willow' !== $_GET['pw'])
      die('nothing');
  }


/*
 * manually clean "http://" from testimonials db table row "url".
 */
  public function http()
  {
    $testimonials = ORM::factory('testimonial')->find_all();
    foreach($testimonials as $testimonial)
    {
      $testimonial->url = str_replace('http://','', strtolower($testimonial->url));
      $testimonial->save();
    }
    echo 'clean was good';
  }
 
/*
 * manually add all owners to mailchimp newsletter.
 */
  public function email()
  {
    include Kohana::find_file('vendor/mailchimp','MCAPI');
    $config = Kohana::config('mailchimp');
    $mailchimp = new MCAPI($config['apikey']);

    $owners = ORM::factory('owner')->find_all();
    foreach($owners as $owner)
    {
      if(empty($owner->email))
        continue;
        
      echo kohana::debug($mailchimp->listSubscribe(
        $config['list_id'],
        $owner->email,
        '',
        'text',
        false,
        true,
        true,
        false
      ));
    }
    die('done');
  }
 
 
 
 
} // End utils Controller
