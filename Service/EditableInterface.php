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
	 * @param	[bool $flag indicates whether or not the form will be returned with populated data] 
	 * @return 	Zend_Form 
	 */
	public function getPopulatedForm($id, $flag = true);
	
	/**
	 * deletes a given entity by it´s id
	 * 
	 * @param 	int $id The id of the entity to delete
	 * @throws	Svs_Model_Exception when no request is given and no 
	 * 								request has been explicitly been set
	 * @throws	Svs_Model_Exception	when no id has been provided in the request
	 */
	public function delete($id);
	
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
