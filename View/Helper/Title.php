<?php

class Svs_View_Helper_Title extends Zend_View_Helper_Abstract
{
	/**
	 * sets the title as a headline and the title tag
	 * 
	 * @param 	[string $title] optional. set this one to change the title
	 * @param 	[bool $escape] optional. escpae the title: default is true
	 * @return 	void
	 */
	public function title($title = 'Insert Title', $escape = true)
	{
		$this->setTitle($title, $escape);
	}
	
	
	/**
	 * sets the title as a headline and the title tag
	 * 
	 * @param 	[string $title] optional. set this one to change the title
	 * @param 	[bool $escape] optional. escpae the title: default is true
	 * @return 	void
	 */
	public function setTitle($title = 'Insert Title', $escape = true)
	{
		$title = $escape ? $this->view->escape($title) : $title;
		$this->view->placeholder('title')->append($title);
		$this->view->headTitle()->prepend($title);	
	}
}
