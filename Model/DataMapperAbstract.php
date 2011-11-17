<?php

/**
 * TODO: make the abstract work!
 */
abstract class Svs_Model_DataMapperAbstract 
	implements Svs_Model_DataMapperInterface 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var 	end_Db_Table_Abstract  defines the table in a database to use
	 */
	protected $_dbTable;
	
	/**
	 * @var 	string 	the entity class to be used 
	 */
	protected $_entityClass;
	
	/**
	 * @var 	string
	 */
	protected $_entityTableName;
	
	/**
	 * @var 	Svs_Model_CollectionAbstract
	 */
	protected $_collection;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * @see 	Svs_Model_DataMapperInterface
	 */
	public function findById($id)
	{
		$result = $this->getDbTable()->find($id);
		
	}
	
	/**
	 * @see 	Svs_Model_DataMapperInterface
	 */
	public function findAll()
	{
		$select = $this->getDbTable()->fetchAll();
	}
	
	/**
	 * @see 	Svs_Model_DataMapperInterface
	 */
	public function search($criteria)
	{
		
	}
	
	public function save(Svs_Model_EntityAbstract $entity)
	{
		
	}
	
	/**
	 * @see 	Svs_Model_DataMapperInterface
	 */
	public function delete($id)
	{
		
	}
	
	/**
	 * sets the entity table name to use for lazyloading the dbTable
	 * provides a fluid interface
	 * 
	 * @param 	string $name the name of the dbTable Class to use
	 * @return 	Svs_Model_DataMapperAbstract
	 */
	public function setEntityTableName($name)
	{
		if(empty($name) || !is_string($name)){
			throw new Svs_Model_Exception(
				sprintf('The Table name: %s given is invalid', $name)
			);
		}
		$this->_entityTableName = $name;
		return $this;
	}
	
	
	public function getEntityTableName()
	{
		return $this->_entityTableName;	
	}
	
	/**
	 * retrieve the persistence Adapter
	 * 
	 * @return 	Zend_Db_Table_Abstract
	 */
	public function getDbTable()
	{
		if(null === $this->_dbTable && null !== $this->_entityTableName){
			$this->setDbTable($this->_entityTableName);	
			
		}
		return $this->_dbTable;
	}
	
	/**
	 * sets the dbTable to be used
	 * provides a fluid interface
	 * 
	 * @param	Zend_Db_Table_Abstract $dbTable	the dbTable to use
	 * @return	Svs_Model_DataMapperAbstract
	 */
	public function setDbTable(Zend_Db_Table_Abstract $table)
	{
		$this->_table = $table;
		return $this;
	}
		
	/**
	 * returns the entity class to be used 
	 * 
	 * @return 	string
	 */
	public function getEntityClass()
	{
		return $this->_entityClass;	
	}
		
	/**
	 * sets the entity class to use if the given class exists
	 * provides a fluid interface
	 * 
	 * @param	string $class 	The entity class to be used later one
	 * @throws  Svs_Model_Exception	when class does not exist
	 * @return 	Svs_Model_DataMapperAbstract
	 */
	public function setEntityClass($class)
	{
		if(!class_exists($class)){
			throw new Svs_Model_Exception(
				sprintf('Entity class %s does not exist!', $class)
			);
		}
		$this->_entityClass = $class;
		return $this;
	}
	
	/**
	 * returns the used collection of entities
	 * 
	 * @return	Svs_Model_CollectionAbstract
	 */
	public function getCollection()
	{
		return $this->_collection;
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
