<?php

class Svs_Controller_Action_Helper_Paginator 
	extends Zend_Controller_Action_Helper_Abstract
{
	//-------------------------------------------------------------------------
	// -- PUBLIC
	
	/**
	 * strategy pattern
	 * 
	 * @param array $result
	 * @param [int $itemsPerPage]
	 * @param [string $scrollStyle]
	 * @return void
	 */
	public function direct(
		$result, $itemsPerPage = 20, $scrollStyle = 'Sliding'
	)
	{
		$view = $this->getActionController()->view;
				
		Zend_Paginator::setDefaultScrollingStyle($scrollStyle);
		$paginator = Zend_Paginator::factory($result);
				
		$paginator->setItemCountPerPage($itemsPerPage);
		$paginator->setCurrentPageNumber(
			$this->getRequest()->getParam('page', 1));
		
		$view->paginator = $paginator;	
	}	
}
