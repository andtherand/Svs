<?php

/**
 *
 * @author __my
 *
 */
class Svs_View_Helper_Img extends Zend_View_Helper_HtmlElement
{
	/**
	 * creates an <img/> tag 
	 * 
	 * @param array $options 
	 * @param [array $attribs] optional
	 * @return string
	 * @throws Zend_View_Exception when no src is given
	 */
	public function img(array $options, array $attribs = array()){
		$html = '<img ';

		if(!isset($options['src'])){
			throw new Zend_View_Exception('No image source specified.');
		}

		$html .= sprintf('src="%s" %s',  $options['src'], $this->_htmlAttribs($attribs));
		$html .= $this->_isXhtml() ? '/' : '' . '>';
		return $html;
	}
}