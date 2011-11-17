<?php
class Svs_Controller_Action_Helper_Cache
	extends Zend_Controller_Action_Helper_Abstract
{
	
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var Zend_Cache_Core
	 */
	private $_cache;
	private $_manager;
		
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * @see Zend_Controller_Action_Helper_Abstract
	 */
	public function init()
	{
		$this->_manager = Zend_Controller_Front::getInstance()
						->getParam('bootstrap')
						->getResource('cachemanager');
	}
	
	/**
	 * gets a cache object and checks whether to clean up the old files
	 * 
	 * @param string $type
	 * @return Zend_Cache_Core $cache 
	 */
	public function getCache($type)
	{
		$this->_cache = $this->_manager->getCache($type);
		
		/* 
		$date = new Zend_Date();
		$dayNum = $date->get(Zend_Date::WEEKDAY_DIGIT);
		
		switch($dayNum){
			case 1:
			case 3:
			case 5:
				$this->_cache->clean(Zend_Cache::CLEANING_MODE_OLD);
				break;
			default:
				break;
		}*/
		
		return $this->_cache;
	}
	
	
	public function direct($type, $lifetime = null)
	{
		$this->getCache($type)->setLifetime($lifetime);
		return $this->_cache;	
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	
	
	
}
