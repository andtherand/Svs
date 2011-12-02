<?php
    
abstract class Svs_Service_Abstract 
	implements Svs_Service_ReadableInterface
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * handles the persistence
	 *  
	 * @var 	Svs_Model_DataMapper_Abstract
	 */
	protected $_mapper;
	
	/**
	 * hands over the params to the service layer with the result of a really thin
	 * contoller
	 * 
	 *  @var 	Zend_Controller_Request_Abstract
	 */
	protected $_request;
		
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * instantiates a new service and calls the init hook
	 */
	public function __construct()
	{
		$this->_init();
	}
	
	/**
	 * retrieves a collection of service entities
	 * 
	 * @throws Svs_Service_Exception
	 * @return Iterator 
	 */
	public function findAll($criteria = null)
	{
		return $this->_mapper->findAll($criteria);
	}
	
	/**
	 * checks if a request has been set and if an id has been provided
	 * if those checks fail throws Svs_Service_Exception @see below.
	 * if everything goes well returns the specified domain object
	 * 
	 * @param 	[Zend_Controller_Request_Abstract|int $r the request 
	 * 												 to look for an id]
	 * @throws	Svs_Service_Exception when no request is given and no 
	 * 								request has been explicitly been set
	 * @throws	Svs_Service_Exception	when no id has been provided in the request
	 * @return 	App_Model_Service
	 */
	public function findById($r = null)
	{
		$id = $r;
		if(!is_int($id)){
			try {
				$id = $this->_hasRequest('id', $r);
				
			} catch(Svs_Service_Exception $e){
				throw $e;
			}
		} 				
		return $this->_mapper->findById($id);
	}
	
	/**
	 * retrieves a set mapper or lazyloads and sets a mapper
	 * 
	 * @see		Svs_ReadibleInterface
	 * @param	[string $prefix  for example the module name]
	 * @param	[string $type  for example the type name of the mapper]
	 * @throws 	Svs_Service_Exception	when a given mapper class does not exist
	 * @return 	Svs_Model_DataMapper_Abstract 
	 */
	public function getMapper($prefix = null, $type = null)
	{
		if(null === $this->_mapper && null !== $prefix && null !== $type){
			try {
				$this->setMapper(sprintf(
					'%s_Model_DataMapper_%s', ucfirst($prefix), ucfirst($type))
				);
				
			} catch(Svs_Service_Exception $e){
				throw $e;				
			}
		}
		
		return $this->_mapper;
	}
	
	/**
	 * sets the dataMapper for a service 
	 * provides a fluid interface
	 * 
	 * @see		Svs_ReadibleInterface* 
	 * @param	Svs_Model_DataMapper_Abstract|string $mapper a string or a mapper_abstract
	 * @throws 	Svs_Service_Exception	when a given mapper class does not exist	
	 * @return 	Svs_Model_Service_Abstract 
	 */
	public function setMapper($mapper)
	{
		if(is_string($mapper)){
			if(!class_exists($mapper)){
				throw new Svs_Service_Exception(
					sprintf('The given mapper %s does not exist', $mapper)
				);
			}
			$mapper = new $mapper();
		}
		
		if($mapper instanceof Svs_Model_DataMapper_Abstract){
			$this->_mapper = $mapper;
		}
		
		return $this;
	}
	
	/**
	 * sets the current request object to handle logic in the service layer
	 * provides a fluid interface
	 * 
	 * @param	Zend_Controller_Request_Abstract $r the current request object
	 * @return	Svs_Service_Abstract
	 */
	public function setRequest(Zend_Controller_Request_Abstract $r)
	{
		$this->_request = $r;
		return $this;
	}
	
	/**
	 * checks whether or not a request has been set
	 * 
	 * @return bool
	 */
	public function hasRequest()
	{
		return isset($this->_request);
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * helper function to keep things dry!
	 * checks whether or not a request has been set and if the request has an 
	 * id
	 * 
	 * @param	[string $param a param that should be retrieved from the request]
	 * @param	[mixed $default a default value the request param should have]
	 * @param	[Zend_Controller_Request_Abstract $r the specific request]
	 * @throws	Svs_Service_Exception	when no request has been provided
	 * @throws	Svs_Service_Exception	when no id could be found in the request
	 * @return	int
	 */
	protected function _hasRequest($param = 'id', $default = null,
		Zend_Controller_Request_Abstract $r = null
	){
		if($default instanceof Zend_Controller_Request_Abstract){
			$r = $default;
			$default = null;
		}
		
		if(null === $r && null === $this->_request && null === $default){
			throw new Svs_Service_Exception('No request provided');
		}
		
		if(null !== $r){
			$this->setRequest($r);
		}
		
		$rParam = $this->_request->getParam($param, $default); 	
		if(null === $rParam && null === $default){
			throw new Svs_Service_Exception(sprintf(
				'No parameter <i>%s</i> provided by this request', $param)
			);	
		}
		
		return $rParam;
	}
	
	/**
	 * inits the service object
	 */
	abstract protected function _init();
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
