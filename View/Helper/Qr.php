<?php

class Svs_View_Helper_Qr extends Svs_View_Helper_Img
{
	//-------------------------------------------------------------------------
	// - VARS
	
	const CORRECTION_L = 'L';
	const CORRECTION_M = 'M';
	const CORRECTION_Q = 'Q';
	const CORRECTION_H = 'H';
		
	/**
	 * the default save path, the application_path has to be appended 
	 * 
	 * @var string 
	 */
	private $_defaultSavePath = '/../public/img/qr/';
	
	/**
	 * the absolute path where the qr code will be saved
	 * 
	 * @var string
	 */
	private $_savePath = '';
	
	/**
	 * the relative path to the save path
	 * 
	 * @var string
	 */
	private $_webDir = '/img/qr/';
	
	/**
	 * the filename without the path
	 * 
	 * @var string
	 */
	private $_filename = '';
	
	/**
	 * the default error correction level for the qr code
	 * valid option are L, M, Q, H
	 * @var string
	 */
	private $_errorCorrectionLevel = 'M';
	
	/**
	 * the default point size
	 * @var int
	 */
	private $_matrixPointSize = 2;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * creates a qr code image and returns the embeddable string
	 * if neither params are given returns the helper to setup the qr generation
	 * 
	 * @param string $input
	 * @param [array $options] optional
	 * @param [array $attribs] optional
	 * @return mixed string|Svs_View_Helper_Qr
	 * @throws Exception when phpqrcode library not in place or if no input was 
	 * 					 given
	 */
	public function qr(
		$input, array $options = array(), array $attribs = array())
	{		
		// set up qr code!
		if(empty($input) && empty($options)){
			return $this;
		}
			
		if(empty($input)){
			throw new Exception('Input must not be empty!');
		}
		
		$libPath = APPLICATION_PATH . '/../library/phpqrcode/qrlib.php';
						
		if(!file_exists($libPath)){
			throw new Exception('Dependendcy phpqrcode is missing!');
		}
		
		$this->setOptions($options);
		$this->_setSavePath()->_setFilename($input);	
		
		if($this->qrExists($input)){
			return $this->_render($options, $attribs);
		}
		
		require_once $libPath;
				
		QRcode::png(
			$input, $this->_savePath . $this->_filename, 
			$this->_errorCorrectionLevel, 
			$this->_matrixPointSize, 2
		);
			
		return $this->_render($options, $attribs);
	}
	
	/**
	 * checks whether or not a qr code already exists
	 * @param string input
	 * @return bool
	 */
	public function qrExists($input)
	{
		if(!isset($this->_savePath)){
			$this->_savePath();
		}
		
		$id = Svs_Utils_String::generateID($input);
		$filename = $this->_savePath . $id . 'png';
		return file_exists($filename);
	}
	
	/**
	 * sets the options
	 * 
	 * @param array $options 
	 * @return Svs_View_Helper_Qr
	 */
	public function setOptions($options)
	{
		foreach($options as $methodName => $value){
			$tmpMethodName = 'set' . ucfirst($methodName);
			if(method_exists($this, $tmpMethodName)){
				$this->$tmpMethodName($value);
			}
		}
		return $this;
	}
	
	/**
	 * sets the matrix size
	 * 
	 * @param int $int 
	 * @return Svs_View_Helper_Qr
	 */
	public function setMatrixPointSize($int)
	{
		$this->_matrixPointSize = $int;
		return $this;
	}
		
	/**
	 * sets the error correction level_ valid values are M, L, H, Q
	 * 
	 * @param string $level an erro correction level provided throught 
	 * 						the constants
	 * @return Svs_View_Helper_Qr
	 */
	public function setErrorCorrectionLevel($level)
	{
		$this->_errorCorrectionLevel = $level;
		return $this;
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
	 * sets the filename
	 * 
	 * @param string $input a raw string
	 * @return  
	 */
	private function _setFilename($input)
	{
		$id = Svs_Utils_String::generateID($input);
		$this->_filename = $id . '.png';
		
		return $this;
	}
	
	/**
	 * returns the embeddable img string
	 * 
	 * @param array $options
	 * @param array $attribs
	 * @return string  
	 */
	private function _render($options, $attribs)
	{
		$params = array(
			'src' => $this->view->baseUrl() 
					 . $this->_webDir 
					 . $this->_filename,
		); 
		$options += $params;
		
		return $this->img($options, $attribs);
	}
	
	/**
	 * sets the save path
	 * 
	 * @param [string $str] optional
	 * @return Svs_View_Helper_Qr
	 */
	private function _setSavePath($str = null)
	{
		if(null === $str){
			$this->_savePath = APPLICATION_PATH 
							 . $this->_defaultSavePath; 
		}
			
		return $this;
	}

}
