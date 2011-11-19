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
		
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * creates a new form
	 * 
	 * @param [mixed $options] optional.
	 */
	public function __construct($options = null)
	{
		parent::__construct($options);	
	}
	
	/**
	 * inits the form
	 */
	public function init()
	{
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$action = $baseUrl . $this->_action;
				
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
		
		$elems[] = $this->_addHashElem();
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
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * adds a submit button
	 * 
	 * @return Zend_Form_Element $formElement
	 */
	protected function _pushSubmit()
	{
		$s = new Zend_Form_Element_Submit('submit');
		$s->setAttribs($this->_submitAttribs);
		$s->setLabel($this->_submitLabel);
		$s->setIgnore(true);
		return $s;
	}
}