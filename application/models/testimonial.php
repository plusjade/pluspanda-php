<?php defined('SYSPATH') OR die('No direct access allowed.');

class Testimonial_Model extends ORM {
  
  // Relationships
  #protected $has_and_belongs_to_many = array('account_users');
  protected $has_one = array('patron', 'tag');

  public function __set($key, $value)
  {
    parent::__set($key, $value);
  }

  
  /**
   * Overload saving to set the created time and to create a new token
   * when the object is saved.
   */
  public function save()
  {
    if ($this->loaded === FALSE)
    {
      $this->created = time();
      $this->token   = text::random('alnum', 6);
      
      # save the patron data.
      $this->patron->save();
      $this->patron_id = $this->patron->id;
    }
    else
      $this->updated = time();
      
    #$this->body_edit = json_encode($this->body_edit);
    return parent::save();
  }
  
  /**
   * Overload delete to also delete the associated patron
   * when the object is deleted.
   */
  public function delete($id=NULL)
  {
    if ($id === NULL AND $this->loaded)
    {
      $this->patron->delete();
      #TODO: delete the associated images.
    }
    
    return parent::delete($id);
  }


/*
 * interface for fetching testimonials
 * based on defined filters, sorters, and limits.
 
 * filters: page, publish, tag, rating, date.
 * sorters: created
 */
  public function fetch($new_params, $get_count=FALSE)
  {
    $params = array(
      'site_id' => '',
      'page'    => 1,
      'tag'     => '',
      'publish' => '',
      'rating'  => '',
      'range'   => '',
      'created' => '',
      'updated' => '',
      'requests'=> FALSE,
      'limit'   => 10
    );
    foreach($new_params as $key => $value)
      $params[$key] = $value;
    
    #die(kohana::debug($params));
    
    $sort   = array('created' => 'desc');
    $where  = array();
    
    # filter by publish
    if('yes' == $params['publish'])
      $where['publish'] = 1;
    elseif('no' == $params['publish'])
      $where['publish'] = 0;
      
      
    # filter by tag
    if(is_numeric($params['tag']))
      $where['tag_id'] = $params['tag'];
    else
      $where['site_id'] = $params['site_id'];
        
    /*    
    # filter by rating
    if(is_numeric($params['rating']))
      $where['rating'] = $params['rating'];
    */

    # filter by date
    $now = time();
    $day = 86400;
    switch($params['range'])
    {
      case 'today':
      
        break;
      case 'last7':
        $where['created >='] = time() - $day*7;
        break;
      case 'last14':
        $where['created >='] = time() - $day*14;
        break;
      case 'last30':
        $where['created >='] = time() - $day*30;
        break;
      case 'ytd':
        $where['created >='] = mktime(0, 0, 0, 1, 1, date("m Y"));
        break;
    }  

    # filter by requests:
    if(FALSE !== $params['requests'])
      $where['requests'] = $params['requests'];
      
    #--sorters--
    switch($params['created'])
    {
      case 'newest':
        $sort = array('created' => 'desc');
        break;
      case 'oldest':
        $sort = array('created' => 'asc');
        break;
    }

    if($get_count)
      return $this->where($where)->count_all();
    
    # determine the offset and limits.
    $offset = ($params['page']*$params['limit']) - $params['limit'];
    
    return $this
      ->where($where)
      ->orderby($sort)
      ->limit($params['limit'], $offset)
      ->find_all();
  }

  
/*
 * handle uploaded file as image
 */
  public function save_image($apikey, $files, $id)
  {
    #echo kohana::debug($files); die();
    $image_types = array(
      '.jpg'  => 'jpeg',
      '.jpeg' => 'jpeg',
      '.png'  => 'png',
      '.gif'  => 'gif',
      '.tiff' => 'tiff',
      '.bmp'  => 'bmp',
    );      
    $img_dir = t_paths::image($apikey);
    
    # was a file uploaded?
    if(!isset($files['image']['tmp_name']))
      return array('error' => 'No image sent');
    if(!is_uploaded_file($files['image']['tmp_name']))
      return array('error' => 'No image sent');
      
    # get extension
    $ext  = strtolower(strrchr($files['image']['name'], '.'));
    
    # is this an image?
    if(!array_key_exists($ext, $image_types))
      return array('error' => 'File is not a valid image type');

    # sanitize the filename.
    $filename  = "$id$ext";

    $image  = new Image($files['image']['tmp_name']);      
    $width  = $image->__get('width');
    $height = $image->__get('height');

    # Make square thumbnails
    $size = 125;      
    if($width > $height)
      $image->resize($size, $size, Image::HEIGHT)->crop($size, $size);
    else
      $image->resize($size, $size, Image::WIDTH)->crop($size, $size);
    $image->save("$img_dir/$filename");

    # save original
    if(500 <= $width)
      $image->resize(500, 0, Image::WIDTH);
    $image->save("$img_dir/full_$filename");
    
    $this->image = $filename;
    $this->save();
    
    return array('success' =>'Image Uploaded!');
  }
    
  
  
/*
 * save a new thumbnail using given crop params.
 */
  public function save_crop($apikey, $params)
  {
    if(!isset($params[1]))
      return 'Invalid Parameters';
      
    $img_path = t_paths::image($apikey). "/full_$this->image";
    $image    = new Image($img_path);      
    $width    = $image->__get('width');
    $height   = $image->__get('height');

    # Make thumbnail from supplied post params.    
    $size = 125;
    $thumb_path = t_paths::image($apikey). "/$this->image";
    
    $image
      ->crop($params[0], $params[1], $params[2], $params[3])
      ->resize($size, $size)
      ->sharpen(20)
      ->save($thumb_path);
    
    return 'Image saved!';  
  }
  

/*
 * squash the data into an array to be sent out
 * via api calls.
 */
  public function prep_api()
  {
    $data = array();
    $data['body']     = $this->body;
    $data['rating']   = $this->rating;
    $data['image']    = $this->image;
    $data['created']  = $this->created;
    $data['name']     = $this->patron->name;
    $data['position'] = $this->patron->position;
    $data['company']  = $this->patron->company;
    $data['location'] = $this->patron->location;
    $data['url']      = $this->patron->url;
    $data['tag_name'] = $this->tag->name;
    return $data;
  }
  
  
  
  
  
  
  
  
  
  
} // End testimonial Model