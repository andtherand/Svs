<?php

class Svs_View_Helper_AuthUser extends Zend_View_Helper_Abstract
{

    private $_auth = null;

    public function authUser($sessionName = 'auth')
    {
        $session = new Zend_Session_Namespace($sessionName);
        $user = $session->user;

        if ($user instanceof Svs_Model_UserInterface) {
            $items = array(
                'name' => sprintf('<span>%s</span>', $user->getName()),
                'logout' => sprintf(
                    '<a href="%s">Logout</a>',
                    $this->view->url(
                        array(
                            'module' => 'default',
                            'controller' => 'auth',
                            'action' => 'logout'
                        ),
                        'logout'
                    )
                ),
            );
            return $this->view->htmlList($items, false, array('class' => 'svs-user'), false);
        }

    }

}
