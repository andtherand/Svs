<?php
    
abstract class Svs_Model_DataMapper_Abstract  
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTable;
	
	/**
	 * 	holds sql strings for debug reasons 
	 *  @var array
	 */
	protected $_selects = array();
	
	
	protected $_db;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * creates a new instance of the mapper
	 * and calls the init hook
	 */
	public function __construct()
	{
		$this->_init();
	}
	
	
	/**
	 * adds a select statement to the selects array
	 * provides a fluid interface
	 * 
	 * @param	Zend_Db_Table_Select $select the select query
	 * @return  Svs_Model_DataMapper_Abstract 
	 */
	public function addSelect($select)
	{
		if(!in_array($select, $this->_selects)){
			$this->_selects[] = (string)$select;
		}
		return $this;
	}
	
	/**
	 * exposes the called sql strings
	 * 
	 * @return 	array
	 */
	public function exposeSelects()
	{
		return $this->_selects;
	}
	
	/**
	 * sets the required dbTable so we can start mapping
	 * 
	 * @param 	string $dbTable the name of the dbTable to create
	 * @throws 	Svs_Model_Exception when no dbTable instance was created
	 * @return 	Svs_Model_DataMapper_Abstract
	 */
	public function setDbTable($dbTable)
    {
        if(is_string($dbTable)){
            $dbTable = new $dbTable();
        }
		
        if(!$dbTable instanceof Zend_Db_Table_Abstract){
            throw new Svs_Model_Exception('Given table gateway is invalid!');
        }
		
        $this->_dbTable = $dbTable;
        return $this;
    }	
	
	/**
	 * retrieves a dbTable if set else sets a new instance given by prefix and tablename 
	 * 
	 * @param	[string $prefix the desired module name or something alike] 
	 * @param	[string $tableName	the desired tablename to fetch from]
	 * @return 	Zend_Db_Table_Abstract
	 */
	public function getDbTable($prefix = null, $tableName = null)
    {
		if(null === $this->_dbTable 
			&& null !== $tableName
			&& null !== $prefix
		){
		    $this->setDbTable(	
		    	sprintf('%s_Model_DbTable_%s',
		    		ucfirst($prefix),
		    		ucfirst($tableName) 
				)
			);
		}
		return $this->_dbTable;
    }
	
	/**
	 * returns a select object to query 
	 * 
	 * @return 	Zend_DbTable_Select
	 */
	public function getSelect($from = Zend_Db_Table::SELECT_WITHOUT_FROM_PART)
	{
		return $this->getDbTable()->select($from);
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * initializes the mapper
	 * to be overriden by subclass
	 */
	protected function _init()
	{
		$this->_db = $this->getDbTable()->getAdapter();		
	}
	
	/**
	 * builds a query string
	 * 
	 * @param	array $criteriaArray	an array of arrays
	 * 									array('operandName' => array(array(condition => value1),...))
	 * @throws	Svs_Model_Exception		when criteria array is not an array		
	 * @return 	Zend_Db_Table_Select
	 */
	protected function _buildCriteria($criteriaArray)
	{
		if(!is_array($criteriaArray)){
			throw new Svs_Model_Exception(
				'No criteria given, unable to build select'
			);
		}
		
		$select = $this->_dbTable->select();
		
		foreach($criteriaArray as $condition => $criteria){
			if(is_array($criteria)){
				$this->_extractCriteria($criteria, $select,	$condition);							
			}
		}
		return $select;
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
	 * helper method to extract conditions to build a query string from an array of arrays
	 * 
	 * @param	array $criteriera 	the array to be examined
	 * 								array(array('condition1' => 'value1'), ...);
	 * @param	Zend_Db_Table_Select $select the selectobject to append to
	 * @param	string $operator the name of the operation 
	 * @return	Zend_Db_Table_Select 	
	 */
	private function _extractCriteria($criteria, $select, $operator){
		
		foreach($criteria as $wrapper => $condition){
			#var_dump($wrapper);
			if(is_array($condition)){
				$tmpKey = key($condition); // conditions like 'id < ?'  
				$select->$operator($tmpKey, $condition[$tmpKey]);
				
			} else {
				
				$select->$operator($condition);
			#	var_dump($condition);
			} 
		}
		#exit;
		return $select;
	}
	
}
