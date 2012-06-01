<?php

class Svs_View_Helper_JsHandlebarTemplate extends Zend_View_Helper_Placeholder_Container_Standalone
{
    //------------------------------------------------------------------------
    // - VARS

    const HANDLEBARS_EXT = 'handlebars';

    protected $_regKey = 'Svs_View_Helper_JsHandlebarTemplate';


    private $_cache = null;

    private $_section = null;

    private $_templateDirname = 'templates';

    //------------------------------------------------------------------------
    // - METHODS

    public function __construct()
    {
        parent::__construct();
        $this->setSeparator(PHP_EOL);
    }


    public function jsHandlebarTemplate($config = array())
    {
       $this->_init($config);
       return $this;
    }

    public function setConfig($config)
    {
        $this->_init($config);
        return $this;
    }


    public function addDirectory($dir)
    {
         try {
            $template = $this->_getCachedTemplates($dir);

        } catch (Zend_View_Exception $e) {
            // no templates in cache go on
             $template = $this->renderScriptTags($dir);
        }

        $this->getContainer()->prepend($template);
        return $this;
    }

    private function _init($config)
    {
        if (!empty($config)) {
            $this->_cache = isset($config['cache']) ? $config['cache'] : null;
            $this->_section = isset($config['section']) ? $config['section'] : null;

            if (isset($config['templateDir'])) {
                $this->_templateDirname =  $config['templateDir'];
            }
        }
    }

    private function _getCachedTemplates($dir)
    {
        if (null !== $this->_cache && null !== $this->_section) {
            $cacheId = Svs_Utils_String::generateID($section, 'jstemplates_');

            if (!($template = $this->_cache->load($cacheId))) {
                // make template!
                $template = $this->renderScriptTags($dir);
            }

            $this->_cache->save($template, $cacheId, array('infrastructure'));

            return $template;
        }

        throw new Zend_View_Exception('no cached templates found in cache');
    }

    public function renderScriptTags($dir)
    {
        $scripts = $this->_parseFiles($dir);

        return $this->_renderScriptTags($scripts);
    }

    private function _parseFiles($dir)
    {
        $scripts = array();
        $iterator = new DirectoryIterator($dir . $this->_templateDirname);

        foreach ($iterator as $fileInfo) {

            if ($fileInfo->isFile()) {

                $scripts[$fileInfo->getBasename('.' . self::HANDLEBARS_EXT)] =
                    file_get_contents($fileInfo->getPathname());
            }
        }

        return $scripts;
    }

    private function _renderScriptTags($scripts)
    {
        $templateString = '';
        foreach ($scripts as $scriptName => $script) {
            $templateString .= sprintf(
                '<script id="%s" type="text/x-handlebars-template">%s</script>',
                $scriptName,
                $script
            );
        }
        return $templateString;
    }
}