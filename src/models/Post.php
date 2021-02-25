<?php
namespace models;

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
    const INVALID_TEASER = 2;
    const INVALID_AUTHOR = 3;
    const INVALID_CONTENT = 4;
    const INVALID_IMAGEPATH = 5;

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
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        if(!is_string($title) || empty($title))
        {
            $this->errors[] = self::INVALID_TITLE;
        }
        else
        {
            $this->title = $title;
        }
    }

    public function setTeaser($teaser)
    {
        if(!is_string($teaser) || empty($teaser))
        {
            $this->errors[] = self::INVALID_TEASER;
        }
        else
        {
            $this->teaser = $teaser;
        }  
    }

    public function setContent($content)
    {
        if(!is_string($content) || empty($content))
        {
            $this->errors[] = self::INVALID_CONTENT;
        }
        else
        {
            $this->content = $content;
        } 
    }

    public function setAuthor($author)
    {
        if(!is_string($author) || empty($author))
        {
            $this->errors[] = self::INVALID_AUTHOR;
        }
        else
        {
            $this->author = $author;
        }
    }

    public function setImagePath($imagePath)
    {
        if(!is_file($imagePath))
        {
            $this->errors[] = self::INVALID_IMAGEPATH;
        }
        else
        {
            $this->imagePath = $imagePath;
        } 
    }

    public function setAddingDate($addingDate)
    {
        $this->addingDate = $addingDate;
    }
    
    public function setModifDate($modifDate)
    {
        $this->modifDate = $modifDate;
    }

    public function setSlug($slug)
    {  
        $this->slug = $slug;
    }

    public function setNewComment($newComment)
    {
        $this->newComment = $newComment;
    }

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
}