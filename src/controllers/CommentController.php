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
        $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);

        if (isset($_POST['username']) && isset($_POST['content'])) {
            $comment = new Comment(
                [
                'username' => $_POST['username'],
                'content' => $_POST['content'],
                'commentState' => false,
                'postId' => $id
                ]
            );
    
            if ($comment->isValid()) { 
                $CommentManager = new CommentManager($db);
                $CommentManager->add($comment);
        
                $PostManager = new PostManager($db);
                $post = $PostManager->read($id);
                $countNew = $CommentManager->countNew($id);
                $count = $CommentManager->count($id);

                $post = new Post(
                    [
                    'id' => $id,
                    'title' => $post->getTitle(),
                    'author' => $post->getAuthor(),
                    'teaser' => $post->getTeaser(),
                    'content' => $post->getContent(),
                    'slug' => $post->getSlug(),
                    'imagePath' => $post->getImagePath(),
                    'newComment' => $countNew
                    ]
                );
                
                $comments = $CommentManager->read($id);
                $PostManager->update($post);
                echo $twig->render(
                    'frontend/singleView.twig', array(
                    'post' => $post,
                    'comment' => $comment,
                    'comments' => $comments,
                    'count' => $count
                    )
                );
            } else {
                $CommentManager = new CommentManager($db);
                $comments = $CommentManager->read($id);
                $count = $CommentManager->count($id);
                $PostManager = new PostManager($db);
                $post = $PostManager->read($id);
                echo $twig->render(
                    'frontend/singleView.twig', array(
                    'post' => $post,
                    'comment' => $comment,
                    'comments' => $comments,
                    'count' => $count
                    )
                );
            }
        } else {
            $CommentManager = new CommentManager($db);
            $comments = $CommentManager->read($id);
            $PostManager = new PostManager($db);
            $post = $PostManager->read($id);
            echo $twig->render(
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
        $db = DBFactory::dbConnect();
        $CommentManager = new CommentManager($db);
        $commentId = (int)substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
        $postId = (int)strstr(substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1), '/', -1);
        $CommentManager->delete($commentId);
        $PostManager = new PostManager($db);
        $post = $PostManager->read($postId);
        $count = $CommentManager->countNew($postId);

                $post = new Post(
                    [
                    'id' => $postId,
                    'title' => $post->getTitle(),
                    'author' => $post->getAuthor(),
                    'teaser' => $post->getTeaser(),
                    'content' => $post->getContent(),
                    'slug' => $post->getSlug(),
                    'imagePath' => $post->getImagePath(),
                    'newComment' => $count
                    ]
                );

        $PostManager->update($post);
        $slug = $post->getSlug();
        header("location: ../../../admin/article/$slug-$postId");
    }

    /**
     * Confirm a comment
     *
     * @return void
     */
    public function confirm()
    {
        $db = DBFactory::dbConnect();
        $CommentManager = new CommentManager($db);
        $commentId = (int)substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
        $postId = (int)strstr(substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1), '/', -1);
        $comment = $CommentManager->single($postId, $commentId);
        
        $comment = new Comment(
            [
                'id' => $comment->getId(),
                'username' => $comment->getUsername(),
                'content' => $comment->getContent(),
                'commentDate' => $comment->getCommentDate(),
                'commentState' => true,
                'postId' => $comment->getPostId(),
            ]
        );

        $CommentManager->update($comment);

        $PostManager = new PostManager($db);
        $post = $PostManager->read($postId);
        $count = $CommentManager->countNew($postId);

                $post = new Post(
                    [
                    'id' => $postId,
                    'title' => $post->getTitle(),
                    'author' => $post->getAuthor(),
                    'teaser' => $post->getTeaser(),
                    'content' => $post->getContent(),
                    'slug' => $post->getSlug(),
                    'imagePath' => $post->getImagePath(),
                    'newComment' => $count
                    ]
                );

        $PostManager->update($post);
        $slug = $post->getSlug();
        header("location: ../../../admin/article/$slug-$postId");
    }
}
