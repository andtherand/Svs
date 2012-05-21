<?php

class Svs_Controller_Action_Helper_AuthUsers
    extends Zend_Controller_Action_Helper_Abstract
{
    //--------------------------------------------------------------------------
    // - VARS

    private $_tableName = 'users';
    private $_mapperInfo = array();
    private $_auth = null;
    private $_route = array();

    //--------------------------------------------------------------------------
    // - CONSTRUCTOR

    public function __construct(array $config)
    {
        $this->_auth = Zend_Auth::getInstance();

        $this->setUserTable($config['tableName']);
        $this->_mapperInfo = $config['mapper'];
        $this->_route = $config['route'];
    }

    //--------------------------------------------------------------------------
    // - METHODS

    public function setUserTable($name) {
        $this->_tableName = $name;

        return $this;
    }

    public function preDispatch()
    {
        if ($this->_auth->hasIdentity()) {
            $this->_handleIdentity();

        } else {

            try {
                $this->_handleLoginAttempt();

            } catch (Svs_Auth_Exception $e) {
                // we currently don't need error handling here
            }
            $this->_handleNoIdentity();
        }
    }

    private function _handleIdentity()
    {
        if ('login' === $this->getRequest()->getActionName()) {

            $route = $this->_route;
            $action = $route['action'];
            $controller = isset($route['controller'])
                        ? $route['controller']
                        : null;
            $module = isset($route['module'])
                    ? $route['module']
                    : null;
            $params = isset($route['params'])
                    ? $route['params']
                    : array();

            $this->getActionController()->getHelper('Redirector')
                ->gotoSimple($action, $controller, $module, $params);
        }
    }

    private function _handleNoIdentity()
    {
        $request = $this->getRequest();

        if (  'login' !== $request->getActionName()
            && !$request->isPost())
        {
            $session = new Zend_Session_Namespace('referer');
            $session->gotoPage = $request->getRequestUri();
            $session->setExpirationHops(2);

            $this->getActionController()->getHelper('Redirector')
                ->setGotoRoute(array(), 'login');
        }
    }

    /**
     * @throws Svs_Auth_Exception when validation fails
     */
    private function _handleLoginAttempt()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $username = $request->getParam('username');
            $password = $request->getParam('password');

            try {
                $this->authenticateUser($username, $password);

            } catch (Svs_Auth_Exception $e) {
                throw $e;
            }
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
     * @throws Svs_Auth_Exception when validation fails
     */
    public function authenticateUser($username, $password)
    {
        $return = false;
        $idColumn = 'email';

        if (!strpos($username, '@')) {
            $idColumn = 'name';
        }

        $authAdapter = new Svs_Auth_Adapter_Phpass(
            new Svs_Auth_Credentials($username, $idColumn, $password),
            $this->_mapperInfo
        );

        $authResult = $this->_auth->authenticate($authAdapter);

        if (!$authResult->isValid()) {
            throw new Svs_Auth_Exception('Validation failed.');
        }

        $session = new Zend_Session_Namespace('auth');
        $session->user = $authAdapter->getAuthenticatedUser();
    }

    public function logout()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $session = new Zend_Session_Namespace('auth');
        $session->user = null;

        Zend_Session::forgetMe();

        $this->getActionController()->getHelper('Redirector')
            ->setGotoRoute(array(), 'login');
    }
}
