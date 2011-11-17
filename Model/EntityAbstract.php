<?php

abstract class Svs_Model_EntityAbstract
{
	
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var array holds all fields of any given entity
	 */
	protected $_data = array();
	/**
	 * @var int|string holds a uniquie identifier for a specific entity 
	 */
	protected $_id = null;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * constructs a new entity
	 * 
	 * @param [mixed $options] 
	 */	
	public function __construct($options = null)
	{
		$this->_init($options);
	}
	
	/**
	 * sets the Fields of a given entity
	 * 
	 * @param 	array $options assoc array with key => value pairs to be set as entity fields
	 * @return 	Svs_Model_Entity fluid interface
	 */
	public function setFields(array $options = array())
	{
		if(!empty($options)) {
			foreach($options as $key => $value){
				$this->$key = $value;	
			}	
		}
		return $this;
	}
	
	/**
	 * returns the specified field in a more verbose way
	 * 
	 * @param 	string $key the name of the field one wants to retrieve
	 * @param 	mixed $default] an optional default value may be given
	 * @return 	mixed | null $result the result of the specified field or 
	 * 						 if the key equals id the id 
	 */
	public function get($key, $default = null) 
	{
		$result = $default;
		
		if($key === 'id'){
			return $this->_id;	
		}
		
		if(array_key_exists($key, $this->_data)){
			$result = $this->_data[$key];
		}
		return $result;
	}
	
	/**
	 * sets the specific id to identify an entity
	 * if an id has already been set, directly return the fluid interface
	 * 
	 * @param 	int|string $id a unique identifier
	 * @return 	Svs_Model_Entity  fluid interface
	 */
	public function setId($id)
	{
		if($this->getId()){
			return $this;
		}
		$this->_id = $id;
		return $this;
	}
	
	/**
	 * gets the unique identifier of the entity
	 * 
	 * @return int|string the unique identifier
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * to be implemented by subclasses
	 */
	public function isValid()
	{}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * inits the entity
	 * 
	 * @param [mixed $options the key value pairs to define the entity]
	 */
	protected function _init($options = null)
	{
		if(is_array($options)){
			$this->setFields($options);
		}
	} 
	
	
	//-------------------------------------------------------------------------
	// - MAGIC METHODS
	
	/**
	 * sets the field of the entity if exists
	 * provides a fluid interface
	 * 
	 * @param 	string $key the name of the field
	 * @param 	mixed $value the value to be set
	 * @throws 	Svs_Model_Exception if an attribute is not a valid field in the entity
	 * @return 	Svs_Model_Entity fluid interface
	 */
	public function __set($key, $value)
	{
		if($key === 'id'){
			$this->_id = $value;
			
		} else {	
			$this->_data[$key] = $value;
		}
		return $this;
		/*		
		throw new Svs_Model_Exception(
			sprintf('Invalid attribute "%s"', $key), 1);*/
	}	
	
	/**
	 * gets the specified field 
	 * 
	 * @param 	string $key the field to retrieve
	 * @return 	mixed|null $result the specified field
	 */
	public function __get($key)
	{
		return $this->get($key); 
	}
	
	/**
	 * checks wheteher or not a field has been set
	 * 
	 * @param 	string $key the name of the field
	 * @return 	bool $flag 
	 */
	public function __isset($key)
	{
		if($key === 'id'){
			return isset($this->_id);
		} 
		return isset($this->_data[$key]);
	}
	
	/**
	 * unsets the given field if it´s set
	 * 
	 * @param 	string $key the field to delete  
	 */
	public function __unset($key)
	{
		if(isset($this->_data[$key])){
			unset($this->_data[$key]);
		}
	}
	
	/**
	 *  to be implemented by subclasses
	 * 
	 *  @return string 
	 */
	public function __toString()
	{
		$str = '';
		foreach($this->_data as $key => $value){
			$str .= sprintf('%s: %s', $key, $value);
		}
		return $str;
	}
	
	/**
	 * merges the entity to an array
	 * 
	 * @return array
	 */
	public function toArray()
	{
		$arr = $this->_data;
		$arr += array('id', $this->getId());
		return $arr;
	}

}
