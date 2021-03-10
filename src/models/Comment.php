<?php

namespace models;

use core\Entity;
use DateTime;

/**
 * Comment entity
 */
class Comment extends Entity
{
    private $id;
    private $username;
    private $content;
    private $commentDate;
    private $commentState;
    private $postId;
    private $errors = [];

    const INVALID_USERNAME = 1;
    const INVALID_CONTENT = 2;
    
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
     * Username setter
     *
     * @param string $_username username
     * 
     * @return void
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * Content setter
     *
     * @param string $_content content
     * 
     * @return void
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * CommentDate setter
     *
     * @param DateTime $_commentDate comment date
     * 
     * @return void
     */
    public function setCommentDate(DateTime $commentDate)
    {
        $this->commentDate = $commentDate;
    }

    /**
     * CommentState setter
     *
     * @param boolean $_commentState comment state
     * 
     * @return void
     */
    public function setCommentState(bool $commentState)
    {
        $this->commentState = $commentState;
    }

    /**
     * PostId setter
     *
     * @param integer $_postId post id
     * 
     * @return void
     */
    public function setPostId(int $postId)
    {
        $this->postId = $postId;
    }
    // SETTERS END

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
     * Username getter
     *
     * @return void
     */
    public function getUsername()
    {
        return $this->username;
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
     * CommentDate getter
     *
     * @return void
     */
    public function getCommentDate()
    {
        return $this->commentDate;
    }

    /**
     * CommentState getter
     *
     * @return void
     */
    public function getCommentState()
    {
        return $this->commentState;
    }

    /**
     * PostId getter
     *
     * @return void
     */
    public function getPostId()
    {
        return $this->postId;
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
    // GETTERS END

    /**
     * Username and content validation
     *
     * @return boolean
     */
    public function isValid()
    {
        if (empty($this->username)) {
            $this->errors[] = self::INVALID_USERNAME;
        }
        
        if (empty($this->content)) {
            $this->errors[] = self::INVALID_CONTENT;
        }

        return empty($this->errors);
    }      
}
