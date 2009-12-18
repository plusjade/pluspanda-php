<?php defined('SYSPATH') OR die('No direct access allowed.');

class Testimonial_Model extends ORM {
	
	// Relationships
	#protected $has_and_belongs_to_many = array('account_users');
	protected $has_one = array('customer', 'tag');

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
			$this->token	 = text::random('alnum', 6);
		}
		
		#$this->body_edit = json_encode($this->body_edit);
		return parent::save();
	}
	

	
/*
 * handle uploaded file as image
 */
	public function save_image($site_id, $files, $id)
	{
		#echo kohana::debug($files); die();
		$image_types = array(
			'.jpg'	=> 'jpeg',
			'.jpeg'	=> 'jpeg',
			'.png'	=> 'png',
			'.gif'	=> 'gif',
			'.tiff'	=> 'tiff',
			'.bmp'	=> 'bmp',
		);			
		$img_dir = paths::testimonial_image($site_id);
		
		# was a file uploaded?
		if(!isset($files['image']['tmp_name']))
			return FALSE;
		if(!is_uploaded_file($files['image']['tmp_name']))
			return FALSE;
			
		# get extension
		$ext	= strtolower(strrchr($files['image']['name'], '.'));
		
		# is this an image?
		if(!array_key_exists($ext, $image_types))
			return FALSE;

		# sanitize the filename.
		$filename	= "$id$ext";

		$image	= new Image($files['image']['tmp_name']);			
		$width	= $image->__get('width');
		$height	= $image->__get('height');

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
	}
		
	
	
/*
 * save a new thumbnail using given crop params.
 */
	public function save_crop($site_id, $params)
	{
		if(!isset($params[1]))
			return 'Invalid Parameters';
			
		$img_path = paths::testimonial_image($site_id). "/full_$this->image";
		$image	= new Image($img_path);			
		$width	= $image->__get('width');
		$height	= $image->__get('height');

		# Make thumbnail from supplied post params.		
		$size = 125;
		$thumb_path = paths::testimonial_image($site_id). "/$this->image";
		
		$image
			->crop($params[0], $params[1], $params[2], $params[3])
			->resize($size, $size)
			->sharpen(20)
			->save($thumb_path);
		
		return 'Image saved!';	
	}
	

} // End tag Model