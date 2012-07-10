<?php

class Svs_View_Helper_BottomScript extends Zend_View_Helper_HeadScript
{
    //-------------------------------------------------------------------------
    // - VARS

    protected $_baseUrl = '/';

    //-------------------------------------------------------------------------
    // - METHODS

    public function bottomScript($paths = null)
    {
        $this->_retrieveBaseUrl();

        if (is_array($paths)) {
            $this->appendFiles($paths);

        } else if (null !== $paths) {
            $this->appendFile($paths);
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
     * appends js files
     *
     * @param  array $files array of js file paths
     * @return Zend_View_Helper_HeadScript
     */
    public function appendFiles(array $files)
    {
        foreach ($files as $file) {
            $this->appendFile($file);
        }
    }

    /**
     * appends a file and adds the baseUrl
     *
     * @param  string $path
     * @return Zend_View_Helper_HeadScript
     */
    public function appendFile($path)
    {
        $file = Svs_Utils_String::addBaseUrl($path, $this->_baseUrl);

        parent::appendFile($file);
        return $this;
    }

    public function prependScript($script)
    {
        parent::prependScript($script);
        return $this;
    }
}