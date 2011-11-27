<?php

interface Svs_Model_DataMapper_ReadableInterface 
{
		
	/**
	 * finds all records of a specific defined criteria
	 * 
	 * @param	[Zend_Db_Table_Select $criteria a criteria] 
	 * @return 	Svs_Model_CollectionAbstract|Iterator 
	 */
	public function findAll($criteria = null);
	
	/**
	 * searches for a record defined by the search criteria
	 * 
	 * @param 	Zend_Db_Table_Select|array $criteria criteria to filter 
	 * @param 	[bool $showSQL flag whether or not to show sql] 
	 * @throws	Svs_Model_Exception	when criteria is not an array
	 * @return	Svs_Model_CollectionAbstract|Iterator|Svs_Model_Entity|null
	 */
	public function search($criteria, $showSQL = false);
	
}
