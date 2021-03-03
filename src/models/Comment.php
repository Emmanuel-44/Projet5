<?php

namespace models;

class Comment
{
    private $id;
    private $username;
    private $content;
    private $commentDate;
    private $commentState;
    private $postId;
    private $errors;

    const INVALID_USERNAME = 1;
    const INVALID_CONTENT = 2;
    
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

    // SETTERS
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setCommentDate($commentDate)
    {
        $this->commentDate = $commentDate;
    }

    public function setCommentState($commentState)
    {
        $this->commentState = $commentState;
    }

    public function setPostId(int $postId)
    {
        $this->postId = $postId;
    }
    // SETTERS END

    // GETTERS
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCommentDate()
    {
        return $this->commentDate;
    }

    public function getCommentState()
    {
        return $this->commentState;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    // GETTERS END

    public function isValid()
    {
        if(empty($this->username))
        {
            $this->errors[] = self::INVALID_USERNAME;
        }
        
        if (empty($this->content))
        {
            $this->errors[] = self::INVALID_CONTENT;
        }

        return empty($this->errors);
    }      
}