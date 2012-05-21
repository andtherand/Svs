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
                'name' => $user->getName(),
                'logout' =>
                    '<a href="' . $this->view->url(
                        array(
                            'module' => 'default',
                            'controller' => 'auth',
                            'action' => 'logout'
                        ),
                        'logout'
                    ) . '">Logout</a>',
            );
            return $this->view->htmlList($items, false, array('class' => 'svs-user'), false);
        }

    }

}
