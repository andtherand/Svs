<?php

class Svs_Controller_Action_Helper_FileOnTheFly
    extends Zend_Controller_Action_Helper_Abstract
    implements Svs_Cache_CacheableInterface
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

    /**
     * @var Zend_Cache_Core
     */
    private $_cache = null;

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
        $cache = false;
        if(null !== $this->_cache){
            $cache = true;
            $this->_saveAndLoadFromCache();
        }

        $file = $this->_file;
        if(!$this->_fileExists($file) && !$cache){
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
        if($this->hasCache()) {
          $this->_fileName = $fileName;
          $file = $this->_saveAndLoadFromCache();
          $body = $file->content;
          $name = $file->name . $file->extension;

        } else if(null !== $fileName){
            $this->_file = APPLICATION_PATH . $this->_path . $fileName;
        }

        if ($this->_fileExists($this->_file)) {
            $body = file_get_contents($this->_file);
        }

        if ($forceDownload) {
            $this->getResponse()
                ->setBody($body)
                ->setHeader('Content-Type', 'application/octet-stream')
                ->setHeader('Content-Disposition',
                    'attachment; filename=' . $name)
                ->setHeader('Content-Length', strlen($body));
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

    /**
     * sets the cache to persist the files
     *
     * @param   Zend_Cache_Core $cache
     * @return  Svs_Controller_Action_Helper_FileOnTheFly
     */
    public function setCache(Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

	/**
     * checks if a cache was set
     * @return bool
     */
	public function hasCache()
    {
        return null !== $this->_cache;
    }
    //-------------------------------------------------------------------------
    // - PROTECTED

    //-------------------------------------------------------------------------
    // - PRIVATE


	/**
     * saves and loads a file from a cache
     *
     * @return  stdClass
     */
	private function _saveAndLoadFromCache()
    {
        $id = Svs_Utils_String::generateID($this->_fileName, 'fotf_');
        if(!($file = $this->_cache->load($id))){
            $obj = new stdClass();
            $obj->extension = null !== $this->_extension ? $this->_extension : '';
            $obj->content   = $this->_content;
            $obj->name      = $this->_fileName;

            $file = $obj;
            $this->_cache->setLifetime(3600 * 24 * 7);
            $this->_cache->save($file, $id, array('fotf', 'export'));
        }
        return $file;
    }

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
