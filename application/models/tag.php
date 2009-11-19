<?php defined('SYSPATH') OR die('No direct access allowed.');

class Tag_Model extends ORM {
	
	// Relationships
	#protected $has_and_belongs_to_many = array('account_users');
	#protected $has_many = array('tags');
	protected $sorting = array('position'=>'asc');

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

		}
		return parent::save();
	}
	


	

} // End tag Model