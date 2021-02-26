<?php
namespace controllers;

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
        $twig = TwigFactory::twig();
        echo $twig->render('backend/createView.twig');
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