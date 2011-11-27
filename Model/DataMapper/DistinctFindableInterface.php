<?php

interface Svs_Model_DataMapper_DistinctFindableInterface 
	extends Svs_Model_DataMapper_ReadableInterface 
{
		
	/**
	 * finds a certain record by it´s id
	 * 
	 * @param 	int|mixed $id 	the id of the record to find 
	 * @return 	Svs_Model_EntityAbstract 
	 */
	public function findById($id);
	
	
}
