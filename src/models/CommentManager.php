<?php
namespace models;

use DateTime;
use PDO;

/**
 * Comment Manager
 */
class CommentManager
{
    private $_db;

    /**
     * Construct
     *
     * @param \PDO $_db bdd
     */
    public function __construct(\PDO $_db)
    {
        $this->_db = $_db;
    }

    /**
     * Add a comment
     *
     * @param Comment $comment entity
     * 
     * @return void
     */
    public function add(Comment $comment)
    {
        $req = $this->_db->prepare(
            'INSERT INTO comment
            (username, content, commentDate, commentState, postId) 
            VALUES(:username, :content, NOW(), :commentState, :postId)'
        );
        $req->bindValue(':username', $comment->getUsername());
        $req->bindValue(':content', $comment->getContent());
        $req->bindValue(':commentState', $comment->getCommentState());
        $req->bindValue(':postId', $comment->getPostId());
        $req->execute();
    }

    /**
     * Count new comment(s) from a post
     *
     * @param [int] $postId id from Post
     * 
     * @return string
     */
    public function countNew($postId) : string
    {
        $req = $this->_db->query(
            "SELECT COUNT(*) AS countNew FROM comment 
            WHERE commentState = '0' AND postId=" . $postId
        );
        $req->execute();
        $count = $req->fetch()['countNew'];
        return $count;
    }

    /**
     * Count all valid comments from a post
     *
     * @param [int] $postId id from Post
     * 
     * @return string
     */
    public function count($postId) : string
    {
        $req = $this->_db->query(
            "SELECT COUNT(*) AS countComments FROM comment 
            WHERE commentState = '1' AND postId=" . $postId
        );
        $req->execute();
        $count = $req->fetch()['countComments'];
        return $count;
    }

    /**
     * Read all comments from a post
     *
     * @param [int] $postId id from Post
     * 
     * @return array
     */
    public function read($postId) : array
    {
        $req = $this->_db->prepare(
            'SELECT * FROM comment WHERE postId = :postId 
            ORDER BY commentDate DESC'
        );
        $req->bindValue(':postId', $postId);
        $req->execute();
        $req->setFetchMode(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Comment'
        );
        $comments = $req->fetchAll();
        foreach ($comments as $comment) {
            $comment->setId((int)$comment->getId());
            $comment->setPostId((int)$comment->getPostId());
            $comment->setCommentState((bool)$comment->getCommentState());
            $comment->setCommentDate(new DateTime($comment->getCommentDate()));
        }
        return $comments;
    }

    /**
     * Get single comment
     *
     * @param [type] $postId id post
     * 
     * @param [type] $id id comment
     * 
     * @return Comment
     */
    public function single($postId, $id)
    {
        $req = $this->_db->prepare('SELECT id, username, content, commentDate, commentState FROM comment WHERE postId = :postId AND id = :id ORDER BY commentDate DESC');
        $req->bindValue(':postId', $postId);
        $req->bindValue(':id', $id);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Comment');
        $comment = $req->fetch();
        $comment->setId((int)$comment->getId());
        $comment->setPostId((int)$comment->getPostId());
        $comment->setCommentState((bool)$comment->getCommentState());
        $comment->setCommentDate(new DateTime($comment->getCommentDate()));
        return $comment;
    }

    /**
     * Update commentState
     *
     * @param Comment $comment entity
     * 
     * @return void
     */
    public function update(Comment $comment)
    {
        $req = $this->_db->prepare(
            'UPDATE comment SET commentState = :commentState 
            WHERE id =:id'
        );
        $req->bindValue(':commentState', $comment->getCommentState());
        $req->bindValue(':id', $comment->getId());
        $req->execute();
    }

    /**
     * Delete a comment
     *
     * @param [int] $id id from Comment
     * 
     * @return void
     */
    public function delete($id)
    {
        $this->_db->exec('DELETE FROM comment WHERE id=' .$id);
    }
}
