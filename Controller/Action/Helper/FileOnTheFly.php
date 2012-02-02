<?php
    
class Svs_Controller_Action_Helper_FileOnTheFly 
    extends Zend_Controller_Action_Helper_Abstract 
{
	//-------------------------------------------------------------------------
	// - VARS
	
	/**
     * @var string
     */
	private $_file = null;
    
    /**
     * @var string
     */
    private $_path = '/../data/cache/files/';
    
    /**
     * @var string
     */
    private $_contentHash = null;
    
    /**
     * @var string
     */
    private $_content = null; 
    
    /**
     * @var string
     */
    private $_extension = null;
    
    /**
     * @var string
     */
    private $_fileName = null;
    	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
     * sets the content that should be saved and generates the 
     * content hash
     * provides a fluid interface
     * 
     * @param   mixed $content The content to put in the file
     * @return  Svs_Controller_Action_Helper_FileOnTheFly
     */
	public function setContent($content)
    {
        if(is_string($content)){
            $this->_contentHash = sha1($content);
            $this->_content     = $content;
        }
        return $this;
    }
    
    /**
     * gets the sha1 hash of the contents to be put in a file
     * 
     * @return string
     */
    public function getHash()
    {
        return $this->_contentHash;   
    }
    
    /**
     * saves a file if it does not already exist
     * provides a fluid interface
     * 
     * @return  Svs_Controller_Action_Helper_FileOnTheFly 
     */
    public function save()
    {
        $file = $this->_file;
        if(!$this->_fileExists($file)){
            file_put_contents($file, $this->_content);
            chmod($file, 0644);
        }
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function deliverFile($fileName = null, $forceDownload = false)
    {
        $file = $this->_content;
        if(null !== $fileName){
            $this->_file = APPLICATION_PATH . $this->_path . $fileName;
        }
        if($this->_fileExists($file)){
            $file = file_get_contents($this->_file);
        }
        
        //$fileName = $this->_fileName . $this->_extension; 
        if($forceDownload){
            $this->getResponse()
                ->setBody($file)
                ->setHeader('Content-Type', 'application/octet-stream')
                ->setHeader('Content-Disposition', 
                    'attachment; filename=' . $fileName)
                ->setHeader('Content-Length', strlen($file));
        }
        return $this;
    }    	
	
    /**
     * sets the locatoin a file will be saved
     * provides a fluid interface
     * 
     * @param   [string $path Optional. the path]  
     * @return  Svs_Controller_Action_Helper_FileOnTheFly
     */
    public function setFilePath($path = null)
    {
        $path = null !== $path ? $path : $this->_path;
        $name = null !== $this->_fileName ? $this->_fileName : $this->_contentHash;  
        $this->_file = APPLICATION_PATH . $path . $name;
        
        if(null !== $this->_extension){
            $this->_file .= $this->_extension;
        }
        
        return $this;
    }
    
    /**
     * sets the name of the file that´s going to be delivered
     * provides a fluid interface
     * 
     * @param   string $name the files future name
     * @return  Svs_Controller_Action_Helper_FileOnTheFly
     */
    public function setFileName($name)
    {
        $this->_fileName = $name;
        return $this;
    }
    
    /**
     * sets the file extension
     * provides a fluid interface
     * 
     * @param   string $ext the desired extension
     * @return  Svs_Controller_Action_Helper_FileOnTheFly 
     */
    public function setExtension($ext)
    {
        $this->_extension = '.' . $ext;
        return $this;
    }
    
    /**
     * strategy pattern to directly call this action helper
     * provides a fluid interface
     * 
     * @param string $content the content to be saved
     * @param string
     * 
     * @return  Svs_Controller_Action_Helper_FileOnTheFly
     */
	public function direct(
	   $content = null, $filetype = null, $path = null, $forceDownload = false
    )
    {
        if(null === $content){
            return $this;
        }
        
        $this->setContent($content)
             ->setExtension($filetype)
             ->setFilePath($path)
             ->save();   

        return $this;
    }
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
	/**
     * sees if a file exists or not
     * 
     * @param   [string $file Optional. if left empty defaults to the _file field]
     * @return  bool   
     */
	private function _fileExists($file = null)
    {
        $path = null !== $file ? $file : $this->_file;
               
        if(file_exists($path)){
            return true;
        }
        return false;
    }
	
}
