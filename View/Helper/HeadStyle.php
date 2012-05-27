<?php

class Svs_View_Helper_HeadStyle extends Zend_View_Helper_HeadLink
{
    //-------------------------------------------------------------------------
    // - VARS

    protected $_baseUrl = '/';

    //-------------------------------------------------------------------------
    // - METHODS

    public function headStyle($paths = null)
    {
        $this->_retrieveBaseUrl();

        if (is_array($paths)) {
            $this->appendStylesheets($paths);

        } else if (null !== $paths) {
            $this->appendStylesheet($paths);
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
    public function appendStylesheets(array $files)
    {
        foreach ($files as $file) {
            $this->appendStylesheet($file);
        }
    }

    /**
     * appends a file and adds the baseUrl
     *
     * @param  string $path
     * @return Zend_View_Helper_HeadLink
     */
    public function appendStylesheet($path)
    {
        $file = Svs_Utils_String::addBaseUrl($path, $this->_baseUrl);

        parent::appendStylesheet($file);
        return $this;
    }




}