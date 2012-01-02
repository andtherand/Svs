<?php
    
class Svs_Json_Gdata_Column 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	const NUMBER = 'number';
	const STRING = 'string';
	
	private $_data = array(
		'id' => '',
		'type' => '',
		'label' => ''
	);
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * creates a new column
	 * 
	 * @param	[array|string $id Optional. the id of the column or an array to be used for the whole column]
	 * @param	[string $label Optional. the label of the column]
	 * @param	[string $type Optional. one of the type strings specified as a constant]
	 */
	public function __construct($id = null, $label = null, $type = null)
	{
		if(is_array($id)){
			$this->_setOptions($id);
			$id = null;
		}
		
		if(null !== $id){
			$this->setId($id);
		} 
		
		if(null !== $label){
			$this->setLabel($label);
		} 
		
		if(null !== $type){
			$this->setType($type);
		} 
	}
	
	/**
	 * sets the label of the column
	 * provides a fluid interface
	 * 
	 * @param 	string $label the label of the column
	 * @return	Svs_Json_GData_Column
	 */
	public function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}
	
	/**
	 * gets the label of the column
	 * 
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}
	
	/**
	 * sets the type of the data in the column
	 * provides a fluid interface
	 * 
	 * @param 	string $type the type of the column choose one of the convienent constants
	 * @throws	Svs_Json_Exception when the type is empty
	 * @throws	Svs_Json_Exception when the type is not valid
	 * @return	Svs_Json_GData_Column
	 */
	public function setType($type = '')
	{
		if(empty($type)){
			throw new Svs_Json_Exception(
				'The type of the column must not be empty');
		}
		
		switch($type){
			// break omitted intentionally
			case self::NUMBER:
			case self::STRING:
				$this->type = $type;
				break;
				
			default:
				throw new Svs_Json_Exception(
					'The type must either be string or number');	
		}
		return $this;
	}
	
	/**
	 * gets the data type of a specific column 
	 * 
	 * @return string 
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 * sets the id of the column
	 * 
	 * @param 	string $id the id of the column
	 * @param	string $prefix a prefix for the id
	 * @return  Svs_Json_GData_Column 
	 */
	public function setId($id, $prefix = null)
	{		
		if(null === $prefix)
		{
			$this->id = strtolower($id);
						
		} else {
			$this->id = sprintf('%s-%s', strtolower($prefix), strtolower($id));
		}
		
		return $this;
	}
	
	/**
	 * rturns the id of the column
	 * 
	 * @return 	string 
	 */
	public function getId()
	{
		return $this->id;
	}
	
	public function toArray()
	{
		return $this->_data;	
	}	
	
	/**
	 * magic method to easily convert object to json string
	 * 
	 * @param	string $key the field to set
	 * @param 	mixed $value the value to set
	 */
	public function __set($key, $value)
	{
		if(array_key_exists($key, $this->_data)){
			$this->_data[$key] = $value;
		}
	}
	
	/**
	 * magic method to return a specific value
	 * 
	 * @param 	string $key the key to be returned
	 * @return	string
	 */
	public function __get($key)
	{
		return $this->_data[$key];
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * sets all fields 
	 * 
	 * @param	assoc array $options key => value pairs that have to match the field names 
	 */
	protected function _setOptions($options = array())
	{
		if(!empty($options)){
			foreach($options as $key => $value){
				$method = 'set' . ucfirst($key);
				if(method_exists($this, $method)){
					$this->$method($value);
				}
			}
		}
		return $this;
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
