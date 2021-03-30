<?php
namespace models;

use Cocur\Slugify\Slugify;
use core\Entity;
use DateTime;
/**
 * Post entity
 */
class Post extends Entity
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
    private $validComment;
    private $newComment;
    private $errors = [];

    const INVALID_TITLE = 1;
    const INVALID_AUTHOR = 2;
    const INVALID_TEASER = 3;
    const INVALID_CONTENT = 4;

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
     * @param string $slug slug
     * 
     * @return void
     */
    public function setSlug(string $slug)
    {
        $slugify = new Slugify();
        $slug = $slugify->slugify($this->title);
        $this->slug = $slug;
    }

    /**
     * ValidComment setter
     *
     * @param integer $validComment valid comment count
     * 
     * @return void
     */
    public function setValidComment(int $validComment)
    {
        $this->validComment = $validComment;
    }

    /**
     * NewComment setter
     *
     * @param integer $newComment new comment count
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
     * @param array $errors errors array
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
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Title getter
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Teaser getter
     *
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * Content getter
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Author getter
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
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
     * AddingDate getter
     *
     * @return string
     */
    public function getAddingDate()
    {
        return $this->addingDate;
    }

    /**
     * ModifDate getter
     *
     * @return string
     */
    public function getModifDate()
    {
        return $this->modifDate;
    }

    /**
     * Slug getter
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * ValidComment getter
     *
     * @return integer
     */
    public function getValidComment(): int
    {
        return $this->validComment;
    }

    /**
     * NewComment getter
     *
     * @return integer
     */
    public function getNewComment(): int
    {
        return $this->newComment;
    }

    /**
     * Errors getter
     *
     * @return array
     */
    public function getErrors():array
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
