<?php

class Svs_Controller_Action_Helper_Navigation
	extends Zend_Controller_Action_Helper_Abstract
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
	 * @var array
	 */
	private $_data = array(
		'container'       => null,
		'isDefaultModule' => false,
		'suffix'          => '-navigation',
		'extension'       => 'xml',
		'class'           => 'navi-btns',
		'folder'          => 'configs',
	);
	
	/**
	 * @var array
	 */
	private $_params = null;
	
	//-------------------------------------------------------------------------
	// - PUBLIC
		
	/**
	 * adds params to the params array
	 * provides a fluid interface
	 * 
	 * @param 	array $params
	 * @return 	Svs_Controller_Action_Helper_Navigation
	 */
	public function addParams(array $params = array())
	{
		$this->_params = $params;
		return $this;
	}
	
	/**
	 * sets the navigation file which to use
	 * provides a fluid interface
	 *  
	 * @param 	string $file
	 * @param 	string $segment
	 * @return 	Svs_Controller_Action_Helper_Navigation
	 */
	public function addNavigation($file = null, $segment = 'nav')
	{
		$actionName = $file;
			
		// retrieve action name for the navigation.xml
		if(null === $actionName || empty($actionName)){
			$actionName = $this->getActionController()
							->getRequest()
							->getActionName();
		}
		
		$pathPrefix = $this->isDefaultModule()
			? APPLICATION_PATH 
			: $this->getFrontController()->getModuleDirectory();
				
		$path =  $pathPrefix
				. sprintf('/%s/', $this->get('folder')) 
				. $actionName 
				. $this->get('suffix')
				. '.'
				. $this->get('extension'); 
		
		if(file_exists($path)){
			$this->container = new Zend_Navigation(
					new Zend_Config_Xml($path, $segment)
			);
		}
		
		if(!empty($this->_params)){
			$this->_setURIExtras();
		}
		
		return $this;		
	}
	
	/**
	 * renders the navigation container in the view
	 */
	public function render()
	{
		$view = $this->getActionController()->view; 
		$view->navigation()
		  	 ->menu($this->container)
			 ->setUlClass($this->class)->render();
	}
	
	/**
	 * sets options
	 * @param array $options
	 * @return Svs_Controller_Action_Helper_Navigation
	 */
	public function setOptions(array $options) 
	{
		foreach($options as $key => $value){
			if(array_key_exists($key, $this->_data)){
				$this->$key = $value;
			}
		}
		return $this;
	}
	
	/**
	 * checks if the current module is the default module
	 * 
	 * @return bool
	 */
	public function isDefaultModule()
	{
		'default' === $this->getActionController()->getRequest()->getModuleName()
					? $this->isDefaultModule = true : false; 
		return $this->isDefaultModule;
	}
	
	/**
	 * magic method set 
	 *
	 * @param string $field
	 * @param mixed $value
	 * @return Svs_Controller_Action_Helper_Navigation
	 */
	public function __set($field, $value) 
	{
		if(array_key_exists($field, $this->_data)){
			$this->_data[$field] = $value;
			return $this;	
		}
		return $this;
	}
	
	/**
	 * magic get method
	 * 
	 * @param string $field
	 * @return mixed
	 */
	public function __get($field)
	{
		return $this->__isset($field) 
			? $this->_data[$field] 
			: null;	
	}
	
	/**
	 * alias for __get
	 * 
	 * @return mixed 
	 */
	public function get($field){
		return $this->$field;
	}
	
	/**
	 * magic method
	 * 
	 * @param string $field
	 * @return bool $flag
	 */
	public function __isset($field)
	{
		return isset($this->_data[$field]);
	}
	
	/**
	 * Stategy pattern: proxy to the main helper functions 
	 * 
	 * @param [string $file] the navigation file to load
	 * @param [array $options] sets the options
	 */
	public function direct($file = null, array $options = array())
	{
		if(is_array($file)){
			$options = $file;
			$file = null;
		}
		$this->_initialize($file, $options)
			 ->render();
	}
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
	 * inits the helper
	 * 
	 * @param 	string $file
	 * @param 	array $options
	 * @return 	Svs_Controller_Action_Helper_Navigation
	 */
	private function _initialize($file, array $options = array()) 
	{
		if(!empty($options)){
			$this->setOptions($options);
			
			if(array_key_exists('params', $options)){
				$this->addParams($options['params']);
			}
		}
		return $this->addNavigation($file);
	}
	
	/**
	 * sets extra uri params
	 * 
	 * @return void
	 */
	private function _setURIExtras()
	{
		$pages = $this->get('container')->getPages();
		foreach($pages as $container => $page){
			$tmpParams = $page->getParams();
			$tmpParams += $this->_params;
			$page->setParams($tmpParams);
		}
	}
	
	
}