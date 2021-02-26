<?php

namespace models;

class User
{
    private $id;
    private $username;
    private $contactEmail;
    private $password;
    private $addingDate;

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
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
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
}