<?php
    
abstract class Svs_Model_Service_Abstract 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var 	Svs_Model_DataMapper_Abstract
	 */
	protected $_mapper; 
		
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * @return 	Svs_Model_DataMapper_Abstract 
	 */
	public function getMapper()
	{
		return $this->_mapper;
	}
	
	/**
	 * sets the dataMapper for a service
	 * provides a fluid interface
	 * 
	 * @param	Svs_Model_DataMapper_Abstract $mapper
	 * @return 	Svs_Model_Service_Abstract 
	 */
	public function setMapper(Svs_Model_DataMapper_Abstract $mapper)
	{
		$this->_mapper = $mapper;
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
