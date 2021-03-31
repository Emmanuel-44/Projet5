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
        $PostId = (int)substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $CommentManager = new CommentManager($this->db);
        $PostManager = new PostManager($this->db);
        $comments = $CommentManager->getList($PostId);
        $post = $PostManager->getPost($PostId);
        $slug = $post->getSlug();
            
        // Si le formulaire est validé
        if ($this->formValidate($_POST, ['username','content'])) {

            // Si l'utilisateur est connecté avec validation du token
            if ($this->sessionExist('user', 'USER') && $this->tokenValidate("http://localhost/Projet5/blog/$slug-$PostId", 15)) {

                $userImagePath = $_SESSION['user']['imagePath'];
                $comment = new Comment(
                    [
                    'username' => htmlspecialchars($_POST['username']),
                    'content' => htmlspecialchars($_POST['content']),
                    'commentState' => false,
                    'postId' => $PostId,
                    'userImagePath' => $userImagePath
                    ]
                );
                
                // Si les données sont valides
                if ($comment->isValid()) { 
                    $CommentManager->add($comment);
                    $countNew = $CommentManager->countNew($PostId);
                    $countValid = $CommentManager->count($PostId);
                    $post = new Post(
                        [
                        'id' => $PostId,
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
                }
                $this->render(
                    'frontend/singleView.twig', array(
                    'post' => $post,
                    'comment' => $comment,
                    'comments' => $comments
                    )
                );
                // Si l'utilisateur n'est pas connecté
            } else {
                session_unset();
                $error = 'Vous devez être connecté pour envoyer un message';
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
            $this->render(
                'frontend/singleView.twig', array(
                'post' => $post,
                'comments' => $comments
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
                substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1), '/', true
            );
            $comment = $CommentManager->getComment($postId, $commentId);
            $comment = new Comment(
                [
                    'id' => $comment->getId(),
                    'content' => 'Message modéré par l\'administration',
                    'commentState' => true
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
                substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1), '/', true
            );
            $comment = $CommentManager->getComment($postId, $commentId);
            $comment = new Comment(
                [
                    'id' => $comment->getId(),
                    'content' => $comment->getContent(),
                    'commentState' => true
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
