<?php

class Svs_View_Helper_IsAllowed extends Zend_View_Helper_Abstract
{
     public function isAllowed($resource, $privilege = null)
     {
         $acl  = Zend_Registry::get('acl');
         $role = Zend_Auth::getInstance()->getIdentity()->getRole();

         return $acl->isAllowed($role, $resource, $privilege);
     }
}