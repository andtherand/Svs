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
	 * @return 	Svs_Model_CollectionAbstract 
	 */
	public function findAll();
	
	/**
	 * searches for a record defined by the search criteria
	 * 
	 * @param 	object 	$criteria criteria to filter 
	 * @throws	Svs_Model_Exception	when criteria is not an array
	 * @return	Svs_Model_CollectionAbstract|Svs_Model_Entity|null
	 */
	public function search($criteria);
	
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
