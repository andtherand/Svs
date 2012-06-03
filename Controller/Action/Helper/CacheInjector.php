<?php
class Svs_Controller_Action_Helper_CacheInjector
	extends Zend_Controller_Action_Helper_Abstract
{

	//-------------------------------------------------------------------------
	// - VARS

	/**
	 * @var Zend_Cache_Core
	 */
	private $_cache = null;
	private $_manager = null;
	private $_cacheable = null;
	private $_regKey = 'cache';

	//-------------------------------------------------------------------------
	// - PUBLIC

	/**
	 * gets a cache object and checks whether to clean up the old files
	 *
	 * @param string $type
	 * @return Zend_Cache_Core $cache | null
	 */
	public function getCache($type = null)
	{
		if (null !== $type) {
			$this->_cache = $this->_manager->getCache($type);
		}
		return $this->_cache;
	}


	public function direct(Svs_Cache_CacheableInterface $cacheable = null, $cacheType = 'filecache', $regKey = 'cache')
	{
		if (null === $cacheable) {
			return $this;
		}

		$this->_manager = Zend_Controller_Front::getInstance()
			->getParam('bootstrap')
			->getResource('cachemanager');

		$hasCache = false;

		if (null !== $this->_manager) {
			$this->_cacheable = $cacheable;
			$this->getCache($cacheType);
			$hasCache = $this->_injectCache();

		}
		return $hasCache;
	}

	//-------------------------------------------------------------------------
	// - PRIVATE

	private function _injectCache()
	{
		$isCacheActive = Zend_Registry::get('config')->get($this->_regKey);
		if ($isCacheActive) {

        	$this->_cacheable->setCache($this->_cache);
        }

        return $this->_cacheable->hasCache();
	}



}
