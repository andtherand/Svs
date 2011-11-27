<?php
    
interface Svs_Service_ConfigurableInterface
{
	//-------------------------------------------------------------------------
	// - VARS
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * retrieves the given instance of the form in a lazyload manner
	 * 
	 * @param	[string $prefix e.g. the module name]
	 * @param	[string $type e.g. the controller name]
	 * @throws 	Svs_Model_Exception when the given form does not exist
	 * @return 	Zend_Form	
	 */
	public function getForm($prefix = null, $type = null);
	
	/**
	 * sets the form for later validation of domain objects.
	 * takes a string or an instance of Zend_Form
	 * provides a fluid interface
	 * 
	 * @param	string|Zend_Form $form an instance of zend form or a string; for validation
	 * @throws	Svs_Model_Exception	when given the form does not exist 
	 * @return 	Svs_Model_Service_Abstract
	 */
	public function setForm($form);
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
