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
	private static $IS_CACHE_ON = false;

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

	/**
	 * strategy pattern
	 * @param  Svs_Cache_CacheableInterface $cacheable everything that can be cached
	 * @param  string $cacheType
	 * @param  string $regKey
	 * @return bool
	 */
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

	public function setCacheTags($tags)
	{
		$this->_cacheable->setCacheTags($tags);
		return $this;
	}

	//-------------------------------------------------------------------------
	// - PRIVATE

	private function _injectCache()
	{
		if (self::$IS_CACHE_ON) {

        	$this->_cacheable->setCache($this->_cache);
        }

        return $this->_cacheable->hasCache();
	}

	public static function enable($on)
	{
		self::$IS_CACHE_ON = $on;
	}


}
