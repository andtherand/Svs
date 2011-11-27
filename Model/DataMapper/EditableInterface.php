<?php
    
interface Svs_Model_DataMapper_EditableInterface 
	extends Svs_Model_DataMapper_DistinctFindableInterface 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
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
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
