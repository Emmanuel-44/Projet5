<?php
namespace controllers;

use core\Controller;
use models\Comment;
use models\CommentManager;
use models\PostManager;
use models\Post;

/**
 * Comment controller
 */
class CommentController extends Controller
{
    /**
     * Add a comment
     *
     * @return void
     */
    public function add()
    {
        $Postid = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $CommentManager = new CommentManager($this->db);
        $PostManager = new PostManager($this->db);
            
        // Si le formulaire est validé
        if ($this->formValidate($_POST, ['username','content'])) {

            // Si l'utilisateur est connecté
            if ($this->sessionExist('user', 'USER')) {

                $userImagePath = $_SESSION['user']['imagePath'];
                $comment = new Comment(
                    [
                    'username' => htmlspecialchars($_POST['username']),
                    'content' => htmlspecialchars($_POST['content']),
                    'commentState' => false,
                    'postId' => $Postid,
                    'userImagePath' => $userImagePath
                    ]
                );
                
                // Si les données sont valides
                if ($comment->isValid()) { 
                    $CommentManager->add($comment);
                    $post = $PostManager->getPost($Postid);
                    $countNew = $CommentManager->countNew($Postid);
                    $countValid = $CommentManager->count($Postid);
    
                    $post = new Post(
                        [
                        'id' => $Postid,
                        'title' => $post->getTitle(),
                        'author' => $post->getAuthor(),
                        'teaser' => $post->getTeaser(),
                        'content' => $post->getContent(),
                        'slug' => $post->getSlug(),
                        'imagePath' => $post->getImagePath(),
                        'validComment' => $countValid,
                        'newComment' => $countNew
                        ]
                    );
                    
                    $comments = $CommentManager->getList($Postid);
                    $PostManager->update($post);
                    $this->render(
                        'frontend/singleView.twig', array(
                        'post' => $post,
                        'comment' => $comment,
                        'comments' => $comments
                        )
                    );
                
                    // Si les données ne sont pas valides
                } else {
                    $comments = $CommentManager->getList($Postid);
                    $post = $PostManager->getPost($Postid);
                    $this->render(
                        'frontend/singleView.twig', array(
                        'post' => $post,
                        'comment' => $comment,
                        'comments' => $comments,
                        )
                    );
                }
                // Si l'utilisateur n'est pas connecté
            } else {
                $error = 'Vous devez être connecté pour envoyer un message';
                $comments = $CommentManager->getList($Postid);
                $post = $PostManager->getPost($Postid);
                $this->render(
                    'frontend/singleView.twig', array(
                    'post' => $post,
                    'comments' => $comments,
                    'error' => $error
                    )
                );
            }
            // Si le formulaire n'est pas rempli
        } else {
            $comments = $CommentManager->getList($Postid);
            $post = $PostManager->getPost($Postid);
            $this->render(
                'frontend/singleView.twig', array(
                'post' => $post,
                'comments' => $comments,
                )
            );
        }
    }

    /**
     * Delete a comment
     *
     * @return void
     */
    public function delete()
    {
        $CommentManager = new CommentManager($this->db);
        $PostManager = new PostManager($this->db);

        if ($this->sessionExist('user', 'ADMIN')) {
            $commentId = (int)substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
            $postId = (int)strstr(
                substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1), '/', -1
            );
            $comment = $CommentManager->getComment($postId, $commentId);
            $userImagePath = $_SESSION['user']['imagePath'];
            $comment = new Comment(
                [
                    'id' => $comment->getId(),
                    'username' => $comment->getUsername(),
                    'content' => 'Message modéré par l\'administration',
                    'commentDate' => $comment->getCommentDate(),
                    'commentState' => true,
                    'postId' => $comment->getPostId(),
                    'userId' => $userImagePath
                ]
            );

            $CommentManager->update($comment);

            $post = $PostManager->getPost($postId);
            $countNew = $CommentManager->countNew($postId);
            $countValid = $CommentManager->count($postId);

                    $post = new Post(
                        [
                        'id' => $postId,
                        'title' => $post->getTitle(),
                        'author' => $post->getAuthor(),
                        'teaser' => $post->getTeaser(),
                        'content' => $post->getContent(),
                        'slug' => $post->getSlug(),
                        'imagePath' => $post->getImagePath(),
                        'validComment' => $countValid,
                        'newComment' => $countNew
                        ]
                    );

            $PostManager->update($post);
            $slug = $post->getSlug();
            header("location: ../../../admin/article/$slug-$postId");
        } else {
            header('location: http://localhost/Projet5');
        }
    }

    /**
     * Confirm a comment
     *
     * @return void
     */
    public function confirm()
    {
        $CommentManager = new CommentManager($this->db);
        $PostManager = new PostManager($this->db);

        if ($this->sessionExist('user', 'ADMIN')) {
            $commentId = (int)substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
            $postId = (int)strstr(
                substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1), '/', -1
            );
            $comment = $CommentManager->getComment($postId, $commentId);
            $userImagePath = $_SESSION['user']['imagePath'];
            $comment = new Comment(
                [
                    'id' => $comment->getId(),
                    'username' => $comment->getUsername(),
                    'content' => $comment->getContent(),
                    'commentDate' => $comment->getCommentDate(),
                    'commentState' => true,
                    'postId' => $comment->getPostId(),
                    'userId' => $userImagePath
                ]
            );

            $CommentManager->update($comment);

            $post = $PostManager->getPost($postId);
            $countNew = $CommentManager->countNew($postId);
            $countValid = $CommentManager->count($postId);

                    $post = new Post(
                        [
                        'id' => $postId,
                        'title' => $post->getTitle(),
                        'author' => $post->getAuthor(),
                        'teaser' => $post->getTeaser(),
                        'content' => $post->getContent(),
                        'slug' => $post->getSlug(),
                        'imagePath' => $post->getImagePath(),
                        'validComment' => $countValid,
                        'newComment' => $countNew
                        ]
                    );

            $PostManager->update($post);
            $slug = $post->getSlug();
            header("location: ../../../admin/article/$slug-$postId");
        } else {
            header('location: http://localhost/Projet5');
        } 
    }
}
