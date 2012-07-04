<?php

class Svs_Controller_Action_Helper_MessengerPigeon
    extends Zend_Controller_Action_Helper_Abstract
{
    private $_fm = null;
    private $_view = null;
    private $_messageType = 'success';


    public function direct()
    {
      $this->_initMessenger();

        return $this;
    }

    private function _initMessenger()
    {
        $action = $this->getActionController();
        var_dump($this->_actionController);
        // $this->_fm = $action->getHelper('FlashMessenger');
        // $this->_view = $action->view;
    }

    /**
     * [broadcast description]
     * @return bool
     */
    public function broadcast()
    {
        $messages = $this->_fm->getMessages();
        $broadcast = false;

        if (!empty($messages)) {
            $this->_view->placeholder('flashMessenger')->set(
                    sprintf(
                        '<div class="svs-messages messages %s">%s</div>',
                        $this->_messageType,
                        $messages[0]
                )
            );
            $this->_fm->clearMessages();
            $broadcast = true;
        }
        return $broadcast;
    }

    public function addMessage($message)
    {
        $this->_fm->addMessage($message);
        return $this;
    }

}