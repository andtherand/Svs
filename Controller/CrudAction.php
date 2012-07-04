<?php

class Svs_Controller_CrudAction extends Zend_Controller_Action
{
	//-------------------------------------------------------------------------
	// - VARS

	 /**
     * @var Svs_Service_Abstract
     */
    protected $_service = null;

	/**
	 * @var string the default route to use for redirects
	 */
	protected $_defaultRedirectRoute =  'list';

	/**
	 * @var string the message to be shown after an update or create action
	 */
	protected $_successMessage = 'Ihre Aktion war erfolgreich.';

	/**
	 * @var string the message to be shown when something was deleted
	 */
	protected $_deleteMessage = 'Erfolgreich gelöscht.';

	/**
	 * @var string the folder to llok for all the view scripts that are shared
	 */
	protected $_viewFolder = 'crud';

	/**
	 * @var string the name of the controller
	 */
	protected $_controller = null;

	/**
	 * @var string the name of the current module
	 */
	protected $_module = null;

	/**
	 * @var Zend_Controller_Action_Helper_Redirector
	 */
	protected $_redirector = null;

    protected $_messenger = null;

	/**
	 * @var Zend_Session_Namespace
	 */
	protected $_namespace = null;

	/**
	 * @var Zend_Controller_Action_Helper_ViewRenderer
	 */
	protected $_viewRenderer = null;

    protected $_entriesPerPage = 25;

	//-------------------------------------------------------------------------
	// - PUBLIC

	/**
	 * the init hook
	 * inits serveral helper and variables in the controller
	 */
	public function init()
	{
		$request = $this->getRequest();
		$this->_controller = strtolower($request->getControllerName());
		$this->_module = strtolower($request->getModuleName());

		$this->_service->setURLChunks(
			$this->_module, $this->_controller
		);

		$this->_namespace = new Zend_Session_Namespace('crud');
		$this->_redirector = $this->getHelper('redirector');
		$this->_viewRenderer = $this->getHelper('viewRenderer');
    }

    /**
     * just a redirect to the list action
     */
    public function indexAction()
    {
        $this->_redirectToDefault();
    }

    /**
     * lists all services found
     */
    public function listAction()
    {
        $this->_messenger = $this->getHelper('MessengerPigeon');

		if ($this->_messenger->broadcast()) {
			// disables the back button for 1 hop
			$this->_namespace->noBackButton = true;
			$this->_namespace->setExpirationHops(1);
		}

		$this->view->assign(array(
				'partialName' => sprintf(
					'partials/%s-list.phtml', $this->_controller),
				'controller' => $this->_controller
			)
		);

		/**
		 * @var Svs_Controller_Action_Helper_Paginator
		 */
		$this->_helper->paginator($this->_service->findAll(), $this->_entriesPerPage);

		$this->_viewRenderer->render($this->_viewFolder . '/list', null, true);
    }

	/**
     * shows the details of a specific service
     */
    public function showAction()
    {
    	try {
    		$requestId = $this->getRequest()->getParam('id');
    		$this->view->entity = $this->_service->findById($requestId);

    	} catch(Svs_Service_Exception $e){
    		throw $e;
    	}
		$this->view->partialName = sprintf(
			'partials/%s-show.phtml', $this->_controller
		);
		$this->_viewRenderer->render($this->_viewFolder . '/show', null, true);
    }

	/**
     * render the form to create a new entity
     */
    public function newAction()
    {
    	$this->_helper->noCacheHeader();

    	// check if the backButton is diabled if so redirect
		if(isset($this->_namespace->noBackButton)){
			$this->_redirectToDefault();
		}
    	$this->view->form =	$this->_service->getPopulatedForm(null, false);
       	$this->_viewRenderer->render($this->_viewFolder . '/form', null, true);
    }

	 /**
     * renders the edit form with the populated data
     */
    public function editAction()
    {
    	$requestId = $this->getRequest()->getParam('id');
    	$this->view->form =	$this->_service->getPopulatedForm($requestId);
		$this->_viewRenderer->render($this->_viewFolder . '/form', null, true);
    }

	/**
     * acts as a gateway:
     * if request is not posted redirect to the list action
     * other wise validate data and either find errors and redirect back
     * or let the request pass
     */
    public function processAction()
    {
        $this->_viewRenderer->setNoRender();
        // if request is get redirect to the list action
        if(!$this->getRequest()->isPost()){
            $this->_redirectToDefault();
        }

        $result = $this->_service->save($this->getRequest()->getPost());

        // something has gone wrong, so show the form again
        if($result instanceof Zend_Form){
            $this->view->form = $result;
            $this->_viewRenderer->render($this->_viewFolder . '/form', null, true);

        } else {
            $this->_messenger = $this->getHelper('MessengerPigeon');
			$this->_messenger->addMessage($this->_successMessage);
			$this->_redirectToDefault();

		}
    }

	/**
	 * deletes an entity
	 */
    public function deleteAction()
    {
    	$requestId = $this->getRequest()->getParam('id');
    	try {
    		$id = $this->_service->delete($requestId);

    	} catch(Svs_Model_Exception $e){
    		throw $e;
    	}

		$this->_messenger->addMessage($this->_deleteMessage);
       	$this->_redirectToDefault();
    }

	//-------------------------------------------------------------------------
	// - PROTECTED

	//-------------------------------------------------------------------------
	// - PRIVATE

    private function _redirectToDefault()
    {
        $this->_redirector->gotoRouteAndExit(
            array(
                'module' => $this->_module,
                'controller' => $this->_controller,
            ),
            $this->_defaultRedirectRoute
        );
    }
}
