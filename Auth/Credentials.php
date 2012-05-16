<?php

class Svs_Auth_Credentials
{

    private $_field = 'name';
    private $_name = '';
    private $_credentials = '';

    public function __construct($name, $field, $pass)
    {
        $this->_field = $field;
        $this->_name = $name;
        $this->pass = $pass;
    }

    public function getFieldName()
    {
        return $this->_field;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getPassword()
    {
        return $this->_credentials;
    }

}