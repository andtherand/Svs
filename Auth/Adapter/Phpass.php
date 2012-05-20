<?php

class Svs_Auth_Adapter_Phpass implements Zend_Auth_Adapter_Interface
{
    protected $_identity = null;
    protected $_fieldName = null;
    protected $_credential = null;
    protected $_mapper = null;
    protected $_user = null;

    public function __construct(Svs_Auth_Credentials $cred, array $mapperInfo)
    {
        $this->_identity = $cred->getName();
        $this->_fieldName = $cred->getFieldName();
        $this->_credential = $cred->getPassword();

        $mapperName = $this->_normalizeMapperName($mapperInfo);
        $this->_mapper = new $mapperName();
    }

    private function _normalizeMapperName(array $mapperInfo)
    {
        return sprintf(
            '%s_Model_DataMapper_%s',
            ucfirst($mapperInfo['namespace']),
            ucfirst($mapperInfo['name'])
        );
    }

    public function authenticate()
    {
        if (!$this->_user = $this->_mapper->findBy($this->_fieldName, $this->_identity)) {
             return new Zend_Auth_Result(
                Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                $this->_identity,
                array('Invalid username')
            );
        }

        $storedPassword = $this->_user->getPassword();
        $hasher = new Svs_Auth_Hash(8, false);

        $isPassOk = $hasher->check($this->_credential, $storedPassword);

        if (!$isPassOk) {
            return new Zend_Auth_Result(
                Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                $this->_identity,
                array('Incorrect password')
            );
        }

        // Success!
        return new Zend_Auth_Result(
            Zend_Auth_Result::SUCCESS,
            $this->_identity,
            array()
        );
    }

    public function getAuthenticatedUser()
    {
        return $this->_user;
    }


}