<?php defined('SYSPATH') OR die('No direct access allowed.');

class Flag_Model extends ORM {
	
	// Relationships
	#protected $has_and_belongs_to_many = array('account_users');
	protected $has_one = array('review');

	public function __set($key, $value)
	{
		parent::__set($key, $value);
	}




	

} // End tag Model