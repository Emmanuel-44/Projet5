<?php
namespace controllers;

use Cocur\Slugify\Slugify;
use core\DBFactory;
use core\Image;
use models\Post;
use models\PostManager;
use models\CommentManager;
use core\TwigFactory;

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
        $db = DBFactory:: dbConnect();
        $PostManager = new PostManager($db);
        $posts = $PostManager->getList();
        $twig = TwigFactory::twig();
        echo $twig->render(
            'frontend/blogView.twig', array(
            'posts' => $posts
            )
        );
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
        $post = $PostManager->getPost($id);
        $comments = $CommentManager->getList($id);
        $twig = TwigFactory::twig();
        echo $twig->render(
            'frontend/singleView.twig', array(
            'post' => $post,
            'comments' => $comments
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
        if (!empty($_SESSION['user']) && in_array('ADMIN', $_SESSION['user']['role'])) {

            $db = DBFactory:: dbConnect();
            $PostManager = new PostManager($db);
            $posts = $PostManager->getList();
            $twig = TwigFactory::twig();
            echo $twig->render(
                'backend/homeView.twig', array(
                'posts' => $posts
                )
            );
        } else {
            header('location: http://localhost/Projet5');
        }   
    }

    /**
     * Add post controller
     *
     * @return void
     */
    public function create()
    {
        $twig = TwigFactory::twig();
        
        if (!empty($_SESSION['user']) && in_array('ADMIN', $_SESSION['user']['role'])) {

            if (isset($_POST['username']) && isset($_POST['title']) 
                && isset($_POST['teaser']) && isset($_POST['content'])
            ) {   
                $imagePath = Image::getImage('post');
                
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
                    'validComment' => 0,
                    'newComment' => 0
                    ]
                );
                
                if ($post->isValid()) {
                    Image::uploadImage('post');
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
        } else {
            header('location: http://localhost/Projet5');
        }
    }

    /**
     * Read a post controller in administration
     *
     * @return void
     */
    public function read()
    {
        if (!empty($_SESSION['user']) && in_array('ADMIN', $_SESSION['user']['role'])) {
            
            $db = DBFactory::dbConnect();
            $PostManager = new PostManager($db);
            $CommentManager = new CommentManager($db);
            $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
            $post = $PostManager->getPost($id);
            $comments = $CommentManager->getList($id);
            $twig = TwigFactory::twig();
            echo $twig->render(
                'backend/readView.twig', array(
                'post' => $post,
                'comments' => $comments,
                )
            );
        } else {
            header('location: http://localhost/Projet5');
        }   
    }

    /**
     * Update a form controller in administration
     *
     * @return void
     */
    public function update()
    {
        $twig = TwigFactory::twig();

        if (!empty($_SESSION['user']) && in_array('ADMIN', $_SESSION['user']['role'])) {

            $db = DBFactory::dbConnect();
            $PostManager = new PostManager($db);
            $CommentManager = new CommentManager($db);
            $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
            $post = $PostManager->getPost($id);
            $countValid = $CommentManager->count($id);
            $countNew = $CommentManager->countNew($id);

            if (isset($_POST['username']) && isset($_POST['title']) 
                && isset($_POST['teaser']) && isset($_POST['content'])
            ) {
                $imagePath = Image::getImage('post');

                $slugify = new Slugify();
                $slug = $slugify->slugify($_POST['title']);

                $post = new Post(
                    [
                    'id' => $id,
                    'author' => $_POST['username'],
                    'title' => $_POST['title'],
                    'teaser' => $_POST['teaser'],
                    'content' => $_POST['content'],
                    'imagePath' => $imagePath,
                    'slug' => $slug,
                    'validComment' => $countValid,
                    'newComment' => $countNew
                    ]
                );

                if ($post->isValid()) {
                    Image::uploadImage('post');
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
        } else {
            header('location: http://localhost/Projet5');
        }     
    }

    /**
     * Delete a form controller in administration
     *
     * @return void
     */
    public function delete()
    {
        if (!empty($_SESSION['user']) && in_array('ADMIN', $_SESSION['user']['role'])) {

            $db = DBFactory::dbConnect();
            $PostManager = new PostManager($db);
            $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
            $PostManager->delete($id);
            header('location:../../admin');
        } else {
            header('location: http://localhost/Projet5');
        }
    }
}
