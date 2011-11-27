<?php
    
interface Svs_Service_ReadableInterface 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * retrieves a mapper or lazyloads and sets a mapper
	 * 
	 * @param	[string $prefix  for example the module name]
	 * @param	[string $type  for example the type name of the mapper]
	 * @throws 	Svs_Model_Exception	when a given mapper class does not exist
	 * @return 	Svs_Model_DataMapper_Abstract 
	 */
	public function getMapper($prefix = null, $suffix = null);
	
	/**
	 * sets the dataMapper for a service 
	 * 
	 * @param	Svs_Model_DataMapper_Abstract|string $mapper a string or a mapper_abstract
	 * @throws 	Svs_Model_Exception	when a given mapper class does not exist	
	 * @return 	Svs_Model_Service_Abstract 
	 */
	public function setMapper($mapper);
	
	/**
	 * retrieves a collection of service entities
	 * 
	 * @param	[Zend_Db_Table_Select|mixed $criteria Optional criteria to retriev from the db]
	 * @return 	Svs_Model_Collection_Abstract 
	 */
	public function findAll($criteria = null);
	
	/**
	 * checks if a request has been set and if an id has been provided
	 * if those checks fail throws Model_Exception @see below.
	 * if everything goes well returns the specified domain object
	 * 
	 * @param 	[Zend_Controller_Request_Abstract|int $r the request 
	 * 												 to look for an id]
	 * @throws	Svs_Model_Exception when no request is given and no 
	 * 								request has been explicitly been set
	 * @throws	Svs_Model_Exception	when no id has been provided in the request
	 * @return 	App_Model_Service
	 */
	public function findById($r = null);
		
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
