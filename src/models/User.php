<?php

namespace models;

use DateTime;

/**
 * User entity
 */
class User
{
    private $_id;
    private $_username;
    private $_contactEmail;
    private $_password;
    private $_addingDate;

    /**
     * Construct
     *
     * @param array $values values array
     */
    public function __construct($values = [])
    {
        if (!empty($values)) {
            $this->hydrate($values);
        }
    }
    
    /**
     * Hydrate
     *
     * @param [array] $datas datas array
     * 
     * @return void
     */
    public function hydrate($datas)
    {
        foreach ($datas as $attribut => $value) {
            $method = 'set'.ucfirst($attribut);
          
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }

    //SETTERS

    /**
     * Id setter
     *
     * @param integer $_id id
     * 
     * @return void
     */
    public function setId(int $_id)
    {
        $this->_id = $_id;
    }

    /**
     * Username setter
     *
     * @param string $_username username
     * 
     * @return void
     */
    public function setUsername(string $_username)
    {
        $this->_username = $_username;
    }

    /**
     * ContactEmail setter
     *
     * @param [string] $_contactEmail contact email
     * 
     * @return void
     */
    public function setContactEmail(string $_contactEmail)
    {
        $this->_contactEmail = $_contactEmail;
    }

    /**
     * Password setter
     *
     * @param string $_password password
     * 
     * @return void
     */
    public function setPassword(string $_password)
    {
        $this->_password = $_password;
    }

    /**
     * AddingDate setter
     *
     * @param DateTime $_addingDate adding date
     * 
     * @return void
     */
    public function setAddingDate(DateTime $_addingDate)
    {
        $this->_addingDate = $_addingDate;
    }

    /**
     * Role setter
     *
     * @param [array] $_role roles array
     * 
     * @return void
     */
    public function setRole(array $_role)
    {
        $this->_role = $_role;
    }

    //GETTERS

    /**
     * Id getter
     *
     * @return void
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Username getter
     *
     * @return void
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * ContactEmail getter
     *
     * @return void
     */
    public function getContactEmail()
    {
        return $this->_contactEmail;
    }

    /**
     * Password getter
     *
     * @return void
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * AddingDate getter
     *
     * @return void
     */
    public function getAddingDate()
    {
        return $this->_addingDate;
    }

    /**
     * Role getter
     *
     * @return void
     */
    public function getRole()
    {
        return $this->_role;
    }
}
