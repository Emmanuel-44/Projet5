<?php
namespace controllers;

use models\Comment;
use models\CommentManager;
use core\DBFactory;
use models\PostManager;
use models\Post;
use core\TwigFactory;

/**
 * Comment controller
 */
class CommentController
{
    /**
     * Add a comment
     *
     * @return void
     */
    public function add()
    {
        $db = DBFactory::dbConnect();
        $twig = TwigFactory::twig();
        $Postid = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $CommentManager = new CommentManager($db);
        $PostManager = new PostManager($db);
            
        // Si le formulaire est validé
        if (isset($_POST['username']) && isset($_POST['content'])) {

            // Si l'utilisateur est connecté
            if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
                $userImagePath = $_SESSION['user']['imagePath'];
                $comment = new Comment(
                    [
                    'username' => $_POST['username'],
                    'content' => $_POST['content'],
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
                    echo $twig->render(
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
                    echo $twig->render(
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
                echo $twig->render(
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
            echo $twig->render(
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
        if (!empty($_SESSION['user']) && in_array('ADMIN', $_SESSION['user']['role'])) {

            $db = DBFactory::dbConnect();
            $CommentManager = new CommentManager($db);
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

            $PostManager = new PostManager($db);
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
        if (!empty($_SESSION['user']) && in_array('ADMIN', $_SESSION['user']['role'])) {

            $db = DBFactory::dbConnect();
            $CommentManager = new CommentManager($db);
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

            $PostManager = new PostManager($db);
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
