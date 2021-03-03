<?php
namespace controllers;

use models\Comment;
use models\CommentManager;
use models\DBFactory;
use models\PostManager;
use models\Post;

class CommentController
{
    public function add()
    {
        $db = DBFactory::dbConnect();
        $twig = TwigFactory::twig();
        $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        
        if(isset($_POST['username']) && isset($_POST['content']))
        {
            $comment = new Comment([
                'username' => $_POST['username'],
                'content' => $_POST['content'],
                'commentState' => '0',
                'postId' => $id
            ]);
    
            if($comment->isValid())
            { 
                $CommentManager = new CommentManager($db);
                $CommentManager->add($comment);
        
                $PostManager = new PostManager($db);
                $post = $PostManager->getSingle($id);
                $count = $CommentManager->countNew($post->getId());
        
                $post = new Post([
                    'id' => $post->getId(),
                    'title' => $post->getTitle(),
                    'author' => $post->getAuthor(),
                    'teaser' => $post->getTeaser(),
                    'content' => $post->getContent(),
                    'slug' => $post->getSlug(),
                    'imagePath' => $post->getImagePath(),
                    'newComment' => $count
                ]);
                
                $PostManager->update($post);
                echo $twig->render('frontend/singleView.twig', array(
                    'post' => $post,
                    'comment' => $comment
                ));
            }
            else
            {
                $PostManager = new PostManager($db);
                $post = $PostManager->getSingle($id);
                echo $twig->render('frontend/singleView.twig', array(
                    'post' => $post,
                    'comment' => $comment
                ));
            }
        }
        else
        {
            $PostManager = new PostManager($db);
            $post = $PostManager->getSingle($id);
            echo $twig->render('frontend/singleView.twig', array(
                'post' => $post
            ));
        }  
    }   
}