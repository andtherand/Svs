<?php
    
abstract class Svs_Service_Configurable_Abstract 
	extends Svs_Service_Abstract
	implements Svs_Service_ConfigurableInterface 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * handles the validation and serves the viewable representation of 
	 * the form to the controller
	 * 
	 * @var 	Zend_Form
	 */
	protected $_form; 
	
	/**
	 * the form type to instantiate 
	 * 
	 * @var 	string
	 */
	protected $_formType;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
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
	 * @throws 	Svs_Service_Exception when the given form does not exist
	 * @return 	Zend_Form	
	 */
	public function getForm($prefix = null, $type = null)
	{
		$form = null;
		
		// if a lazy load get should be peformed 
		if(null !== $prefix && null !== $type){
			$form = sprintf('%s_Form_%s', ucfirst($prefix), ucfirst($type));
			$this->_form = null;
		}
		
		// a form already exists so return immediately
		if(null !== $this->_form){
			return $this->_form;
		}		
		
		// if a type is defined take it
		if(null !== $this->_formType){
			$form = $this->_formType;	
		}
		
		try {
			$this->setForm($form);
			
		} catch(Svs_Service_Exception $e){
			throw $e;
		}
		
		return $this->_form;
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
