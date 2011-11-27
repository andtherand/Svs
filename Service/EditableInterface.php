<?php
    
interface Svs_Service_EditableInterface 
	extends Svs_Service_ConfigurableInterface
{
	//-------------------------------------------------------------------------
	// - VARS
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * retrieves the form
	 * @param	Svs_Form $form
	 * @return 	 
	 */
	public function getPopulatedForm($form);
	
	/**
	 * deletes a given entity by it´s id
	 * 
	 * @param 	[Zend_Controller_Request_Abstract $r the request 
	 * 												 to look for an id]
	 * @throws	Svs_Model_Exception when no request is given and no 
	 * 								request has been explicitly been set
	 * @throws	Svs_Model_Exception	when no id has been provided in the request
	 */
	public function delete(Zend_Controller_Request_Abstract $r = null);
	
	/**
	 * validates a post result 
	 * 
	 * @return	Svs_Form|Svs_Model_Abstract
	 */
	public function save($post);
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
