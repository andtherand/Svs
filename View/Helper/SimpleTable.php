<?php

class Svs_View_Helper_SimpleTable extends Zend_View_Helper_HtmlElement
{
	//-------------------------------------------------------------------------
	// - PUBLIC 
	
	/**
	 * @param string $id
	 * @param array $data
	 * @param [array $options] optional.
	 */
	public function simpleTable($id, Countable $data, array $options = array())
	{
		$th = null;
		if(array_key_exists('th', $options)){
			$th = $options['th'];
			unset($options['th']);
		}
		
		$html = array();
		$html[] = '<table id="' . $id . '"' . $this->_htmlAttribs($options) . '>';
		
		if(null !== $th){
			$html[] = $this->_setTableHead($th);
		}
		
		$html[] = $this->_setBody($data);
		$html[] = '</table>';
		
		return implode("\n", $html);
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
	 * @param mixed string|array 
	 * @return string
	 */
	private function _setTableHead($th)
	{
		return $this->_setRow($th, 'thead');
	}
	
	/**
	 * @param mixed array|string $data
	 * @return string
	 */
	private function _setBody($data)
	{
		return $this->_setRow($data, 'tbody');
	}
	
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