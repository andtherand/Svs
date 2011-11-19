<?php
    
abstract class Svs_Service_Abstract 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var 	Svs_Model_DataMapper_Abstract
	 */
	protected $_mapper;
	
	/**
	 * @var 	Zend_Form
	 */
	protected $_form; 
		
	//-------------------------------------------------------------------------
	// - PUBLIC
	
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
			$mapper = sprintf('%s_Model_DataMapper_%s', $prefix, $type);
			if(class_exists($mapper)){ 
				$this->setMapper($mapper);
			}
			throw new Svs_Model_Exception(
				sprintf('The given DataMapper %s does not exist', $mapper)
			);
		}
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
	
	/**
	 * sets the form for later validation of domain objects
	 * provides a fluid interface
	 * 
	 * @param	Zend_Form	$form	an instance of zend form; for validation
	 * @return 	Svs_Model_Service_Abstract
	 */
	public function setForm(Zend_Form $form)
	{
		$this->_form = $form;
		
		return $this;
	}
	
	/**
	 * retrieves the given instance of the form
	 * 
	 * @return Zend_Form	
	 */
	public function getForm()
	{
		return $this->_form;		
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
