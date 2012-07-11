<?php

interface Svs_Cache_CacheableInterface {

    public function setCache(Zend_Cache_Core $cache);

    public function hasCache();

    public function setCacheTags(array $tags = array());

}