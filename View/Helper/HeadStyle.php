<?php

class Svs_View_Helper_HeadStyle extends Zend_View_Helper_HeadLink
{
    //-------------------------------------------------------------------------
    // - VARS

    const APPEND = 'append';
    const PREPEND = 'prepend';

    protected $_baseUrl = '/';

    //-------------------------------------------------------------------------
    // - METHODS

    public function headStyle($paths = null, $method = self::APPEND)
    {
        $this->_retrieveBaseUrl();

        if (is_array($paths)) {
            $this->addStylesheets($paths, $method);

        } else if (null !== $paths) {
            $this->addStylesheet($paths, $method);
        }

        return $this;
    }

    private function _retrieveBaseUrl()
    {
       $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

        if (null !== $baseUrl) {
            $this->_baseUrl = $baseUrl . '/';
        }
    }

    /**
     * appends css files
     *
     * @param  array $files array of css file paths
     * @return Zend_View_Helper_HeadLink
     */
    public function addStylesheets(array $files, $method = self::APPEND)
    {
        foreach ($files as $file) {
            $this->addStylesheet($file, $method);
        }
    }

    /**
     * appends a file and adds the baseUrl
     *
     * @param  string $path
     * @return Zend_View_Helper_HeadLink
     */
    public function addStylesheet($path, $method = self::APPEND)
    {
        $file = Svs_Utils_String::addBaseUrl($path, $this->_baseUrl);
        $action = strtolower($method) . 'Stylesheet';

        parent::$action($file);
        return $this;
    }




}