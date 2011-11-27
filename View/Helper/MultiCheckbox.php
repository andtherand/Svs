<?php

class Svs_View_Helper_MultiCheckbox extends Svs_View_Helper_RadioButton
{
    /**
     * Input type to use
     * @var string
     */
    protected $_inputType = 'checkbox';

    /**
     * Whether or not this element represents an array collection by default
     * @var bool
     */
    protected $_isArray = true;
    /**
     * Generates a set of checkbox button elements.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The checkbox value to mark as 'checked'.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the checkbox value, and the array value is the radio text.
     *
     * @param array|string $attribs Attributes added to each radio.
     *
     * @return string The radio buttons XHTML.
     */
    public function multiCheckbox($name, $value = null, $attribs = null,
        $options = null, $listsep = "\n")
    {
    	$html = '<ul class="multi-checkboxes">';
    	$html .= $this->radioButton($name, $value, $attribs, $options, "");
		$html .= '</ul>';
        return $html;
    }
	
}