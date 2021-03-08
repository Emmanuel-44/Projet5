<?php
namespace models;

use DateTime;
/**
 * Post entity
 */
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

    // SETTERS

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
     * Title setter
     *
     * @param string $title title
     * 
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Teaser setter
     *
     * @param string $teaser teaser
     * 
     * @return void
     */
    public function setTeaser(string $teaser)
    {
        $this->teaser = $teaser;  
    }

    /**
     * Content setter
     *
     * @param string $content content
     * 
     * @return void
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * Author setter
     *
     * @param string $author author
     * 
     * @return void
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
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
     * AddingDate setter
     *
     * @param Datetime $addingDate adding date
     * 
     * @return void
     */
    public function setAddingDate(Datetime $addingDate)
    {
        $this->addingDate = $addingDate;
    }
    
    /**
     * ModifDate setter
     *
     * @param DateTime $modifDate modification date
     * 
     * @return void
     */
    public function setModifDate(DateTime $modifDate)
    {
        $this->modifDate = $modifDate;
    }

    /**
     * Slug setter
     *
     * @param string $_slug slug
     * 
     * @return void
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * NewComment setter
     *
     * @param integer $_newComment new comment count
     * 
     * @return void
     */
    public function setNewComment(int $newComment)
    {
        $this->newComment = $newComment;
    }

    /**
     * Errors setter
     *
     * @param array $_errors errors array
     * 
     * @return void
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
    // END SETTERS

    // GETTERS

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
     * Title getter
     *
     * @return void
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Teaser getter
     *
     * @return void
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Content getter
     *
     * @return void
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Author getter
     *
     * @return void
     */
    public function getAuthor()
    {
        return $this->author;
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
     * AddingDate getter
     *
     * @return void
     */
    public function getAddingDate()
    {
        return $this->addingDate;
    }

    /**
     * ModifDate getter
     *
     * @return void
     */
    public function getModifDate()
    {
        return $this->modifDate;
    }

    /**
     * Slug getter
     *
     * @return void
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * NewComment getter
     *
     * @return void
     */
    public function getNewComment()
    {
        return $this->newComment;
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
        if (empty($this->title)) {
            $this->errors[] = self::INVALID_TITLE;
        }
        
        if (empty($this->author)) {
            $this->errors[] = self::INVALID_AUTHOR;
        }
        
        if (empty($this->teaser)) {
            $this->errors[] = self::INVALID_TEASER;
        }
        
        if (empty($this->content)) {
            $this->errors[] = self::INVALID_CONTENT;
        }

        return empty($this->errors);
    }
}