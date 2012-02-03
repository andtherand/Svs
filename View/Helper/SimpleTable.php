<?php

class Svs_View_Helper_SimpleTable extends Zend_View_Helper_HtmlElement
{
	//-------------------------------------------------------------------------
	// - VARS
	
	private $_th    = null;
  private $_tbody = null;
  private $_id    = null;
	
	//-------------------------------------------------------------------------
	// - PUBLIC 
	
	/**
	 * @param string $id
	 * @param array $data
	 * @param [array $options] optional.
	 */
	public function simpleTable($id, Countable $data = null, array $options = array())
	{
	  if($data === null){
	    $this->_id = $id;
	    return $this;
	  }
    
		$th = null;
		if(array_key_exists('th', $options)){
			$th = $options['th'];
			unset($options['th']);
		}
		
		$html = array();
		$html[] = '<table id="' . $id . '"' . $this->_htmlAttribs($options) . '>';
		
		if(null !== $th){
			$html[] = $this->setTableHead($th);
		}
		
		$html[] = $this->setBody($data);
		$html[] = '</table>';
		
		return implode("\n", $html);
	}
  
  /**
   * @param mixed string|array 
   * @return string
   */
  public function setTableHead($th)
  {
    $this->_th = $this->_setRow($th, 'thead');
    return $this->_th;
  }
  
  /**
   * @param mixed array|string $data
   * @return string
   */
  public function setBody($data)
  {
    $this->_tbody = $this->_setRow($data, 'tbody');
    return $this->_tbody;
  }
  
  public function render(array $options = array())
  {
    $html   = array();
    $html[] = '<table id="' . $id . '"' . $this->_htmlAttribs($options) . '>';
    $html[] = $this->_th;
    $html[] = $this->_tbody;
    $html[] = '</table>';
    return implode("\n", $html);    
  }
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
	 * @param mixed string|array $data
	 * @param string $type tbody or thead
	 * @return string 
	 */
	private function _setRow($data, $type = 'tbody')
	{
		$columnTag = $type === 'tbody' ? 'td' : 'th';
		
		$html = array();
		$html[] = sprintf('<%s>', $type);
				
		if(!is_string($data)){						
			foreach($data as $row){			
				$html[] = sprintf(
					'<tr class="%s"><%s>%s</%s></tr>', 
					$this->view->cycle(array('odd', 'even'))->next(), 
					$columnTag, $row, $columnTag
				);  
			}
				
		} else {
			$html[] = sprintf('<tr><%s>%s</%s></tr>', $columnTag, $data, $columnTag);
		}
		
		$html[] = sprintf('</%s>', $type);
		return implode("\n", $html);
	}
}