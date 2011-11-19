<?php
    
abstract class Svs_Service_Abstract 
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
	 * handles the validation and serves the viewable representation of 
	 * the form to the controller
	 * 
	 * @var 	Zend_Form
	 */
	protected $_form; 
	
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
	 * retrieves a set mapper or lazyloads and sets a mapper
	 * 
	 * @param	[string $prefix  for example the module name]
	 * @param	[string $type  for example the type name of the mapper]
	 * @throws 	Svs_Model_Exception	when a given mapper class does not exist
	 * @return 	Svs_Model_DataMapper_Abstract 
	 */
	public function getMapper($prefix = null, $type = null)
	{
		if(null === $this->_mapper && null !== $prefix && null !== $type){
			try {
				$this->setMapper(sprintf(
					'%s_Model_DataMapper_%s', $prefix, $type)
				);
				
			} catch(Svs_Model_Exception $e){
				throw $e;				
			}
		}
		
		return $this->_mapper;
	}
	
	/**
	 * sets the dataMapper for a service 
	 * provides a fluid interface
	 * 
	 * @param	Svs_Model_DataMapper_Abstract|string $mapper a string or a mapper_abstract
	 * @throws 	Svs_Model_Exception	when a given mapper class does not exist	
	 * @return 	Svs_Model_Service_Abstract 
	 */
	public function setMapper($mapper)
	{
		if(is_string($mapper)){
			if(!class_exists($mapper)){
				throw new Svs_Model_Exception(
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
	 * sets the form for later validation of domain objects.
	 * takes a string or an instance of Zend_Form
	 * provides a fluid interface
	 * 
	 * @param	string|Zend_Form $form an instance of zend form or a string; for validation
	 * @throws	Svs_Model_Exception	when given the form does not exist 
	 * @return 	Svs_Model_Service_Abstract
	 */
	public function setForm($form)
	{
		if(is_string($form)){
			if(!class_exists($form)){
				throw new Svs_Model_Exception(
					sprintf('The given form %s does not exist', $form)
				);
			}
			$form = new $form();
		}
		
		if($form instanceof Zend_Form){
			$this->_form = $form;
		}
		
		return $this;
	}
	
	/**
	 * retrieves the given instance of the form in a lazyload manner
	 * 
	 * @param	[string $prefix e.g. the module name]
	 * @param	[string $type e.g. the controller name]
	 * @throws 	Svs_Model_Exception when the given form does not exist
	 * @return 	Zend_Form	
	 */
	public function getForm($prefix = null, $type = null)
	{
		if(null === $this->_form && null !== $prefix && null !== $type){
			try {
				$this->setForm(sprintf('%s_Form_%s', $prefix, $type));
				
			} catch(Svs_Model_Exception $e){
				throw $e;
			}
		}
		
		return $this->_form;
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
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * inits the service object
	 */
	abstract protected function _init();
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
