<?php

class Svs_Controller_Action_Helper_AuthUsers
    extends Zend_Controller_Action_Helper_Abstract
{

    private $_tableName = 'users';
    private $_mapperInfo = array();

    //--------------------------------------------------------------------------
    // - METHODS

    public function __construct($tableName = null, array $mapperInfo = array())
    {
        if (null !== $tableName) {
            $this->setUserTable($tableName);
        }

        if (!empty($mapperInfo)) {
            $this->_mapperInfo = $mapperInfo;
        }
    }

    public function setUserTable($name) {
        $this->_tableName = $name;

        return $this;
    }

    public function preDispatch()
    {
        $actionController = $this->getActionController();
        $request = $this->getRequest();

        $username = $request->getParam('username');
        $isPost = $request->isPost();

        if ($isPost && $username) {
            $password = $request->getParam('password');

            if ($this->authenticateUser($username, $password)) {

                            ;

            } else {
                $actionController->view->loginformerror = true;
            }
        }

        $auth = Zend_Auth::getInstance();
        $actionName = $request->getActionName();

        if (!$auth->hasIdentity() && 'login' !== $actionName && !$isPost) {

            $session = new Zend_Session_Namespace('referer');
            $session->gotoPage = $request->getRequestUri();

            $actionController->getHelper('Redirector')
                ->setGotoRoute(array(), 'auth');
        }
    }

    /**
     * Returns true if user is authenticated as user
     * @return bool
     */
    public function isLoggedInUser()
    {
        $session = new Zend_Session_Namespace('auth');
        return isset($session->user)
            && $session->user instanceof Svs_Model_UserInterface;
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

        $return = false;
        $idColumn = 'email';

        if (!strpos($username, '@')) {
            $idColumn = 'name';
        }

        $authAdapter = new Svs_Auth_Adapter_Phpass(
            new Svs_Auth_Credentials($username, $idColumn, $password),
            $this->_mapperInfo
        );

        $authResult = $auth->authenticate($authAdapter);

        if ($authResult->isValid()) {

            //valid username and password
            $user = $authAdapter->getAuthenticatedUser();

            //save userinfo in session
            $session = new Zend_Session_Namespace('auth');
            $session->user = $user;

            $return = true;
        }

        return $return;
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
