<?php
namespace models;

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
}