<?php defined('SYSPATH') OR die('No direct access allowed.');

class Customer_Model extends ORM {
	
	// Relationships
	#protected $has_and_belongs_to_many = array('account_users');
	#protected $has_many = array('tags');
	protected $sorting = array('id' => 'desc');

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
		}
		return parent::save();
	}
	

	/**
	 * Tests if a username exists in the database. This can be used as a
	 * Valdidation rule.
	 *
	 * @param   mixed    id to check
	 * @return  boolean
	 */
	public function email_exists($id)
	{
		return (bool) $this->db
			->where($this->unique_key($id), $id)
			->count_records($this->table_name);
	}

	
	
	/**
	 * Allows the user model to be loaded by email field
	 */
	public function unique_key($id)
	{
		if ( ! empty($id) AND is_string($id) AND ! ctype_digit($id))
		{
			return 'email';
		}

		return parent::unique_key($id);
	}
	
	

	

} // End tag Model