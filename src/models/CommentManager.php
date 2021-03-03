<?php
namespace models;

class CommentManager
{
    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function add(Comment $comment)
    {
        $req = $this->db->prepare('INSERT INTO comment(username, content, commentDate, commentState, postId) VALUES(:username, :content, NOW(), :commentState, :postId)');
        $req->bindValue(':username', $comment->getUsername());
        $req->bindValue(':content', $comment->getContent());
        $req->bindValue(':commentState', $comment->getCommentState());
        $req->bindValue(':postId', $comment->getPostId());
        $req->execute();
    }

    public function countNew($postId)
    {
        $req = $this->db->query("SELECT COUNT(*) AS countNew FROM comment WHERE commentState = '0' AND postId=" . $postId);
        $req->execute();
        $count = $req->fetch()['countNew'];
        return $count;
    }

    public function count($postId)
    {
        $req = $this->db->query("SELECT COUNT(*) AS countComments FROM comment WHERE commentState = '1' AND postId=" . $postId);
        $req->execute();
        $count = $req->fetch()['countComments'];
        return $count;
    }
}