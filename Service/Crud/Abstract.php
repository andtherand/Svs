<?php
    
abstract class Svs_Service_Crud_Abstract 
	extends Svs_Service_Configurable_Abstract 
	implements Svs_Service_EditableInterface
{
	//-------------------------------------------------------------------------
	// - VARS
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * validates and saves a post result 
	 * 
	 * @return	Svs_Model_Abstract
	 */
	public function save($post)
	{
		$this->setForm($this->_formType);
		$form = $this->getForm();
						
		// it´s update time	so change the form accordingly
		if(array_key_exists('id', $post)){
			$form->setUpdateMode(true);
		}
		
		// something went wrong so return the form that generated the 
		// error
		if(!$form->isValid($post)){
			return $form;
		}
		
		// all went better than expected :}
		return $this->_mapper->save(
			$this->_convertPostToDomainObject()
		);
	}	
	
	/**
	 * deletes a given entity by it´s id
	 * 
	 * @param 	[Zend_Controller_Request_Abstract $r the request 
	 * 												 to look for an id]
	 * @throws	Svs_Model_Exception when no request is given and no 
	 * 								request has been explicitly been set
	 * @throws	Svs_Model_Exception	when no id has been provided in the request
	 */
	public function delete(Zend_Controller_Request_Abstract $r = null)
	{
		try {
			$id = $this->_hasRequest('id', $r);
			
		} catch(Svs_Model_Exception $e) {
			throw $e;
		}
		
		return $this->_mapper->delete($id);
	}
	
	/**
	 * retrieves a form populated with the data of an entity.
	 * if $flag is set to false returns an unpopulated form
	 * 
	 * @param 	[bool $flag indicates whether or not the form will be returned with populated data] 
	 * @return 	Svs_Form
	 */
	public function getPopulatedForm($flag = true)
	{
		$form = $this->_buildForm($this->_formType, $flag);
		
		if($flag){
			$form->setSubmitLabel('Service editieren');
			$form->populate($this->findById()->toArray());
		}
		
		return $form;		
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * to be implemented by sub class
	 */
	protected function _init()
	{}
	
	/**
	 * assembles the Action URL for the form
	 * 
	 * @param	[string $action the action to call]
	 * @return 	string 
	 */
	protected function _assembleActionURL($action = 'process')
	{
		return sprintf(
			'/%s/%s/%s',
			$this->_request->getModuleName(),
			$action,
			$this->_request->getControllerName()
		);
	}
	
	/**
	 * converts an array to a valide domain object
	 * the concrete implementation is up to the child class
	 * because of the concrete factories which have to be used
	 * 
	 * @return 	Svs_Model_Abstract
	 */
	abstract protected function _convertPostToDomainObject(); 
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
	 * helper function: builds the form
	 * 
	 * @param	string|Zend_Form $form	a string or an instance of zend_form
	 * @param	[bool $flag if the form is used to update]
	 * @param	[string $action the action to redirect to when processing the form]
	 * @return 	Svs_Form 
	 */
	private function _buildForm($form, $flag = true, $action = 'process')
	{
		$this->setForm($form);
		$form = $this->getForm()->setAction($this->_assembleActionURL($action));
		$form->setUpdateMode($flag);
		
		return $form;
	}
	
}
