<?php
namespace controllers;

use Cocur\Slugify\Slugify;
use models\DBFactory;
use models\Functions;
use models\Post;
use models\PostManager;
use models\CommentManager;

/**
 * Post controller
 */
class PostController
{
    /**
     * Home page controller
     *
     * @return void
     */
    public function index()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('frontend/homeView.twig');
    }

    /**
     * Blog page controller
     *
     * @return void
     */
    public function blog()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('frontend/blogView.twig');
    }
    
    /**
     * Read a single post controller
     *
     * @return void
     */
    public function single()
    {
        $db = DBFactory::dbConnect();
        $PostManager = new PostManager($db);
        $CommentManager = new CommentManager($db);
        $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $post = $PostManager->read($id);
        $comments = $CommentManager->read($id);
        $count = $CommentManager->count($id);
        $twig = TwigFactory::twig();
        echo $twig->render(
            'frontend/singleView.twig', array(
            'post' => $post,
            'comments' => $comments,
            'count' => $count
            )
        );
    }

    /**
     * Home admin page controller
     *
     * @return void
     */
    public function adminIndex()
    {
        $db = DBFactory:: dbConnect();
        $PostManager = new PostManager($db);
        $posts = $PostManager->getList();
        $twig = TwigFactory::twig();
        echo $twig->render(
            'backend/homeView.twig', array(
            'posts' => $posts
            )
        );
    }

    /**
     * Add post controller
     *
     * @return void
     */
    public function create()
    {
        $twig = TwigFactory::twig();

        if (isset($_POST['username']) && isset($_POST['title']) 
            && isset($_POST['teaser']) && isset($_POST['content'])
        ) {   
            if (!empty($_FILES['image']['name'])) {
                $imagePath = "public/img/" .$_FILES['image']['name'];
            } else {
                $imagePath = 'public/img/defaut.png';
            }
            
            $slugify = new Slugify();
            $slug = $slugify->slugify($_POST['title']);

            $post = new Post(
                [
                'author' => $_POST['username'],
                'title' => $_POST['title'],
                'teaser' => $_POST['teaser'],
                'content' => $_POST['content'],
                'imagePath' => $imagePath,
                'slug' => $slug,
                'newComment' => '0'
                ]
            );
            
            if ($post->isValid()) {
                Functions::uploadImage();
                $db = DBFactory::dbConnect();
                $PostManager = new PostManager($db);
                $PostManager->add($post);
                header('location: ../admin');
            } else {
                echo $twig->render(
                    'backend/createView.twig', array (
                    'post' => $post
                    )
                );
            }
        } else {
            echo $twig->render('backend/createView.twig');
        }     
    }

    /**
     * Read a post controller in administration
     *
     * @return void
     */
    public function read()
    {
        $db = DBFactory::dbConnect();
        $PostManager = new PostManager($db);
        $CommentManager = new CommentManager($db);
        $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $post = $PostManager->read($id);
        $comments = $CommentManager->read($id);
        $count = $CommentManager->count($id);
        $twig = TwigFactory::twig();
        echo $twig->render(
            'backend/readView.twig', array(
            'post' => $post,
            'comments' => $comments,
            'count' => $count
            )
        );
    }

    /**
     * Update a form controller in administration
     *
     * @return void
     */
    public function update()
    {
        $twig = TwigFactory::twig();

        $db = DBFactory::dbConnect();
        $PostManager = new PostManager($db);
        $CommentManager = new CommentManager($db);
        $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $post = $PostManager->read($id);
        $count = $CommentManager->countNew($id);

        if (isset($_POST['username']) && isset($_POST['title']) 
            && isset($_POST['teaser']) && isset($_POST['content'])
        ) {
            if (!empty($_FILES['image']['name'])) {
                $image = "public/img/" .$_FILES['image']['name'];
            } else {
                $image = 'public/img/defaut.png';
            }

            $slugify = new Slugify();
            $slug = $slugify->slugify($_POST['title']);

            $post = new Post(
                [
                'id' => $id,
                'author' => $_POST['username'],
                'title' => $_POST['title'],
                'teaser' => $_POST['teaser'],
                'content' => $_POST['content'],
                'imagePath' => $image,
                'slug' => $slug,
                'newComment' => $count
                ]
            );

            if ($post->isValid()) {
                Functions::uploadImage();
                $PostManager->update($post);
                echo $twig->render(
                    'backend/updateView.twig', array(
                    'post' => $post
                    )
                );
            } else {
                echo $twig->render(
                    'backend/updateView.twig', array(
                    'post' => $post
                    )
                );
            }          
        } else {
            $post->setErrors(['vide']);
            echo $twig->render(
                'backend/updateView.twig', array(
                'post' => $post
                )
            );
        }    
    }

    /**
     * Delete a form controller in administration
     *
     * @return void
     */
    public function delete()
    {
        $db = DBFactory::dbConnect();
        $PostManager = new PostManager($db);
        $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $PostManager->delete($id);
        header('location:../../admin');
    }
}
