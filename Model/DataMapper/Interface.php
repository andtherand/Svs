<?php

interface Svs_Model_DataMapper_Interface {
		
	/**
	 * finds a certain record by it´s id
	 * 
	 * @param 	int|mixed $id 	the id of the record to find 
	 * @return 	Svs_Model_EntityAbstract 
	 */
	public function findById($id);
	
	/**
	 * finds all records of a specific defined criteria
	 * 
	 * @param	[Zend_Db_Table_Select $criteria a criteria] 
	 * @return 	Svs_Model_CollectionAbstract 
	 */
	public function findAll($criteria = null);
	
	/**
	 * searches for a record defined by the search criteria
	 * 
	 * @param 	Zend_Db_Table_Select|array $criteria criteria to filter 
	 * @param 	[bool $showSQL flag whether or not to show sql] 
	 * @throws	Svs_Model_Exception	when criteria is not an array
	 * @return	Svs_Model_CollectionAbstract|Svs_Model_Entity|null
	 */
	public function search($criteria, $showSQL = false);
	
	/**
	 * persists a given entity
	 * 
	 * @param	Svs_Model_EntityAbstract $entity 	the entity to persist
	 * @return	mixed 	returns the primary key just saved 
	 */
	public function save(Svs_Model_EntityAbstract $entity);
	
	
	/**
	 * deletes an entity from the given persistence storage
	 * 
	 * @param 	int $id		the id of the entity to delete
	 * @return	boolean
	 */
	public function delete($id);
}
