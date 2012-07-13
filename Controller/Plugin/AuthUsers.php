<?php

class Svs_Controller_Plugin_AuthUsers
    extends Zend_Controller_Plugin_Abstract
{
    //--------------------------------------------------------------------------
    // - VARS

    private $_tableName = 'users';

    private $_mapperInfo = array();

    private $_auth = null;

    private $_acl = null;

    private $_route = array();


    //--------------------------------------------------------------------------
    // - CONSTRUCTOR

    public function __construct(array $config)
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = $config['acl'];

        $this->setUserTable($config['tableName']);
        $this->_mapperInfo = $config['mapper'];
        $this->_route = $config['route'];
    }

    //--------------------------------------------------------------------------
    // - METHODS

    public function setUserTable($name)
    {
        $this->_tableName = $name;

        return $this;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
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
        $request = $this->getRequest();

        if ('login' === $request->getActionName()) {

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

            $redirector =
                Zend_Controller_Action_HelperBroker::getStaticHelper(
                    'redirector'
                );

            $redirector->gotoSimple($action, $controller, $module, $params);
        }

        $role = $this->_auth->getIdentity()->getRole();
        $resource = $request->getModuleName();

        if (!$this->_acl->has($resource)) {
            $resource = null;
        }

        if (!$this->_acl->isAllowed($role, $resource)) {
            $response = $this->getResponse();
            $response->setHttpResponseCode(403);
            $response->setException(new Svs_Auth_Exception('Forbidden'));
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

            $redirector =
                Zend_Controller_Action_HelperBroker::getStaticHelper(
                    'redirector'
                );

            $redirector->setGotoRoute(array(), 'login');
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
}
