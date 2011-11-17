<?php
    
class Svs_Model_VO_RestrictedFields extends Svs_Model_ValueObject 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * restricts the fields of a value object
	 * @param array $options
	 * @throws Svs_Model_VO_Exception
	 */
	protected function _setFields($options)
	{
		foreach($options as $key => $value){
			
			if(!array_key_exists($key, $this->_data)){
				throw new Svs_Model_VO_Exception(
					sprintf('The given fieldname "%s" is not a valid option', $key)
				);
			}
		}
		parent::_setFields($options);
	} 
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
