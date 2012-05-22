<?php

abstract class Svs_Service_Crud_Abstract
	extends Svs_Service_Configurable_Abstract
	implements Svs_Service_EditableInterface
{
	//-------------------------------------------------------------------------
	// - VARS

	/**
	 * @var string the label to be shown on the submit button in edit state
	 *
	 */
	protected $_editLabel = 'Save';

	protected $_module = '';

	protected $_controller = '';

	protected $_action = 'process';

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
			if ($form instanceof Svs_Form && $form->getRenderSubmit()) {
				$label = $form->hasUpdateMode()
						? $this->_editLabel
						: $form->getSubmitLabel();

				$form->getElement('submit')->setLabel($label);
			}
			return $form;
		}
		// everything went better than expected :}
		return $this->_mapper->save(
			$this->_convertPostToDomainObject()
		);
	}

	/**
	 * deletes a given entity by it´s id
	 *
	 * @param 	int	$id The id of the entity to delete
	 * @return	int $id the id which has bee deleted
	 */
	public function delete($id)
	{
		return $this->_mapper->delete($id);
	}

	/**
	 * retrieves a form populated with the data of an entity.
	 * if $flag is set to false returns an unpopulated form
	 *
	 * @param	[int $id the id of the entity to fetch]
	 * @param 	[bool $flag indicates whether or not the form will be returned with populated data]
	 * @return 	Svs_Form
	 */
	public function getPopulatedForm($id = null, $flag = true)
	{
		$form = $this->_buildForm($this->_formType, $flag);

		if($flag){
			$form->setSubmitLabel($this->_editLabel);
			$form->populate($this->findById($id)->toArray());
		}

		return $form;
	}

	/**
	 * sets the label for the edit state of the submit button
	 * provides a fluid interface
	 *
	 * @param	string $label the label to set
	 * @return 	Svs_Service_Crud_Abstract
	 */
	public function setEditLabel($label)
	{
		$this->_editLabel = $label;
		return $this;
	}

	/**
	 * gets the edit label
	 * @return 	string
	 */
	public function getEditLabel()
	{
		return $this->_editLabel;
	}

	/**
	 * @param 	string $module the current module name
	 * @param 	string $controller the current controller name
	 * @return 	Svs_Service_Crud_Abstract
	 */
	public function setURLChunks($module, $controller)
	{
		$this->_module = $module;
		$this->_controller = $controller;
		return $this;
	}

	public function getModule()
	{
		return $this->_module;
	}

	public function getController()
	{
		return $this->_controller;
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
			$this->getModule(),
			$action,
			$this->getController()
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

	/**
	 * helper function: builds the form
	 *
	 * @param	string|Zend_Form $form	a string or an instance of zend_form
	 * @param	[bool $flag if the form is used to update]
	 * @param	[string $action the action to redirect to when processing the form]
	 * @return 	Svs_Form
	 */
	protected function _buildForm($form, $flag = true, $action = 'process')
	{
		$this->setForm($form);
		$form = $this->getForm()->setAction($this->_assembleActionURL($action));
		$form->setUpdateMode($flag);

		return $form;
	}

}
