<?php
namespace controllers;

use Cocur\Slugify\Slugify;
use DateTime;
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
        $twig = TwigFactory::twig();
        echo $twig->render('backend/homeView.twig');
    }

    public function create()
    {
        if (isset($_POST['username']) && isset($_POST['title']) && isset($_POST['teaser']) && isset($_POST['content']))
        {
            if (!empty($_FILES['image']['name']))
            {
                $image = $_FILES['image']['name'];
            }
            else
            {
                $image = 'discord.jpg';
            }
            
            $slugify = new Slugify();
            $slug = $slugify->slugify($_POST['title']);

            $post = new Post([
                'author' => $_POST['username'],
                'title' => $_POST['title'],
                'teaser' => $_POST['teaser'],
                'content' => nl2br($_POST['content']),
                'imagePath' => "public/img/" . $image,
                'slug' => $slug,
                'newComment' => '0'
            ]);

            if ($post->isValid())
            {
                $db = DBFactory::dbConnect();
                $PostManager = new PostManager($db);
                $PostManager->add($post);
                $errors = $post->getErrors();
                $twig = TwigFactory::twig();
                echo $twig->render('backend/createView.twig', array(
                    'errors' => $errors
                ));
            }
            else
            {
                $errors = $post->getErrors();
                $twig = TwigFactory::twig();
                echo $twig->render('backend/createView.twig', array (
                    'errors' => $errors
                ));
            }
        }
        else
        {
            $twig = TwigFactory::twig();
            echo $twig->render('backend/createView.twig');
        }     
    }

    public function read()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('backend/readView.twig');
    }

    public function update()
    {
        $twig = TwigFactory::twig();
        echo $twig->render('backend/updateView.twig');
    }
}