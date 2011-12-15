<?php

class Svs_Form extends Zend_Form
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var string
	 */
	protected $_submitLabel = 'Search';
	
	/**
	 * @var array
	 */
	protected $_submitAttribs = array();
	
	/**
	 * @var bool	whether or not to insert an id element
	 */
	protected $_isInUpdateMode = false;
	
	/**
	 * @var bool	whether or not to have a hash to prevent csrf attacks
	 */
	protected $_preventCSRF = true;
	
	/**
	 * @var bool	indicates whether or not to automatically append a submit btn 
	 */
	protected $_renderSubmit = true;
		
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * creates a new form
	 * 
	 * @param [mixed $options] optional.
	 */
	public function __construct($options = null)
	{
		$this->addPrefixPath(
			'Svs_Form_Decorator', 'Svs/Form/Decorator', 'decorator');
		parent::__construct($options);	
	}
	
	/**
	 * inits the form
	 */
	public function init()
	{
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$action = $baseUrl . $this->_action;
		
		if(isset($this->_id)){
			$this->setAttrib('id', $this->_id);
		}
		
		$this->setAction($action)
			 ->setMethod(self::METHOD_POST);
		
		$this->addElements($this->_addFormElements());
	}
	
	/**
	 * sets the label of the submit button
	 * provides a fluid interface
	 * 
	 * @param 	string $str the label for the submit button
	 * @return 	Svs_Form fluid interface
	 */
	public function setSubmitLabel($str)
	{
		$s = $this->getElement('submit');
		if(
			$s instanceof Zend_Form_Element_Button ||
			$s instanceof Zend_Form_Element_Submit 
		){
			$s->setLabel($str);	
		}
				
		$this->_submitLabel = $str;
		return $this;
	} 
	
	/**
	 * sets the attributes of the submit button
	 * provides a fluid interface
	 * 
	 * @param 	array $attribs the attributes of the submit button
	 * @return 	Svs_Form fluid interface
	 */
	public function setSubmitAttribs(array $attribs = array())
	{
		$this->_submitAttribs = $attribs;
		
		return $this;
	}
	
	/**
	 * inserts a given className into every form element except the submit button
	 * 
	 * @param string $class a css class name
	 * @return Svs_Form fluid interface 
	 */
	public function setElementClass($class = '')
	{
		if(!empty($class)){
			$elems = $this->getElements();
			foreach($elems as $elem){
				if(
					!$elem instanceof Zend_Form_Element_Submit 
					|| !$elem instanceof Zend_Form_Element_Reset  
					|| !$elem instanceof Zend_Form_Element_Button  
				){
					$classes = $elem->getAttrib('class');
					$classString = $class;
					if(!empty($classes)){
						$classString .= ' ' . $classes;
					}
					$elem->setAttrib('class', $classString);
				}
			}
		}
		return $this;
	}
	
	/**
	 * sets the form into the update mode. adds a hidden id field to the form
	 * and calls the _notifyUpdate Hook
	 * provides a fluent interface
	 * 
	 * @param 	[bool $mode whether or not to set the update Mode]
	 * @return	Svs_Form
	 */
	public function setUpdateMode($mode = false)
	{
		$this->_isInUpdateMode = $mode;
		
		if(true === $mode){
			if(null !== ($idElem = $this->_addIdElem())){
				$this->addElement($idElem);
			}	
		}
		$this->_notifyUpdate();				
		return $this;
	}
	
	/**
	 * retrieves the status of the form
	 * 
	 * @return	bool
	 */
	public function hasUpdateMode()
	{
		return $this->_isInUpdateMode;
	}
	
	/**
	 * sets a flag if a submit button should be appended
	 * provides a fluid interface
	 * 
	 * @param	[bool $flag sets the flag]
	 * @return	Svs_Form 
	 */
	public function setRenderSubmit($flag = true)
	{
		$this->_renderSubmit = $flag;
		return $this;
	}
	
	/**
	 * gets the flag if a submit btn should be appended
	 * 
	 * @return 	bool
	 */
	public function getRenderSubmit()
	{
		return $this->_renderSubmit;
	}
			
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
	 * uses get_class_methods to retrieve all methods, filters them by the prefix
	 * of _addElem and adds them to an array which is eventually returned 
	 *
	 * @return array 
	 */
	private function _addFormElements()
	{
		$elems = array();
		$methodNames = get_class_methods($this);
		
        foreach($methodNames as $method){
            if(5 < strlen($method) && '_push' === substr($method, 0, 5)){
              $elems[] = $this->$method();
			}
        }
		
		if(true === $this->_preventCSRF){
			$elems[] = $this->_addHashElem();
		}
		
		if(true === $this->_renderSubmit){
			$elems[] = $this->_addSubmit();
		}
		
		return $elems;
	}
	
	/**
	 * adds a security mechanism to the form to prevent csrf attacks
	 * for a good explanation see http://de.wikipedia.org/wiki/Cross-Site_Request_Forgery
	 * 
	 * @return 	Zend_Form_Element_Hash
	 */
	private function _addHashElem()
	{
		$hash = new Zend_Form_Element_Hash('no_csrf');
		$hash->setSalt('unique');
		
		return $hash;
	}
	
	/**
	 * when in update mode and no id element has been added yet,
	 * adds a hidden id form element to the form
	 * 
	 * @return Zend_Form_Element_Hidden | null
	 */
	private function _addIdElem()
	{
		if(null === $this->getElement('id')){
			return new Zend_Form_Element_Hidden('id');
		}
		return null;
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * update hook @see 
	 * to be implemented by class who are interested on the event
	 */
	protected function _notifyUpdate()
	{}
			
	/**
	 * adds a submit button
	 * 
	 * @return Zend_Form_Element $formElement
	 */
	protected function _addSubmit()
	{
		$s = new Zend_Form_Element_Button('submit');
		$s->setAttribs($this->_submitAttribs)
		  ->setAttrib('type', 'submit')
		  ->setLabel($this->_submitLabel)
		  ->setIgnore(true);
		return $s;
	}
}