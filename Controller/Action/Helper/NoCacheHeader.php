<?php

class Svs_Controller_Action_Helper_NoCacheHeader
	extends Zend_Controller_Action_Helper_Abstract
{
	//-------------------------------------------------------------------------
	// - VARS
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * strategy pattern to call the Helper directly
	 */
	public function direct()
	{
		$view = $this->getActionController()->view; 
		$view->headMeta()
    		   ->appendHttpEquiv('cache-control', 'no-cache, no-store')
			   ->appendHttpEquiv('expires', 0)
			   ->appendHttpEquiv('pragma', 'no-cache, no-store');
		/*
		$this->getResponse()
			->setHeader(
				'Cache-Control',
				'no-cache, no-store, max-age=0, must-revalidate,' .
				' post-check=0, pre-check=0'
			)->setHeader('Pragma', 'no-cache, no-store')
			->setHeader('Expires', 'Tue, 27 Aug 1985 20:20:20 GMT');
		 *
		 */
	}
	
}