<?php

namespace models;

use DateTime;
use core\Entity;
use core\Session;

/**
 * User entity
 */
class User extends Entity
{
    private $id;
    private $username;
    private $contactEmail;
    private $password;
    private $addingDate;
    private $imagePath;
    private $role = [];
    private $errors = [];

    const INVALID_USERNAME = 1;
    const INVALID_PASSWORD = 2;
    const INVALID_EMAIL = 3;

    //SETTERS

    /**
     * Id setter
     *
     * @param integer $id id
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
     * @param string $username username
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
     * @param string $contactEmail contact email
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
     * @param string $password password
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
     * @param DateTime $addingDate adding date
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
     * Role setter
     *
     * @param array $role role
     * 
     * @return void
     */
    public function setRole(array $role)
    {
        $this->role = $role;
    }

    /**
     * Errors setter
     *
     * @param string $errors error
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
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Username getter
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * ContactEmail getter
     *
     * @return string
     */
    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    /**
     * Password getter
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * AddingDate getter
     *
     * @return string
     */
    public function getAddingDate()
    {
        return $this->addingDate;
    }

    /**
     * ImagePath getter
     *
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * Role getter
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Errors getter
     *
     * @return array
     */
    public function getErrors(): array
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

        if (empty($this->contactEmail) 
            || !filter_var($this->contactEmail, FILTER_VALIDATE_EMAIL)
        ) {
            $this->errors[] = self::INVALID_EMAIL;
        }

        return empty($this->errors);
    }

    /**
     * Set session
     *
     * @return void
     */
    public function setSession()
    {
        Session::put('user', [
            'id' => $this->id,
            'username' => $this->username,
            'contactEmail' => $this->contactEmail,
            'addingDate' => $this->addingDate,
            'imagePath' => $this->imagePath,
            'role' => $this->role
        ]);

        $token = bin2hex(random_bytes(15));
        Session::put('token', $token);
        Session::put('token_time', time());
    }
}
