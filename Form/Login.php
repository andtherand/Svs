<?php

class Svs_Form_Login extends Svs_Form
{

    //-------------------------------------------------------------------------
    // - VARS

    /**
     * @var string the id of the form to use
     */
    protected $_id = 'login-form';

    /**
     * @var string the button label
     */
    protected $_submitLabel = 'Login';

    /**
     * @var array
     */
    protected $_submitAttribs = array('class' => 'action-btn call-to-action');

    //--------------------------------------------------------------------------
    // - METHODS

    protected function _pushName()
    {
        $elem = new Zend_Form_Element_Text('username');
        $elem->setRequired()
            ->setLabel('Username/E-Mail')
            ->setAttribs(array(
                    'autocomplete' => 'off',
                )
            )
            ->addValidators(array(
                    array('notEmpty', true),
                )
            )
            ->addFilters(array(
                'stringTrim'
            )
        );

        return $elem;
    }

    protected function _pushPassword()
    {
        $elem = new Zend_Form_Element_Password('password');
        $elem->setLabel('Passwort')
            ->addFilters(array(
                    'stringTrim'
                )
            )
            ->addValidators(array(
                    array('notEmpty', true),
                )
            )
            ->setRequired();

        return $elem;
    }

}