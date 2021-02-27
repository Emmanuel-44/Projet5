<?php
namespace models;

use DateTime;

class Post
{
    private $id;
    private $title;
    private $teaser;
    private $author;
    private $content;
    private $imagePath;
    private $addingDate;
    private $modifDate;
    private $slug;
    private $newComment;
    private $errors = [];

    const INVALID_TITLE = 1;
    const INVALID_AUTHOR = 2;
    const INVALID_TEASER = 3;
    const INVALID_CONTENT = 4;

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

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setTeaser(string $teaser)
    {
        $this->teaser = $teaser;  
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function setImagePath(string $imagePath)
    {
        $this->imagePath = $imagePath;
    }

    public function setAddingDate(DateTime $addingDate)
    {
        $this->addingDate = $addingDate;
    }
    
    public function setModifDate(Datetime $modifDate)
    {
        $this->modifDate = $modifDate;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function setNewComment(int $newComment)
    {
        $this->newComment = $newComment;
    }
    // END SETTERS

    // GETTERS
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getTeaser()
    {
        return $this->teaser;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    public function getAddingDate()
    {
        return $this->addingDate;
    }

    public function getModifDate()
    {
        return $this->modifDate;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getNewComment()
    {
        return $this->newComment;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    // END GETTERS

    public function isValid() : bool
    {
        if (empty($this->title))
        {
            $this->errors[] = self::INVALID_TITLE;
        }
        
        if (empty($this->author))
        {
            $this->errors[] = self::INVALID_AUTHOR;
        }
        
        if (empty($this->teaser))
        {
            $this->errors[] = self::INVALID_TEASER;
        }
        
        if (empty($this->content))
        {
            $this->errors[] = self::INVALID_CONTENT;
        }

        return empty($this->errors);
    }
}