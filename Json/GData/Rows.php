<?php
    
class Svs_Json_GData_Rows
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var	array
	 */
	private $_cells  = null;
	
	/**
	 * @var array
	 */
	private $_rows 	 = null;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * instantiates a new row
	 * 
	 * @param	[array|string $value Optional. the value of the cell or an array to be used for the entire cell
	 * @param	[string $format Optional. a format string for numbers]
	 * @param	[array $param Optional. an array of misc types]
	 */
	public function __construct($value = null, $format = null, $param = null)
	{
		$this->_init();
		
		if(is_array($value) || null !== $value){
			$this->addCell($value, $format, $param);
		}
	}
	
	/**
	 * adds a cell to a row
	 * provides a fluid interface
	 * 
	 * @param	string $value the value of the cell
	 * @param	[string $format Optional. a number format]
	 * @param	[string $param Optional. any given string expression]
	 * @return 	Svs_Json_GData_Rows 
	 */
	public function addCell($value, $format = null, $param = null)
	{
		if(is_array($value) && array_key_exists('v', $value)){
			$tmp 	= $value;
			$value 	= $tmp['v'];
			$format = isset($tmp['f']) ? $tmp['f'] : null; 
			$param  = isset($tmp['p']) ? $tmp['p'] : null; 
			unset($tmp);
		}
		
		$tmpCell = array('v' => $value);
		
		if(null !== $format){
			$tmpCell['f'] = $format;
		}
		
		if(null !== $param){
			$tmpCell['p'] = $param;
		}

		$this->_cells[] = $tmpCell;
		
		return $this;
	}
	
	/**
	 * adds a bulk of cells to the row
	 * 
	 * @param	array $cells an array of arrays to set the cells at once
	 * @return	Svs_Json_GData_Rows
	 */
	public function addCells(array $cells)
	{
		foreach($cells as $wrapper => $cell){
			$this->addCell($cell);
		}
		return $this;
	}
	
	
	/**
	 * returns all the cells of a row
	 * 
	 * @return 	array 
	 */
	public function getCells()
	{
		return $this->_cells;
	}
	
	/**
	 * counts the cells of a row
	 * 
	 * @return 	int
	 */
	public function countCells()
	{
		return count($this->_cells);
	}
	
	/**
	 * counts the rows
	 * 
	 * @return 	int
	 */
	public function countRows()
	{
		return count($this->_rows);
	}
	
	/**
	 * adds a row and resets the cells
	 * provides a fluid interface
	 * 
	 * @return 	Svs_Json_GData_Rows 
	 */
	public function addRow()
	{
		$this->_rows[] = json_encode($this->_cells);
		$this->_cells = array();
		
		return $this;
	}
	
	/**
	 * returns the rows
	 * 
	 * @return 	array
	 */
	public function getRows()
	{
		return $this->_rows;
	}
	
	/**
	 * transforms the row to a valid json string
	 * 
	 * @return 	string
	 */
	public function __toString()
	{
		return '{"c":' . implode('},{"c":', $this->_rows) . '}';
	}
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	/**
	 * the init hook
	 */
	protected function _init()
	{
		$this->_cells = array();
		$this->_rows = array();
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}


	
