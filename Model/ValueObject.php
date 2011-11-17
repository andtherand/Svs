<?php
/**
 * 
 */
class Svs_Model_ValueObject 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var array holds all fields
	 */
	protected $_data = array();
	
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * creates a new valueObject
	 * the options param is mandatory because no values can be set after the
	 * object construction
	 * 
	 * @param 	array $options the fields to be set
	 * @throws 	Svs_Model_Exception if a field is invalid
	 * @throws 	Svs_Model_Exception if the options array is empty
	 */
	public function __construct(array $options)
	{
		try {
			$this->_setFields($options);
			
		} catch (Svs_Model_Exception $e) {
			throw $e;
		}		
	}
	
	/**
	 * reads the value of a given ValueObject
	 * 
	 * @param 	string the required fieldname
	 * @throws 	Svs_Model_Exception
	 * @return 	mixed the value of the requested fields
	 */
	public function get($key)
	{
		if(!array_key_exists($key, $this->_data)){
			throw new Svs_Model_Exception(
				'VO has invalid state'
			);
		}
		$result = $this->_data[$key]; 
		return $result;
	}
	
	/**
	 * checks whether or not a given ValueObject equals another one 
	 * @param 	Svs_Model_ValueObject $o a valueObject to compare 
	 * @return 	bool 
	 */
	public function equals(Svs_Model_ValueObject $o)
	{
		return $this == $o;
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
	
	//-------------------------------------------------------------------------
	// - PROTECTED
		
	/**
	 * sets the fields of the value object
	 * 
	 * @see _set
	 * @param 	array $options the key value pairs
	 * @throws 	Svs_Model_Exception if the options array is empty
	 */
	protected function _setFields($options)
	{
		if(empty($options)){
			throw new Svs_Model_Exception(
				'VO has invalid state'
			);
		}
		
		foreach($options as $key => $value){
			$this->_set($key, $value);
		}
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
		
	/**
	 * sets a field defined by the key to the given value.
	 * is a readonly method because ValueObjects are by definition immutable
	 * 
	 * @param 	string $key the name of the field to be set
	 * @param 	mixed $value the value to be set
	 */
	private function _set($key, $value)
	{
		$this->_data[$key] = $value;		
	}
	
}
