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
            imagePath, addingDate, modifDate, slug, validComment, newComment) 
            VALUES(:title, :teaser, :author, :content, :imagePath, 
            NOW(), NOW(), :slug, :validComment, :newComment )'
        );
        $req->bindValue(':title', $post->getTitle());
        $req->bindValue(':teaser', $post->getTeaser());
        $req->bindValue(':author', $post->getAuthor());
        $req->bindValue(':content', $post->getContent());
        $req->bindValue(':imagePath', $post->getImagePath());
        $req->bindValue(':slug', $post->getSlug());
        $req->bindValue(':validComment', $post->getValidComment());
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
            modifDate = NOW(), validComment = :validComment, 
            newComment = :newComment 
            WHERE id = :id'
        );
        $req->bindValue(':author', $post->getAuthor());
        $req->bindValue(':title', $post->getTitle());
        $req->bindValue(':teaser', $post->getTeaser());
        $req->bindValue(':content', $post->getContent());
        $req->bindValue(':imagePath', $post->getImagePath());
        $req->bindValue(':slug', $post->getSlug());
        $req->bindValue(':validComment', $post->getValidComment());
        $req->bindValue(':newComment', $post->getNewComment());
        $req->bindValue(':id', $post->getId());
        $req->execute();
    }

    /**
     * Delete a post
     *
     * @param int $id post id
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
        $req = $this->_db->query('SELECT * FROM post ORDER BY addingDate DESC LIMIT 0, 3');
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Post');
        $posts = $req->fetchAll();
        foreach ($posts as $post) {
            $post->setId((int)$post->getId());
            $post->setValidComment((int)$post->getValidComment());
            $post->setNewComment((int)$post->getNewComment());
            $post->setAddingDate(new DateTime($post->getAddingDate()));
            $post->setModifDate(new DateTime($post->getModifDate()));
        }
        return $posts;
    }

    /**
     * Read a post
     *
     * @param int $id post id
     * 
     * @return Post
     */
    public function getPost($id) : Post
    {
        $req = $this->_db->prepare('SELECT * FROM post WHERE id = :id');
        $req->bindValue(':id', $id);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Post');
        $post = $req->fetch();
        $post->setId((int)$post->getId());
        $post->setValidComment((int)$post->getValidComment());
        $post->setNewComment((int)$post->getNewComment());
        $post->setAddingDate(new DateTime($post->getAddingDate()));
        $post->setModifDate(new DateTime($post->getModifDate()));
        return $post;
    }

    /**
     * Check if id and slug exist
     *
     * @param integer $id
     * @param string $slug
     * 
     * @return array|false
     */
    public function checkPost(int $id, string $slug)
    {
        $req = $this->_db->prepare("SELECT id, slug FROM post WHERE id = $id AND slug = '$slug'");
        $req->execute();
        $check = $req->fetch();
        return $check;
    }

    // PAGINATION

    /**
     * Posts count
     *
     * @return integer
     */
    public function countPost()
    {
        $req = $this->_db->query('SELECT COUNT(*) AS nb_posts FROM post');
        $req->execute();
        $result = $req->fetch();
        $nbPosts = (int)$result['nb_posts'];
        return $nbPosts;
    }

    /**
     * Get posts list
     *
     * @param int $firstPage
     * @param int $perPage
     * 
     * @return array
     */
    public function getPosts($firstPage, $perPage)
    {
        $req = $this->_db->prepare('SELECT * FROM post ORDER BY addingDate DESC LIMIT :firstPage, :perPage');
        $req->bindValue(':firstPage', $firstPage, PDO::PARAM_INT);
        $req->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Post');
        $posts = $req->fetchAll();
        foreach ($posts as $post) {
            $post->setId((int)$post->getId());
            $post->setValidComment((int)$post->getValidComment());
            $post->setNewComment((int)$post->getNewComment());
            $post->setAddingDate(new DateTime($post->getAddingDate()));
            $post->setModifDate(new DateTime($post->getModifDate()));
        }
        return $posts;
    }    
}
