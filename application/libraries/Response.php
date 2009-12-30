<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * construct a proper server response
 * for ajax calls
 */

class Response {

  public $status = 'error';
  public $msg = 'something went wrong';

  /**
   * Returns a singleton instance of Response.
   *
   * @return  object
   */
  public static function instance()
  {
    static $instance;

    if ($instance == NULL)
    {
      // Initialize the URI instance
      $instance = new Response;
    }

    return $instance;
  }

  
/*
 * output the response in json
 */
  public function send()
  {
    die(json_encode($this));
    if(Request::is_ajax())
    {
      header('Cache-Control: no-cache, must-revalidate');
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
      header('Content-type: application/json');
      die(json_encode($this));
    }
    die($this->msg);
  }


} // end response library