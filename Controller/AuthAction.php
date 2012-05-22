<?php

class Svs_Controller_AuthAction extends Zend_Controller_Action
{
    //-------------------------------------------------------------------------
    // - VARS

    protected $_defaultReferrer = '/';

    protected $_formAction = 'login';

    protected $_loginDeniedMessage = 'Username/E-Mail and/or Password combination is wrong!';

    //-------------------------------------------------------------------------
    // - METHODS

    public function loginAction()
    {
        $session = new Zend_Session_Namespace('referer');
        $ref = $session->gotoPage;

        $form = new Svs_Form_Login();
        $form->setAction($this->_formAction);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $isValid = $form->isValid($request->getPost());
            $hasIdentity = Zend_Auth::getInstance()->hasIdentity();

            if (!$hasIdentity) {
                $elem = $form->getElement('password');
                $elem->addError($this->_loginDeniedMessage);

            } else if ($isValid && $hasIdentity) {
                $url = null !== $ref ? $ref : $this->_defaultReferrer;

                $this->getHelper('Redirector')
                    ->gotoUrl($url);
            }
        }
        $this->view->form = $form;

    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $session = new Zend_Session_Namespace('auth');
        $session->user = null;

        Zend_Session::forgetMe();

        $this->getHelper('Redirector')->setGotoRoute(array(), 'login');
    }

}