<?php

namespace models;

use core\Entity;
use DateTime;

/**
 * User entity
 */
class User extends Entity
{
    protected $id;
    protected $username;
    protected $contactEmail;
    protected $password;
    protected $addingDate;
    protected $imagePath;
    protected $role = ['USER'];
    protected $errors = [];

    const INVALID_USERNAME = 1;
    const INVALID_PASSWORD = 2;
    const INVALID_EMAIL = 3;

    //SETTERS

    /**
     * Id setter
     *
     * @param integer $_id id
     * 
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Username setter
     *
     * @param string $_username username
     * 
     * @return void
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * ContactEmail setter
     *
     * @param [string] $_contactEmail contact email
     * 
     * @return void
     */
    public function setContactEmail(string $contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * Password setter
     *
     * @param string $_password password
     * 
     * @return void
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * AddingDate setter
     *
     * @param DateTime $_addingDate adding date
     * 
     * @return void
     */
    public function setAddingDate(DateTime $addingDate)
    {
        $this->addingDate = $addingDate;
    }

    /**
     * ImagePath setter
     *
     * @param string $imagePath image path
     * 
     * @return void
     */
    public function setImagePath(string $imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * ImagePath setter
     *
     * @param string $imagePath image path
     * 
     * @return void
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
    // END SETTERS

    //GETTERS

    /**
     * Id getter
     *
     * @return void
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Username getter
     *
     * @return void
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * ContactEmail getter
     *
     * @return void
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Password getter
     *
     * @return void
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * AddingDate getter
     *
     * @return void
     */
    public function getAddingDate()
    {
        return $this->addingDate;
    }

    /**
     * ImagePath getter
     *
     * @return void
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Role getter
     *
     * @return void
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Errors getter
     *
     * @return void
     */
    public function getErrors()
    {
        return $this->errors;
    }
    // END GETTERS

    /**
     * Validation
     *
     * @return boolean
     */
    public function isValid() : bool
    {
        if (empty($this->username)) {
            $this->errors[] = self::INVALID_USERNAME;
        }
        
        if (empty($this->password)) {
            $this->errors[] = self::INVALID_PASSWORD;
        }

        if (empty($this->contactEmail) || !filter_var($this->contactEmail, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = self::INVALID_EMAIL;
        }

        return empty($this->errors);
    }
}
