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


/*
 * update
 */
 public function index()
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
