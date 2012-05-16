<?php

class Svs_Controller_Action_Helper_AuthUsers
    extends Zend_Controller_Action_Helper_Abstract
{

    //--------------------------------------------------------------------------
    // - METHODS

    public function preDispatch()
    {
        $request = $this->getRequest();
        $username = $request->getParam('username');

        $actionController = $this->getActionController();

        if ($request->isPost() && $username) {
            $password = $request->getParam('password');

            if ($this->authenticateUser($username, $password)) {

                            ;

            } else {
                $actionController->view->loginformerror = true;
            }
        }

        $auth = Zend_Auth::getInstance();
        $actionName = $request->getActionName();

        if (!$auth->hasIdentity() && 'login' !== $actionName) {
            $actionController->getHelper('Redirector')->setGotoRoute(array(), 'auth');
        }


       /* $this->getActionController()->view->isLoggedInUser =
            $this->isLoggedInUser();*/
    }

    /**
     * Returns true if user is authenticated as user
     * @return bool
     */
    public function isLoggedInUser()
    {
        $session = new Zend_Session_Namespace('auth');
        return isset($session->user)
            && $session->user instanceof Svs_Model_EntityAbstract;
    }

    /**
     *
     * @param string $username - username or email
     * @param string $password - password
     * @return bool
     */
    public function authenticateUser($username, $password)
    {
        $auth = Zend_Auth::getInstance();
        $db = Zend_Db_Table::getDefaultAdapter();

        $idColumn = 'email';

        if (false === strpos($username, '@')) {
            $idColumn = 'name';
        }

        $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users', $idColumn, 'password');
        $authAdapter->setIdentity($username)
            ->setCredential(hash('sha256', '23ns' . $password . 'cx89a'));

        $authResult = $auth->authenticate($authAdapter);

        if ($authResult->isValid()) {

            //valid username and password
            $userInfo = $authAdapter->getResultRowObject();
            var_dump($userInfo);

            //save userinfo in session
            $session = new Zend_Session_Namespace('auth');
            //$session->user = $user;

            return true;
        }

        return false;

    }

    public function logout()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $session = new Zend_Session_Namespace('auth');
        $session->user = null;

        Zend_Session::forgetMe();
    }
}
