<?php
namespace models;

use DateTime;
use PDO;

/**
 * Post manager
 */
class PostManager
{
    private $_db;

    /**
     * Construt
     *
     * @param PDO $db bdd
     */
    public function __construct(PDO $db)
    {
        $this->_db = $db;
    }
    
    /**
     * Add a post
     *
     * @param Post $post entity
     * 
     * @return void
     */
    public function add(Post $post)
    {
        $req = $this->_db->prepare(
            'INSERT INTO post(title, teaser, author, content, 
            imagePath, addingDate, modifDate, slug, newComment) 
            VALUES(:title, :teaser, :author, :content, :imagePath, 
            NOW(), NOW(), :slug, :newComment)'
        );
        $req->bindValue(':title', $post->getTitle());
        $req->bindValue(':teaser', $post->getTeaser());
        $req->bindValue(':author', $post->getAuthor());
        $req->bindValue(':content', $post->getContent());
        $req->bindValue(':imagePath', $post->getImagePath());
        $req->bindValue(':slug', $post->getSlug());
        $req->bindValue(':newComment', $post->getNewComment());
        $req->execute();
    }

    /**
     * Update a post
     *
     * @param Post $post entity
     * 
     * @return void
     */
    public function update(Post $post)
    {
        $req = $this->_db->prepare(
            'UPDATE post SET author = :author, title = :title, teaser = :teaser, 
            content = :content, imagePath = :imagePath, slug = :slug, 
            modifDate = NOW(), newComment = :newComment 
            WHERE id = :id'
        );
        $req->bindValue(':author', $post->getAuthor());
        $req->bindValue(':title', $post->getTitle());
        $req->bindValue(':teaser', $post->getTeaser());
        $req->bindValue(':content', $post->getContent());
        $req->bindValue(':imagePath', $post->getImagePath());
        $req->bindValue(':slug', $post->getSlug());
        $req->bindValue(':newComment', $post->getNewComment());
        $req->bindValue(':id', $post->getId());
        $req->execute();
    }

    /**
     * Delete a post
     *
     * @param [int] $id post id
     * 
     * @return void
     */
    public function delete($id)
    {
        $this->_db->exec('DELETE FROM post WHERE id ='. $id);
        $this->_db->exec('DELETE FROM comment WHERE postId='. $id);
    }

    /** 
     * Get list of all posts
     *
     * @return array
     */
    public function getList() : array
    {
        $req = $this->_db->query('SELECT * FROM post ORDER BY addingDate DESC');
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Post');
        $posts = $req->fetchAll();
        foreach ($posts as $post) {
            $post->setId((int)$post->getId());
            $post->setNewComment((int)$post->getNewComment());
            $post->setAddingDate(new DateTime($post->getAddingDate()));
            $post->setModifDate(new DateTime($post->getModifDate()));
        }
        return $posts;
    }

    /**
     * Read a post
     *
     * @param [int] $id post id
     * 
     * @return Post
     */
    public function read($id) : Post
    {
        $req = $this->_db->prepare('SELECT * FROM post WHERE id = :id');
        $req->bindValue(':id', $id);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Post');
        $post = $req->fetch();
        $post->setId((int)$post->getId());
        $post->setNewComment((int)$post->getNewComment());
        $post->setAddingDate(new DateTime($post->getAddingDate()));
        $post->setModifDate(new DateTime($post->getModifDate()));
        return $post;
    }
}
