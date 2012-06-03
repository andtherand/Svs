<?php

abstract class Svs_Service_Abstract
	implements Svs_Service_ReadableInterface, Svs_Cache_CacheableInterface
{
	//-------------------------------------------------------------------------
	// - VARS

	/**
	 * handles the persistence
	 *
	 * @var 	Svs_Model_DataMapper_Abstract
	 */
	protected $_mapper;

	protected $_mappers = array();

    /**
     * @var Zend_Cache_Core
     */
    protected $_cache;

	//-------------------------------------------------------------------------
	// - PUBLIC

	/**
	 * instantiates a new service and calls the init hook
	 */
	public function __construct()
	{
		$this->_init();
	}

	/**
	 * retrieves a collection of service entities
	 *
	 * @throws Svs_Service_Exception
	 * @return Iterator
	 */
	public function findAll($criteria = null)
	{
		return $this->_mapper->findAll($criteria);
	}

	/**
	 * checks if a request has been set and if an id has been provided
	 * if those checks fail throws Svs_Service_Exception @see below.
	 * if everything goes well returns the specified domain object
	 *
	 * @param 	int $id the id of the entity to retrieve
	 *
	 * @return 	Svs_Model_Entity
	 */
	public function findById($id)
	{
		return $this->_mapper->findById($id);
	}

	/**
	 * retrieves a set mapper or lazyloads and sets a mapper
	 *
	 * @see		Svs_ReadibleInterface
	 * @param	[string $prefix  for example the module name]
	 * @param	[string $type  for example the type name of the mapper]
	 * @throws 	Svs_Service_Exception	when a given mapper class does not exist
	 * @return 	Svs_Model_DataMapper_Abstract
	 */
	public function getMapper($prefix = null, $type = null)
	{
		if(null !== $prefix && null !== $type){
			try {
				$this->_mapper = null;
				$this->setMapper($prefix, $type);

			} catch(Svs_Service_Exception $e){
				throw $e;
			}
		}

		return $this->_mapper;
	}

	/**
	 * sets the dataMapper for a service
	 * provides a fluid interface
	 *
	 * @see		Svs_ReadibleInterface*
	 * @param	Svs_Model_DataMapper_Abstract|string $mapper a string or a mapper_abstract
	 * @throws 	Svs_Service_Exception	when a given mapper class does not exist
	 * @return 	Svs_Model_Service_Abstract
	 */
	public function setMapper($prefix, $type)
	{
		$mapperInstance = null;
		$mapper = sprintf(
			'%s_Model_DataMapper_%s',
			ucfirst($prefix),
			ucfirst($type)
		);
		$mapperId = $prefix . '_' . $type;

		if(is_string($mapper)){
			if(!class_exists($mapper)){
				throw new Svs_Service_Exception(
					sprintf('The given mapper %s does not exist', $mapper)
				);
			}

			if (!array_key_exists($mapper, $this->_mappers)) {
				$mapperInstance = new $mapper();
				$this->_mappers[$mapperId] = $mapperInstance;

			} else {
				$mapperInstance = $this->_mappers[$mapperId];
			}
		}

		if($mapperInstance instanceof Svs_Model_DataMapper_Abstract){
			$this->_mapper = $mapperInstance;
		}
		return $this;
	}

	public function getMappers()
	{
		return $this->_mappers;
	}

    /**
     * injects the cache object
     * provides a fluid interface
     *
     * @param   Zend_Cache_Core $cache
     * @return  Svs_Service_Abstract
     */
    public function setCache(Zend_Cache_Core $cache){
        $this->_cache = $cache;

        return $this;
    }

    /**
     * checks if a cache has been set
     *
     * @return bool
     */
    public function hasCache()
    {
        return isset($this->_cache);
    }

	//-------------------------------------------------------------------------
	// - PROTECTED

	/**
	 * inits the service object
	 */
	abstract protected function _init();

	//-------------------------------------------------------------------------
	// - PRIVATE

}
