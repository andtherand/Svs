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
	protected $_defaultRedirectRoute =  'listService';
	
	/**
	 * @var string the message to be shown after an update or create action
	 */
	protected $_successMessage = 'Ihre Aktion war erfolgreich.';
	
	/**
	 * @var string the message to be shown when something was deleted 
	 */
	protected $_deleteMessage = 'Erfolgreich gelöscht.';
	
	/**
	 * @var Zend_Controller_Action_Helper_FlashMessenger
	 */
	private $_flashMessenger = null;
	
	/**
	 * @var Zend_Session_Namespace
	 */
	private $_namespace;
	
	/**
	 * @var Zend_Controller_Action_Helper_Redirector
	 */
	private $_redirector;
	
	
	//-------------------------------------------------------------------------
	// - PUBLIC
	
	/**
	 * the init hook
	 */
	public function init()
	{
	  	$this->_service->setRequest($this->getRequest());
		
		$this->_flashMessenger 	= $this->getHelper('FlashMessenger');
		$this->_namespace 		= new Zend_Session_Namespace('crud');
		$this->_redirector 		= $this->getHelper('redirector');
	}
	
	/**
	 * just a redirect to the list action
	 */
	public function indexAction()
	{
		$this->_redirector->gotoRoute(array(), $this->_defaultRedirectRoute);	
	}
	
	/**
     * lists all services found
     */
    public function listAction()
    {
    	$message = $this->_flashMessenger->getMessages();
		if(!empty($message)){
			$this->view->placeholder('flashMessenger')->set(
					sprintf(
						'<div class="success grid_4">%s</div>', $message[0]
				)
			);
			$this->_flashMessenger->clearMessages();
			// disables the back button for 1 hop
			$this->_namespace->noBackButton = true;
			$this->_namespace->setExpirationHops(1); 
		}
		
    	$this->_helper->paginator($this->_service->findAll(), 10);
    }
	
	/**
     * shows the details of a specific service
     */
    public function showAction()
    {
    	try {
    		$this->view->entity = $this->_service->findById();
			
    	} catch(Svs_Model_Exception $e){
    		throw $e;
    	}
    }
	
	/**
     * render the form to create a new entity
     */
    public function newAction()
    {
    	// check if the backButton is diabled if so redirect	
		if(isset($this->_namespace->noBackButton)){
			$this->_redirector->gotoRouteAndExit(
				array(), $this->_defaultRedirectRoute
			);
		}
		
    	$this->view->form =	$this->_service->getPopulatedForm(false);
        $this->render('form');
    }
	
	 /**
     * renders the edit form with the populated data
     */
    public function editAction()
    {
    	$this->view->form =	$this->_service->getPopulatedForm();
		$this->render('form');
    }
	
	/**
     * acts as a gateway:
     * if request is not posted redirect to the list action
     * other wise validate data and either find errors and redirect back 
     * or let the request pass
     */
    public function processAction()
    {
    	$this->getHelper('viewRenderer')->setNoRender();
		
    	// if request is get redirect to the list action
    	if(!$this->getRequest()->isPost()){
    		$this->_redirector->gotoRoute(
    			array(), $this->_defaultRedirectRoute
			);
    	}
		
		$result = $this->_service->save($this->getRequest()->getPost());
		
		// somethings gone wrong, so show the form again
		if($result instanceof Zend_Form){
			$this->view->form = $result;
			$this->render('form');
			
		} else {
			$this->_flashMessenger->addMessage($this->_successMessage);
			$this->_redirector->gotoRouteAndExit(
				array(), $this->_defaultRedirectRoute
			);
			
		} 	
    }
	
	/**
	 * deletes an entity 
	 */
    public function deleteAction()
    {
    	try {
    		$id = $this->_service->delete();
			
    	} catch(Svs_Model_Exception $e){
    		throw $e;
    	}
		
		$this->_flashMessenger->addMessage($this->_deleteMessage);
       	$this->_redirector->gotoRouteAndExit(
       		array(), $this->_defaultRedirectRoute
		);
    }
	
	//-------------------------------------------------------------------------
	// - PROTECTED
	
	//-------------------------------------------------------------------------
	// - PRIVATE
	
}
