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
    private $userImagePath;
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
     * @param string $username username
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
     * @param string $content content
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
     * @param DateTime $commentDate comment date
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
     * @param boolean $commentState comment state
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
     * @param integer $postId post id
     * 
     * @return void
     */
    public function setPostId(int $postId)
    {
        $this->postId = $postId;
    }

    /**
     * UserId setter
     *
     * @param string $UserImagePath user ImagePath
     * 
     * @return void
     */
    public function setUserImagePath(string $UserImagePath)
    {
        $this->userImagePath = $UserImagePath;
    }
    // SETTERS END

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
     * Username getter
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
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
     * CommentDate getter
     *
     * @return string
     */
    public function getCommentDate()
    {
        return $this->commentDate;
    }

    /**
     * CommentState getter
     *
     * @return boolean
     */
    public function getCommentState(): bool
    {
        return $this->commentState;
    }

    /**
     * PostId getter
     *
     * @return integer
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * UserImagePath getter
     *
     * @return string
     */
    public function getUserImagePath(): string
    {
        return $this->userImagePath;
    }

    /**
     * Errors getter
     *
     * @return array
     */
    public function getErrors(): array
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
