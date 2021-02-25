<?php

namespace models;

class User
{
    private $id;
    private $username;
    private $contactEmail;
    private $password;
    private $addingDate;
    private $role = [];
    private $errors = [];

    const INVALID_USERNAME = 1;
    const INVALID_EMAIL = 2;
    const INVALID_PASSWORD = 3;

    public function __construct($valeurs = [])
    {
      if (!empty($valeurs))
      {
        $this->hydrate($valeurs);
      }
    }
    
    public function hydrate($donnees)
    {
      foreach ($donnees as $attribut => $valeur)
      {
        $methode = 'set'.ucfirst($attribut);
        
        if (is_callable([$this, $methode]))
        {
          $this->$methode($valeur);
        }
      }
    }

    //SETTERS
    public function setId($id)
    {
      $this->id = $id;
    }

    public function setUsername($username)
    {
        if(!is_string($username) || empty($username))
        {
            $this->errors[] = self::INVALID_USERNAME;
        }
        else
        {
        $this->username = $username;
        }
    }

    public function setContactEmail($contactEmail)
    {
        if(!filter_var($contactEmail, FILTER_VALIDATE_EMAIL))
        {
            $this->errors[] = self::INVALID_EMAIL;
        }
        else
        {
            $this->contactEmail = $contactEmail;
        }
    }

    public function setPassword($password)
    {
        if(!is_string($password) || empty($password))
        {
            $this->errors[] = self::INVALID_PASSWORD;
        }
        else
        {
            $this->password = $password;
        }
    }

    public function setAddingDate($addingDate)
    {
      $this->addingDate = $addingDate;
    }

    public function setRole($role)
    {
      $this->role = $role;
    }

    //GETTERS
    public function getId()
    {
      return $this->id;
    }

    public function getUsername()
    {
      return $this->username;
    }

    public function getContactEmail()
    {
      return $this->contactEmail;
    }

    public function getPassword()
    {
      return $this->password;
    }

    public function getAddingDate()
    {
      return $this->addingDate;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}