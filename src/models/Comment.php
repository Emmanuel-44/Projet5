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
    // GETTERS END
}