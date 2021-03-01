<?php
namespace models;

use DateTime;
use PDO;

class PostManager
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function add(Post $post)
    {
        $req = $this->db->prepare('INSERT INTO post(title, teaser, author, content, imagePath, addingDate, modifDate, slug, newComment) VALUES(:title, :teaser, :author, :content, :imagePath, NOW(), NOW(), :slug, :newComment)');
        $req->bindValue(':title', $post->getTitle());
        $req->bindValue(':teaser', $post->getTeaser());
        $req->bindValue(':author', $post->getAuthor());
        $req->bindValue(':content', $post->getContent());
        $req->bindValue(':imagePath', $post->getImagePath());
        $req->bindValue(':slug', $post->getSlug());
        $req->bindValue(':newComment', $post->getNewComment());
        $req->execute();
    }

    public function update(Post $post)
    {
        $req = $this->db->prepare('UPDATE post SET author = :author, title = :title, teaser = :teaser, content = :content, imagePath = :imagePath, slug = :slug, modifDate = NOW() WHERE id = :id');
        $req->bindValue(':author', $post->getAuthor());
        $req->bindValue(':title', $post->getTitle());
        $req->bindValue(':teaser', $post->getTeaser());
        $req->bindValue(':content', $post->getContent());
        $req->bindValue(':imagePath', $post->getImagePath());
        $req->bindValue(':slug', $post->getSlug());
        $req->bindValue(':id', $post->getId());
        $req->execute();
    }

    public function getList() : array
    {
        $req = $this->db->query('SELECT * FROM post ORDER BY addingDate DESC');
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Post');
        $posts = $req->fetchAll();
        foreach ($posts as $post)
        {
            $post->setAddingDate(new DateTime($post->getAddingDate()));
            $post->setModifDate(new DateTime($post->getModifDate()));
        }
        return $posts;
    }

    public function getSingle($id) : object
    {
        $req = $this->db->prepare('SELECT * FROM post WHERE id = :id');
        $req->bindValue(':id', $id);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\Post');
        $post = $req->fetch();
        $post->setAddingDate(new DateTime($post->getAddingDate()));
        $post->setModifDate(new DateTime($post->getModifDate()));
        return $post;
    }
}