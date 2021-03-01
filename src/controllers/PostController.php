<?php
namespace controllers;

use Cocur\Slugify\Slugify;
use models\DBFactory;
use models\Post;
use models\PostManager;

class PostController
{
    public function index()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('frontend/homeView.twig');
    }

    public function blog()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('frontend/blogView.twig');
    }

    public function single()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('frontend/singleView.twig');
    }

    public function adminIndex()
    {
        $db = DBFactory:: dbConnect();
        $PostManager = new PostManager($db);
        $posts = $PostManager->getList();
        $twig = TwigFactory::twig();
        echo $twig->render('backend/homeView.twig', array(
            'posts' => $posts
        ));
    }

    public function create()
    {
        $twig = TwigFactory::twig();

        if (isset($_POST['username']) && isset($_POST['title']) && isset($_POST['teaser']) && isset($_POST['content']))
        {   
            if (!empty($_FILES['image']['name']))
            {
                $image = $_FILES['image']['name'];
            }
            else
            {
                $image = 'defaut.png';
            }
            
            $slugify = new Slugify();
            $slug = $slugify->slugify($_POST['title']);

            $post = new Post([
                'author' => $_POST['username'],
                'title' => $_POST['title'],
                'teaser' => $_POST['teaser'],
                'content' => $_POST['content'],
                'imagePath' => "public/img/" . $image,
                'slug' => $slug,
                'newComment' => '0'
            ]);
            
            
            if ($post->isValid())
            {
                $db = DBFactory::dbConnect();
                $PostManager = new PostManager($db);
                $PostManager->add($post);
                echo $twig->render('backend/createView.twig', array(
                    'post' => $post,
                ));
            }
            else
            {
                echo $twig->render('backend/createView.twig', array (
                    'post' => $post
                ));
            }
        }
        else
        {
            echo $twig->render('backend/createView.twig');
        }     
    }

    public function read()
    {
        $db = DBFactory::dbConnect();
        $PostManager = new PostManager($db);
        $id = substr($_SERVER['REQUEST_URI'], -1);
        $post = $PostManager->getSingle($id);
        $twig = TwigFactory::twig();
        echo $twig->render('backend/readView.twig', array(
            'post' => $post
        ));
    }

    public function update()
    {
        $twig = TwigFactory::twig();

        $db = DBFactory::dbConnect();
        $PostManager = new PostManager($db);
        $id = substr($_SERVER['REQUEST_URI'], -1);
        $post = $PostManager->getSingle($id);

        if (isset($_POST['username']) && isset($_POST['title']) && isset($_POST['teaser']) && isset($_POST['content']))
        {
            if (!empty($_FILES['image']['name']))
            {
                $image = $_FILES['image']['name'];
            }
            else
            {
                $image = 'defaut.png';
            }

            $slugify = new Slugify();
            $slug = $slugify->slugify($_POST['title']);

            $post = new Post([
                'id' => $post->getId(),
                'author' => $_POST['username'],
                'title' => $_POST['title'],
                'teaser' => $_POST['teaser'],
                'content' => $_POST['content'],
                'imagePath' => $image,
                'slug' => $slug
            ]);

            if ($post->isValid())
            {
                $PostManager->update($post);
                echo $twig->render('backend/updateView.twig', array(
                    'post' => $post
                ));
            }
            else
            {
                echo $twig->render('backend/updateView.twig', array(
                    'post' => $post
                ));
            }          
        }
        else
        {
            $post->setErrors(['vide']);
            echo $twig->render('backend/updateView.twig', array(
                'post' => $post
            ));
        }    
    }
}