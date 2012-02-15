<?php
    
class Svs_Json_GData_Table 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var array holds the column objects
	 */
	private $_columns = null;
	
	/**
	 * @var Svs_Json_Gdata_Rows 
	 */
	private $_rows = null;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * instantiates a new json table
	 */
	public function __construct()
	{
		$this->_init();
	}
	
	/**
	 * adds a bulk of columns to the table
	 * provides a fluid interface
	 * 
	 * @param	array $columns a list of columns to add those maybe arrays or Svs_Json_Columns
	 * @return 	Svs_Json_GData_Table  
	 */
	public function addColumns(array $columns)
	{
		foreach($columns as $column){
			$this->addColumn($column);
		}
		return $this;
	}
	
	/**
	 * adds a column object to the table as a json_encoded string
	 * provides a fluid interface 
	 * 
	 * @param 	array|Svs_Json_Column $column the column to add to the table
	 * @return 	Svs_Json_GData_Table
	 */
	public function addColumn($column)
	{
		if(is_array($column)){
			$this->_columns[] = $column;
		}	
		return $this;
	}
	
	/**
	 * returns the added columns
	 * 
	 * @return 	array
	 */
	public function getColumns()
	{
		return $this->_columns;
	}
	
	/**
	 * counts the columns of the table
	 * 
	 * @return	int
	 */
	public function countColumns()
	{
		return count($this->_columns);
	}
	
	/**
	 * checks whether or not a table has columns
	 * 
	 * @return 	bool
	 */
	public function hasColumns()
	{
		return 0 < $this->countColumns();
	}
	
	/**
	 * adds a bulk of rows to the table
	 * provides a fluid interface
	 * 
	 * @param	array $cells a list of columns to add those maybe arrays or Svs_Json_Rows
	 * @throws 	Svs_Json_Exception when no table has no columns
	 * @return 	Svs_Json_GData_Table  
	 */
	public function addRows(array $cells)
	{
		if($this->hasColumns()){
			
			$this->_rows->addCells($cells, $this->countColumns());
			
			if($this->countCellsPerRow() == $this->countColumns()){
				$this->_rows->addRow();
			}
			
			return $this;	
		}
		
		throw new Svs_Json_Exception(
			'Before adding rows, columns have to be provided');
	}
	
	/**
	 * adds a row object to the table 
	 * provides a fluid interface 
	 * 
	 * @param 	array $row the row to add to the table
	 * @throws 	Svs_Json_Exception when no table has no columns
	 * @return 	Svs_Json_GData_Table
	 */
	public function addRow($cell)
	{
		if($this->hasColumns()){
			$this->_rows->addCell($cell);
			
			if($this->countCellsPerRow() == $this->countColumns()){
				$this->_rows->addRow();
			}
			
			return $this;
		}
		throw new Svs_Json_Exception(
			'Before adding rows, columns have to be provided');
	}
	
	/**
	 * returns all rows
	 * 
	 * @return Svs_Json_Rows 
	 */
	public function getRows()
	{
		return $this->_rows;
	}
	
	/**
	 * counts the rows of the table
	 * 
	 * @return	int
	 */
	public function countRows()
	{
		return $this->_rows->countRows();
	}
	
	/**
	 * counts the cells in a row
	 * 
	 * @return 	int
	 */
	public function countCellsPerRow()
	{
		return $this->_rows->countCells();
	}
	
			
	public function toArray()
	{
		return array_merge(
			array('cols' => $this->_columns),
			array('rows' => $this->_rows->getRows())
		);
	}
		
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * the init hook
	 */
	protected function _init()
	{
		$this->_columns = array();
		$this->_rows 	= new Svs_Json_GData_Rows();
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
