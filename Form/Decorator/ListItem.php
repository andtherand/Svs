<?php
    
class Svs_Form_Decorator_ListItem extends Zend_Form_Decorator_Abstract 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
     * Default placement: surround content
     * @var string
     */
    protected $_placement = null;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * renders the decorator
	 * @param	string? $content
	 * @return	string
	 */
	public function render($content)
	{
		/**
		 * @var Zend_Form_Element
		 */
		$element 		= $this->getElement();
		$elementName 	= $element->getName();
		
		$label  = $this->_buildLabel();
		$input  = $this->_buildInput();
		$desc   = $this->_buildDescription();
		$errors	= $this->_buildErrors();
		
		$output = $label . $input . $desc . $errors;
		$classOption = $this->getOption('class');
		$class  =  $classOption ? sprintf(' class="%s"', $classOption) : '';
		
		if(
			$element instanceof Zend_Form_Element_Hash ||
			$element instanceof Zend_Form_Element_Hidden
		){
			$class = ' class="hidden"';
		}
		
		return sprintf(
			'<li id="%s-element"%s>%s%s</li>', 
			$elementName, $class, $output, $content
		);
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
	 * helper function to build the formlabel
	 * 
	 * @return	string 
	 */	
	private function _buildLabel()
	{
		$element = $this->getElement();
		$label   = $element->getLabel();
		
		if($label === null){
			return '';
		}
		
		return $element->getView()
					   ->formLabel($element->getName(), $label);
	}
	
	/**
	 * helper function to build the input
	 * 
	 * @return 	string
	 */
	private function _buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
		
		$attribs = $element->getAttribs();
		if(array_key_exists('helper', $attribs)){
			unset($attribs['helper']);
		}
		
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $attribs,
            $element->options
        );
    }
	
	/**
	 * builds the error Messages
	 * 
	 * @return	string 
	 */
	private function _buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return '<div class="errors">' .
               $element->getView()->formErrors($messages) . '</div>';
    }
	
	/**
	 * builds the descritpion
	 * 
	 * @return 	string
	 */
	private function _buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) {
            return '';
        }
        return '<div class="description">' . $desc . '</div>';
    }
}
