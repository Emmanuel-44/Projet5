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

    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath; 
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
}